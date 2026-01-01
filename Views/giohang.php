<?php
// Views/giohang.php
session_start();
require_once '../classes/Sanpham.class.php';
require_once '../classes/Giohang.class.php';
require_once '../classes/Chitiet_Giohang.class.php';

$spModel = new Sanpham();
$ds_sanpham = [];
$tongTien = 0;

if (isset($_SESSION['user_id'])) {
    // Lấy từ Database như cũ
    $maKH = $_SESSION['user_id'];
    $gioHang = $ghModel->lay_theo_khach_hang($maKH);
    if ($gioHang) {
        $ds_sanpham = $ctghModel->lay_danh_sach_trong_gio($gioHang['MaGH']);
        $tongTien = $gioHang['TongTien'];
    }
} else {
    // Lấy từ SESSION cho khách vãng lai
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $maSP => $sl) {
            $sp = $spModel->getById($maSP);
            if ($sp) {
                $thanhTien = $sp['Gia'] * $sl;
                $tongTien += $thanhTien;
                $ds_sanpham[] = [
                    'MaSP' => $sp['MaSP'],
                    'TenSP' => $sp['TenSP'],
                    'HinhAnh' => $sp['HinhAnh'],
                    'DonGia' => $sp['Gia'],
                    'SoLuong' => $sl
                ];
            }
        }
    }
}
include_once 'includes/header.php';
?>

<style>
    .cart-container {
        margin: 40px auto;
        max-width: 1100px;
        background: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .cart-table {
        width: 100%;
        margin: 0 auto;
        border-collapse: collapse;
    }

    .cart-table th,
    .cart-table td {
        padding: 15px;
        text-align: center;
        /* Căn giữa văn bản */
        border-bottom: 1px solid #eee;
    }

    .cart-table td:first-child,
    .cart-table th:first-child {
        text-align: left;
    }

    .cart-table th {
        background: #2E7D32;
        color: white;
        padding: 15px;
        text-align: left;
    }

    .cart-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    .cart-img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        background: #f9f9f9;
    }

    .quantity-input {
        width: 70px;
        padding: 8px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .btn-xoa {
        color: #d32f2f;
        text-decoration: none;
        font-weight: bold;
    }

    .cart-summary {
        margin-top: 30px;
        background: #f9f9f9;
        padding: 20px;
        text-align: right;
        border: 1px solid #eee;
    }

    .total-price {
        font-size: 24px;
        color: #d32f2f;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .btn-checkout {
        background: #2E7D32;
        color: white;
        padding: 15px 40px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        display: inline-block;
    }
</style>

<div class="container cart-container">
    <h2 class="section-title">Giỏ hàng của bạn</h2>

    <?php if (!empty($ds_sanpham)): ?>
        <form action="../controller/GiohangController.php" method="POST">
            <input type="hidden" name="action" value="update">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ds_sanpham as $item): ?>
                        <tr>
                            <td><strong><?php echo $item['TenSP']; ?></strong></td>
                            <td><img src="/Web_DoHocTap/assets/images/Sanpham/<?php echo $item['HinhAnh']; ?>" class="cart-img"></td>
                            <td><?php echo number_format($item['DonGia'], 0, ',', '.'); ?>đ</td>

                            <td>
                                <input type="number"
                                    name="sl[<?php echo $item['MaSP']; ?>]"
                                    value="<?php echo $item['SoLuong']; ?>"
                                    min="1"
                                    class="quantity-input">
                            </td>

                            <td><strong><?php echo number_format($item['DonGia'] * $item['SoLuong'], 0, ',', '.'); ?>đ</strong></td>
                            <td>
                                <a href="../controller/GiohangController.php?action=xoa&idsp=<?php echo $item['MaSP']; ?>" class="btn-xoa" onclick="return confirm('Bạn muốn bỏ sản phẩm này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <div class="total-price">Tổng cộng: <?php echo number_format($tongTien, 0, ',', '.'); ?> VNĐ</div>
                <a href="../index.php" style="margin-right: 20px; color: #666; text-decoration: none;">← Tiếp tục mua sắm</a>
                <button type="submit" class="btn-checkout" style="border: none; cursor: pointer; margin-right: 10px;">CẬP NHẬT GIỎ HÀNG</button>
                <a href="thanhtoan.php" class="btn-checkout">TIẾN HÀNH THANH TOÁN</a>
            </div>
        </form>
    <?php else: ?>
        <div style="text-align: center; padding: 50px;">
            <p style="font-size: 18px; color: #666;">Giỏ hàng của bạn đang trống.</p>
            <a href="../index.php" class="btn-muangay" style="margin-top: 20px;">QUAY LẠI CỬA HÀNG</a>
        </div>
    <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>