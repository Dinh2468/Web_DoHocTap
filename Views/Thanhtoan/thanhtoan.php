<?php
session_start();
require_once '../../classes/DB.class.php';
require_once '../../classes/Giohang.class.php';
require_once '../../classes/Chitiet_Giohang.class.php';
require_once '../../classes/Sanpham.class.php';

$ghModel = new Giohang();
$ctghModel = new Chitiet_Giohang();
$spModel = new Sanpham();

$ds_sanpham = [];
$tongTien = 0;
$userInfo = null;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Taikhoan/login.php");
    exit();
}
if (isset($_SESSION['user_id'])) {
    $userInfo = $spModel->query("SELECT * FROM khachhang WHERE MaKH = ?", [$_SESSION['user_id']])->fetch();
}
// Lấy dữ liệu giỏ hàng tương tự file cũ của bạn
if (isset($_SESSION['user_id'])) {
    $gioHang = $ghModel->lay_theo_khach_hang($_SESSION['user_id']);
    if ($gioHang) {
        $ds_sanpham = $ctghModel->lay_danh_sach_trong_gio($gioHang['MaGH']);
        $tongTien = $gioHang['TongTien'];
    }
} else if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $maSP => $sl) {
        $sp = $spModel->getById($maSP);
        if ($sp) {
            $tongTien += $sp['Gia'] * $sl;
            $ds_sanpham[] = ['TenSP' => $sp['TenSP'], 'HinhAnh' => $sp['HinhAnh'], 'DonGia' => $sp['Gia'], 'SoLuong' => $sl];
        }
    }
}

include_once '../includes/header.php';
?>

<style>
    .checkout-container {
        display: flex;
        max-width: 1100px;
        margin: 40px auto;
        gap: 40px;
        font-family: sans-serif;
    }

    .checkout-left {
        flex: 1.5;
    }

    .checkout-right {
        flex: 1;
        background: #fafafa;
        padding: 25px;
        border-left: 1px solid #e1e1e1;
        min-height: 80vh;
    }

    .shop-name {
        font-size: 22px;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .breadcrumb {
        font-size: 13px;
        color: #338dbc;
        margin-bottom: 25px;
    }

    .section-title {
        font-size: 18px;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .form-group {
        margin-bottom: 12px;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #d9d9d9;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-row {
        display: flex;
        gap: 10px;
    }

    .product-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .product-img-wrapper {
        position: relative;
        width: 60px;
        height: 60px;
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        background: #fff;
    }

    .product-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 5px;
    }

    .product-qty {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #8f8f8f;
        color: #fff;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        color: #717171;
        font-size: 14px;
    }

    .total-price {
        font-size: 20px;
        color: #333;
        font-weight: bold;
        border-top: 1px solid #e1e1e1;
        padding-top: 15px;
        margin-top: 15px;
    }

    .btn-submit {
        background: #338dbc;
        color: white;
        border: none;
        padding: 15px;
        width: 100%;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
    }
</style>

<div class="checkout-container">
    <div class="checkout-left">
        <div class="shop-name">THIÊN ĐƯỜNG DỤNG CỤ HỌC TẬP</div>
        <!-- <div class="breadcrumb">Giỏ hàng > Thông tin giao hàng > Phương thức thanh toán</div> -->

        <div class="section-title">Thông tin giao hàng</div>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div style="font-size: 14px; margin-bottom: 15px;">Bạn đã có tài khoản? <a href="Taikhoan/login.php" style="color: #338dbc;">Đăng nhập</a></div>
        <?php endif; ?>

        <form action="../../controller/ThanhtoanController.php" method="POST">
            <div class="form-group">
                <input type="text" name="hoTen" class="form-control" placeholder="Họ và tên"
                    value="<?php echo htmlspecialchars($userInfo['HoTen'] ?? ''); ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group" style="flex: 2;">
                    <input type="email" name="email" class="form-control" placeholder="Email"
                        value="<?php echo htmlspecialchars($userInfo['Email'] ?? ''); ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <input type="text" name="sdt" class="form-control" placeholder="Số điện thoại"
                        value="<?php echo htmlspecialchars($userInfo['SDT'] ?? ''); ?>" required>
                </div>
            </div>
            <div class="form-group">
                <input type="text" name="diaChi" class="form-control" placeholder="Địa chỉ"
                    value="<?php echo htmlspecialchars($userInfo['DiaChi'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <textarea name="ghiChu" class="form-control" placeholder="Ghi chú (tùy chọn)" style="height: 100px;"></textarea>
            </div>
            <button type="submit" class="btn-submit">THANH TOÁN</button>
        </form>
    </div>

    <div class="checkout-right">
        <?php foreach ($ds_sanpham as $sp): ?>
            <div class="product-item">
                <div class="product-img-wrapper">
                    <img src="../../assets/images/Sanpham/<?php echo $sp['HinhAnh']; ?>">
                    <span class="product-qty"><?php echo $sp['SoLuong']; ?></span>
                </div>
                <div style="flex: 1; font-size: 14px; color: #4b4b4b;"><?php echo $sp['TenSP']; ?></div>
                <div style="font-size: 14px;"><?php echo number_format($sp['DonGia'] * $sp['SoLuong'], 0, ',', '.'); ?>đ</div>
            </div>
        <?php endforeach; ?>

        <!-- <div style="display: flex; gap: 10px; margin: 25px 0; border-top: 1px solid #e1e1e1; padding-top: 25px;">
            <input type="text" class="form-control" placeholder="Mã giảm giá" style="flex: 3;">
            <button style="flex: 1; background: #c8c8c8; color: #fff; border: none; border-radius: 4px; cursor: not-allowed;">Sử dụng</button>
        </div>

        <div class="summary-line">
            <span>Tạm tính</span>
            <span><?php echo number_format($tongTien, 0, ',', '.'); ?>đ</span>
        </div>
        <div class="summary-line">
            <span>Phí vận chuyển</span>
            <span>—</span>
        </div> -->
        <div class="summary-line total-price">
            <span>Tổng cộng</span>
            <span style="font-size: 24px;"><small style="font-size: 12px; font-weight: normal; vertical-align: middle;">VND</small> <?php echo number_format($tongTien, 0, ',', '.'); ?>đ</span>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>