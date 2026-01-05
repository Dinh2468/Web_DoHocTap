<?php
// admin/Views/Donhang/index.php
include_once '../../includes/header.php';
$db = new Db();
$search = $_GET['search'] ?? '';
$date = $_GET['date'] ?? '';
$status = $_GET['status'] ?? '';
$sql = "SELECT dh.*, kh.HoTen, kh.SDT 
        FROM donhang dh 
        JOIN khachhang kh ON dh.MaKH = kh.MaKH 
        WHERE 1=1";
$params = [];
if (!empty($search)) {
    $sql .= " AND (dh.MaDH LIKE ? OR kh.HoTen LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if (!empty($date)) {
    $sql .= " AND DATE(dh.NgayDat) = ?";
    $params[] = $date;
}
if (!empty($status)) {
    $sql .= " AND dh.TrangThai = ?";
    $params[] = $status;
}
$sql .= " ORDER BY dh.NgayDat DESC";
$orders = $db->query($sql, $params)->fetchAll();
?>
<div class="main-content-inner">
    <header class="main-header">
        <h2>Danh sách đơn hàng</h2>
    </header>
    <div class="toolbar">
        <form action="" method="GET">
            <input type="text" name="search" class="search-input" style="flex: 10; min-width: 450px;"
                placeholder="Tìm theo mã đơn, tên khách..." value="<?php echo htmlspecialchars($search); ?>">
            <input type="date" name="date" class="filter-select" style="flex: 1;"
                value="<?php echo htmlspecialchars($date); ?>">
            <select name="status" class="filter-select" style="flex: 1;">
                <option value="">Tất cả trạng thái</option>
                <option value="Chờ xử lý" <?php echo ($status == 'Chờ xử lý') ? 'selected' : ''; ?>>Chờ xử lý</option>
                <option value="Đang giao" <?php echo ($status == 'Đang giao') ? 'selected' : ''; ?>>Đang giao</option>
                <option value="Hoàn thành" <?php echo ($status == 'Hoàn thành') ? 'selected' : ''; ?>>Hoàn thành</option>
                <option value="Đã hủy" <?php echo ($status == 'Đã hủy') ? 'selected' : ''; ?>>Đã hủy</option>
            </select>
            <button type="submit" class="btn-filter">Lọc</button>
            <a href="index.php" class="btn-clear">Xóa lọc</a>
        </form>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong>#DH<?php echo str_pad($order['MaDH'], 4, '0', STR_PAD_LEFT); ?></strong></td>
                        <td>
                            <?php echo htmlspecialchars($order['HoTen']); ?><br>
                            <small style="color: #777;"><?php echo $order['SDT']; ?></small>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($order['NgayDat'])); ?></td>
                        <td style="font-weight: bold; color: var(--danger-color);">
                            <?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ
                        </td>
                        <td>
                            <?php
                            $statusClass = '';
                            switch ($order['TrangThai']) {
                                case 'Chờ xử lý':
                                    $statusClass = 'background: #FFFDE7; color: #FBC02D;';
                                    break;
                                case 'Đang giao':
                                    $statusClass = 'background: #FFF3E0; color: #EF6C00;';
                                    break;
                                case 'Hoàn thành':
                                    $statusClass = 'background: #E8F5E9; color: #2E7D32;';
                                    break;
                                case 'Đã hủy':
                                    $statusClass = 'background: #FFEBEE; color: #C62828;';
                                    break;
                            }
                            ?>
                            <span class="status" style="<?php echo $statusClass; ?>"><?php echo $order['TrangThai']; ?></span>
                        </td>
                        <td>
                            <a href="detail.php?id=<?php echo $order['MaDH']; ?>" class="btn-action btn-edit" title="Xem chi tiết">Xem chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once '../../includes/footer.php'; ?>