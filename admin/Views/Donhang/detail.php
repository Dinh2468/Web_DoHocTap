<?php
// admin/Views/Donhang/detail.php
include_once '../../includes/header.php';
$db = new Db();
$id = $_GET['id'] ?? '';
if (empty($id)) {
    header("Location: index.php");
    exit();
}
$order = $db->query("SELECT dh.*, kh.* FROM donhang dh JOIN khachhang kh ON dh.MaKH = kh.MaKH WHERE dh.MaDH = ?", [$id])->fetch();

$items = $db->query("SELECT ctdh.*, sp.TenSP, sp.HinhAnh 
                     FROM chitietdh ctdh 
                     JOIN sanpham sp ON ctdh.MaSP = sp.MaSP 
                     WHERE ctdh.MaDH = ?", [$id])->fetchAll();
?>
<div class="main-content-inner">
    <header class="main-header">
        <h2>Chi tiết đơn hàng #DH<?php echo str_pad($id, 4, '0', STR_PAD_LEFT); ?></h2>
        <a href="index.php" style="text-decoration: none; color: var(--primary-color);">← Quay lại</a>
    </header>
    <div class="stats-grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="table-container">
            <h3 style="margin-bottom: 15px; color: var(--primary-color);">THÔNG TIN KHÁCH HÀNG</h3>
            <p><strong>Họ tên:</strong> <?php echo $order['HoTen']; ?></p>
            <p><strong>Điện thoại:</strong> <?php echo $order['SDT']; ?></p>
            <p><strong>Email:</strong> <?php echo $order['Email']; ?></p>
            <p><strong>Địa chỉ:</strong> <?php echo $order['DiaChi']; ?></p>
        </div>
        <div class="table-container">
            <h3 style="margin-bottom: 15px; color: var(--primary-color);">THÔNG TIN ĐƠN HÀNG</h3>
            <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y - H:i', strtotime($order['NgayDat'])); ?></p>
            <p><strong>Ghi chú:</strong> <?php echo $order['GhiChu'] ? $order['GhiChu'] : 'Không có'; ?></p>
            <form action="/Web_DoHocTap/admin/controller/AdminDonhangController.php?action=update_status" method="POST" style="margin-top: 15px;">
                <input type="hidden" name="maDH" value="<?php echo $id; ?>">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                    <label><strong>Cập nhật trạng thái:</strong></label>
                    <select name="status" class="filter-select" style="width: auto;">
                        <option value="Chờ xử lý" <?php if ($order['TrangThai'] == 'Chờ xử lý') echo 'selected'; ?>>Chờ xử lý</option>
                        <option value="Đang giao" <?php if ($order['TrangThai'] == 'Đang giao') echo 'selected'; ?>>Đang giao</option>
                        <option value="Hoàn thành" <?php if ($order['TrangThai'] == 'Hoàn thành') echo 'selected'; ?>>Hoàn thành</option>
                        <option value="Đã hủy" <?php if ($order['TrangThai'] == 'Đã hủy') echo 'selected'; ?>>Đã hủy</option>
                    </select>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="submit" class="btn-save" style="padding: 10px 30px;">Lưu</button>
                    <button type="button" onclick="window.print()" class="btn-print">
                        🖨️ In hóa đơn
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="table-container" style="margin-top: 20px;">
        <h3 style="margin-bottom: 15px;">DANH SÁCH SẢN PHẨM</h3>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th style="text-align: center;">Số lượng</th>
                    <th style="text-align: right;">Đơn giá</th>
                    <th style="text-align: right;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="/Web_DoHocTap/assets/images/Sanpham/<?php echo $item['HinhAnh']; ?>" width="50" style="border-radius: 5px;" onerror="this.src='/Web_DoHocTap/assets/images/error.jpg'">
                                <span><?php echo htmlspecialchars($item['TenSP']); ?></span>
                            </div>
                        </td>
                        <td style="text-align: center;"><?php echo $item['SoLuong']; ?></td>
                        <td style="text-align: right;">
                            <?php
                            $gia = $item['DonGia'] ?? 0;
                            echo number_format((float)$gia, 0, ',', '.');
                            ?>đ
                        </td>
                        <td style="text-align: right; font-weight: bold;">
                            <?php echo number_format((float)$item['SoLuong'] * (float)$gia, 0, ',', '.'); ?>đ
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; padding-top: 20px;"><strong>Tạm tính:</strong></td>
                    <td style="text-align: right; padding-top: 20px;"><?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">
                        <h3 style="color: var(--danger-color);">Tổng cộng:</h3>
                    </td>
                    <td style="text-align: right;">
                        <h3 style="color: var(--danger-color);"><?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ</h3>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php include_once '../../includes/footer.php'; ?>