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

$errorCount = 0;
if (!empty($ds_sanpham)) {
    foreach ($ds_sanpham as $item) {

        $checkSp = $spModel->query("SELECT SoLuongTon FROM sanpham WHERE MaSP = ?", [$item['MaSP']])->fetch();
        if ($item['SoLuong'] > $checkSp['SoLuongTon']) {
            $errorCount++;

            $ctghModel->cap_nhat_so_luong($gioHang['MaGH'], $item['MaSP'], $checkSp['SoLuongTon']);
        }
    }
}

if ($errorCount > 0) {
    $ghModel->tinh_lai_tong_tien($gioHang['MaGH']);
    header("Location: ../giohang.php?error=out_of_stock");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Taikhoan/login.php");
    exit();
}
if (isset($_SESSION['user_id'])) {
    $userInfo = $spModel->query("SELECT * FROM khachhang WHERE MaKH = ?", [$_SESSION['user_id']])->fetch();
}
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

        <div style="margin: 25px 0; border-top: 1px solid #e1e1e1; padding-top: 25px;">
            <div class="section-title" style="font-size: 15px; color: #2E7D32; font-weight: bold;">Chọn ưu đãi dành cho bạn</div>
            <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                <?php
                // Lấy các chương trình khuyến mãi còn hạn
                $today = date('Y-m-d');
                $sqlKM = "SELECT * FROM khuyenmai WHERE NgayKetThuc >= ? AND NgayBatDau <= ?";
                $listKM = $spModel->query($sqlKM, [$today, $today])->fetchAll();

                if ($listKM):
                    foreach ($listKM as $km):
                        // Giả sử DieuKienApDung lưu số tiền tối thiểu (ví dụ: 200000)
                        $minAmount = (int)$km['DieuKienApDung'];
                        $isEligible = ($tongTien >= $minAmount); // Kiểm tra điều kiện
                ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border: 1px dashed <?php echo $isEligible ? '#4CAF50' : '#ccc'; ?>; background: <?php echo $isEligible ? '#f1f8e9' : '#f5f5f5'; ?>; border-radius: 5px; opacity: <?php echo $isEligible ? '1' : '0.6'; ?>;">
                            <div>
                                <strong style="color: #2E7D32;"><?php echo $km['TenKM']; ?></strong>
                                <br><small style="color: #666; font-size: 11px;">Đơn tối thiểu: <?php echo number_format($minAmount, 0, ',', '.'); ?>đ</small>
                            </div>
                            <?php if ($isEligible): ?>
                                <button type="button"
                                    onclick="applyDiscount('<?php echo $km['TenKM']; ?>', <?php echo $km['PhanTramGiam']; ?>)"
                                    style="background: #4CAF50; color: white; border: none; padding: 5px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold;">
                                    Áp dụng
                                </button>
                            <?php else: ?>
                                <span style="color: #d32f2f; font-size: 11px; font-weight: bold;">Chưa đủ điều kiện</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <p style="font-size: 12px; color: #999; font-style: italic;">Hiện không có chương trình khuyến mãi nào.</p>
                <?php endif; ?>
            </div>

            <input type="hidden" id="selected_coupon_name" name="tenKM" value="">
            <input type="hidden" id="final_total_input" name="final_total" value="<?php echo $tongTien; ?>">
        </div>

        <!-- <div class="summary-line">
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
<script>
    let originalTotal = <?php echo $tongTien; ?>;

    function applyDiscount(tenKM, phanTram) {
        let discountAmount = originalTotal * (phanTram / 100);
        let finalTotal = originalTotal - discountAmount;

        const totalDisplay = document.querySelector('.total-price span:last-child');
        totalDisplay.innerHTML = `
        <div style="font-size: 13px; color: #d32f2f; font-weight: normal; margin-bottom: 5px;">
            Giảm giá (${tenKM}): -${new Intl.NumberFormat('vi-VN').format(discountAmount)}đ
        </div>
        <div style="font-size: 14px; color: #666; font-weight: normal; text-decoration: line-through;">
            ${new Intl.NumberFormat('vi-VN').format(originalTotal)}đ
        </div>
        <div style="font-size: 24px; color: #2E7D32;">
            <small style="font-size: 12px; font-weight: normal;">VND</small> 
            ${new Intl.NumberFormat('vi-VN').format(finalTotal)}đ
        </div>
    `;

        document.getElementById('selected_coupon_name').value = tenKM;
        document.getElementById('final_total_input').value = finalTotal;
    }
</script>
<?php include_once '../includes/footer.php'; ?>