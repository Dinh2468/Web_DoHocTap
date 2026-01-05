<?php
// admin/Views/Khachhang/index.php
include_once '../../includes/header.php';
$db = new Db();
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$sql = "SELECT kh.*, tk.TrangThai 
        FROM khachhang kh 
        JOIN taikhoan tk ON kh.MaTK = tk.MaTK 
        WHERE 1=1";
$params = [];
if (!empty($search)) {
    $sql .= " AND (kh.HoTen LIKE ? OR kh.Email LIKE ? OR kh.SDT LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($status !== '') {
    $sql .= " AND tk.TrangThai = ?";
    $params[] = $status;
}
$sql .= " ORDER BY kh.MaKH DESC";
$customers = $db->query($sql, $params)->fetchAll();
?>
<div class="main-content-inner">
    <header class="main-header">
        <h2>Danh sách khách hàng</h2>
        <div>Số lượng: <strong><?php echo count($customers); ?></strong></div>
    </header>
    <div class="toolbar">
        <form action="" method="GET" style="display: flex; gap: 12px; width: 100%;">
            <input type="text" name="search" class="search-input" style="flex: 10;"
                placeholder="Tìm tên, email hoặc SĐT..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="status" class="filter-select" style="flex: 2;">
                <option value="">Tất cả trạng thái</option>
                <option value="1" <?php echo ($status === '1') ? 'selected' : ''; ?>>Đang hoạt động</option>
                <option value="0" <?php echo ($status === '0') ? 'selected' : ''; ?>>Đã bị khóa</option>
            </select>
            <button type="submit" class="btn-filter">Lọc</button>
            <a href="index.php" class="btn-clear">Xóa lọc</a>
        </form>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Khách hàng</th>
                    <th>Liên hệ</th>
                    <th>Địa chỉ</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $cust): ?>
                    <tr>
                        <td>#KH<?php echo $cust['MaKH']; ?></td>
                        <td>
                            <div class="user-cell">
                                <div>
                                    <strong><?php echo htmlspecialchars($cust['HoTen']); ?></strong><br>
                                    <span class="badge-rank rank-member">Thành viên</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($cust['Email']); ?><br>
                            <small style="color:#777"><?php echo $cust['SDT']; ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($cust['DiaChi']); ?></td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" <?php echo ($cust['TrangThai'] == 1) ? 'checked' : ''; ?>
                                    onchange="toggleAccountStatus(<?php echo $cust['MaTK']; ?>, this.checked)">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <button class="btn-view-profile" onclick="openProfile(<?php echo $cust['MaKH']; ?>)">
                                Xem hồ sơ
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal-overlay" id="customerModal" style="display: none;">
    <div class="modal-content">
        <div class="profile-side">
            <div class="large-avatar" id="detAvatar">?</div>
            <div class="profile-name" id="detName">Tên khách hàng</div>
            <div class="profile-meta">Mã KH: <span id="detID"></span></div>
            <div class="profile-stats">
                <div class="stat-row">
                    <span>Email:</span> <strong id="detEmail"></strong>
                </div>
                <div class="stat-row">
                    <span>Số điện thoại:</span> <strong id="detSDT"></strong>
                </div>
                <div class="stat-row">
                    <span>Địa chỉ:</span> <strong id="detAddress"></strong>
                </div>
            </div>
            <button class="btn-clear" style="margin-top:20px; width:100%; border:1px solid #ddd;" onclick="closeModal()">Đóng</button>
        </div>
        <div class="history-side">
            <div class="history-title" style="font-weight: bold; color: var(--primary-color); border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
                Lịch sử đơn hàng gần đây
            </div>
            <div id="orderHistoryContent">
            </div>
        </div>
    </div>
</div>
<script>
    function toggleUserStatus(id, status) {
        const active = status ? 1 : 0;
        fetch(`../../Controller/AdminUserController.php?action=toggle_status&id=${id}&status=${active}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) alert("Lỗi khi cập nhật trạng thái!");
            });
    }

    function closeModal() {
        document.getElementById('customerModal').style.display = 'none';
    }

    function toggleAccountStatus(maTK, isChecked) {
        const status = isChecked ? 1 : 0;
        fetch(`../../Controller/AdminUserController.php?action=toggle_status&id=${maTK}&status=${status}`)
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert("Lỗi: " + data.message);
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Không thể kết nối với máy chủ!");
            });
    }

    function openProfile(maKH) {
        const modal = document.getElementById('customerModal');
        fetch(`../../Controller/AdminUserController.php?action=get_details&id=${maKH}`)
            .then(res => res.json())
            .then(data => {
                const cust = data.customer;
                document.getElementById('detAvatar').innerText = cust.HoTen.charAt(0);
                document.getElementById('detName').innerText = cust.HoTen;
                document.getElementById('detID').innerText = '#KH' + cust.MaKH;
                document.getElementById('detEmail').innerText = cust.Email;
                document.getElementById('detSDT').innerText = cust.SDT;
                document.getElementById('detAddress').innerText = cust.DiaChi;
                let historyHTML = `
                <table style="width:100%; border-collapse: collapse; margin-top:10px;">
                    <thead>
                        <tr style="text-align:left; border-bottom:1px solid #eee;">
                            <th style="padding:10px 0;">Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>`;
                if (data.orders.length > 0) {
                    data.orders.forEach(order => {
                        historyHTML += `
                        <tr style="border-bottom:1px solid #f9f9f9;">
                            <td style="padding:10px 0;">#DH${order.MaDH}</td>
                            <td>${new Date(order.NgayDat).toLocaleDateString('vi-VN')}</td>
                            <td>${new Intl.NumberFormat('vi-VN').format(order.TongTien)}đ</td>
                            <td><span style="font-size:12px;">${order.TrangThai}</span></td>
                        </tr>`;
                    });
                } else {
                    historyHTML += `<tr><td colspan="4" style="padding:20px; text-align:center; color:#999;">Chưa có đơn hàng nào</td></tr>`;
                }
                historyHTML += `</tbody></table>`;
                document.getElementById('orderHistoryContent').innerHTML = historyHTML;
                modal.style.display = 'flex';
            });
    }
</script>
<?php include_once '../../includes/header.php'; ?>