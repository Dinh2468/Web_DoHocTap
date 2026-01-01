<?php
// Views/thanhcong.php
session_start();
require_once '../classes/DB.class.php';
$db = new Db();

$maDH = isset($_GET['madh']) ? $_GET['madh'] : null;

if (!$maDH) {
    header("Location: ../index.php");
    exit();
}

// Lấy thông tin đơn hàng vừa đặt
$donHang = $db->query("SELECT * FROM donhang WHERE MaDH = ?", [$maDH])->fetch();
$sqlCT = "SELECT ct.*, s.TenSP, s.HinhAnh 
          FROM chitietdh ct 
          JOIN sanpham s ON ct.MaSP = s.MaSP 
          WHERE ct.MaDH = ?";
$ds_sanpham_mua = $db->query($sqlCT, [$maDH])->fetchAll();
include_once 'includes/header.php';
?>

<div class="container" style="margin: 50px auto; max-width: 800px; text-align: center;">
    <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div style="color: #2E7D32; font-size: 60px; margin-bottom: 20px;">✔</div>
        <h2 style="color: #333; margin-bottom: 10px;">ĐẶT HÀNG THÀNH CÔNG!</h2>
        <p style="color: #666; margin-bottom: 30px;">Cảm ơn bạn đã tin tưởng. Mã đơn hàng của bạn là: <strong>#<?php echo $maDH; ?></strong></p>

        <div style="text-align: left; background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h3 style="font-size: 18px; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 15px;">Thông tin giao hàng</h3>
            <p><strong>Người nhận:</strong> <?php echo htmlspecialchars($donHang['HoTenNguoiNhan']); ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($donHang['SDTNguoiNhan']); ?></p>
            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($donHang['DiaChiGiaoHang']); ?></p>
        </div>

        <div style="text-align: left; background: #fff; border: 1px solid #eee; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h3 style="font-size: 18px; margin-bottom: 15px;">Chi tiết sản phẩm</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <?php foreach ($ds_sanpham_mua as $sp): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px 0; width: 60px;">
                            <img src="/Web_DoHocTap/assets/images/Sanpham/<?php echo $sp['HinhAnh']; ?>" style="width: 50px; height: 50px; object-fit: contain;">
                        </td>
                        <td style="padding: 10px; font-size: 14px;">
                            <strong><?php echo $sp['TenSP']; ?></strong> x <?php echo $sp['SoLuong']; ?>
                        </td>
                        <td style="padding: 10px; text-align: right; color: #d32f2f;">
                            <?php echo number_format($sp['DonGia'] * $sp['SoLuong'], 0, ',', '.'); ?>đ
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div style="text-align: right; margin-top: 15px; font-size: 18px; color: #d32f2f;">
                <strong>Tổng thanh toán: <?php echo number_format($donHang['TongTien'], 0, ',', '.'); ?> VNĐ</strong>
            </div>
        </div>

        <div style="display: flex; justify-content: center;">
            <a href="../index.php" style="background: #4CAF50; text-decoration: none; color: white; padding: 12px 40px; border-radius: 5px; font-weight: bold;">TIẾP TỤC MUA SẮM</a>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>