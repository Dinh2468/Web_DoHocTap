<?php
// Views/giohang.php
session_start();
require_once '../classes/Sanpham.class.php';
require_once '../classes/Giohang.class.php';
require_once '../classes/Chitiet_Giohang.class.php';

$ghModel = new Giohang();
$ctghModel = new Chitiet_Giohang();
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
// Tìm dòng 35 (sau khi đã có $ds_sanpham) và chèn đoạn này:
$canCheckout = true;
foreach ($ds_sanpham as $item) {
    // Nếu có bất kỳ sản phẩm nào vượt quá tồn kho, khóa nút thanh toán
    if (isset($item['SoLuongTon']) && $item['SoLuong'] > $item['SoLuongTon']) {
        $canCheckout = false;
        break;
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

    .qty-btn {
        padding: 5px 10px;
        background: #eee;
        border: 1px solid #ddd;
        cursor: pointer;
    }
</style>

<div class="container cart-container">
    <h2 class="section-title">Giỏ hàng của bạn</h2>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'out_of_stock'): ?>
        <div style="background: #FFEBEE; color: #C62828; padding: 20px; border: 2px solid #ef9a9a; border-radius: 8px; margin-bottom: 25px; font-weight: bold; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 24px;">⚠️</span>
            <div>
                Rất tiếc! Một số sản phẩm trong giỏ hàng đã hết hoặc không đủ số lượng thực tế trong kho.
                <br>Chúng tôi đã tự động điều chỉnh về mức tối đa hiện có. Vui lòng kiểm tra lại giỏ hàng trước khi thanh toán lại!
            </div>
        </div>
    <?php endif; ?>
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
                    <?php foreach ($ds_sanpham as $item):
                        // Kiểm tra xem sản phẩm này có vượt quá tồn kho không
                        $isOverstock = (isset($item['SoLuongTon']) && $item['SoLuong'] > $item['SoLuongTon']);
                    ?>
                        <tr style="<?php echo $isOverstock ? 'background-color: #FFF9F9; border-left: 4px solid #d32f2f;' : ''; ?>">
                            <td>
                                <strong><?php echo $item['TenSP']; ?></strong>
                                <?php if ($isOverstock): ?>
                                    <div style="color: #d32f2f; font-size: 12px; font-weight: bold; margin-top: 5px;">
                                        ⚠️ Số lượng yêu cầu vượt quá kho!
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><img src="/Web_DoHocTap/assets/images/Sanpham/<?php echo $item['HinhAnh']; ?>" class="cart-img"></td>
                            <td><?php echo number_format($item['DonGia'], 0, ',', '.'); ?>đ</td>
                            <td>
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 5px;">
                                    <div style="display: flex; justify-content: center; align-items: center; gap: 5px;">
                                        <button type="button" class="qty-btn" onclick="this.parentNode.querySelector('input').stepDown()">-</button>

                                        <input type="number"
                                            name="sl[<?php echo $item['MaSP']; ?>]"
                                            value="<?php echo $item['SoLuong']; ?>"
                                            min="1"
                                            max="<?php echo $item['SoLuongTon']; ?>"
                                            class="quantity-input"
                                            style="width: 50px; <?php echo $isOverstock ? 'border-color: #d32f2f; background: #FFF0F0;' : ''; ?>">

                                        <button type="button" class="qty-btn" onclick="this.parentNode.querySelector('input').stepUp()">+</button>
                                    </div>
                                    <small style="color: <?php echo $isOverstock ? '#d32f2f' : '#666'; ?>; font-size: 11px; font-weight: <?php echo $isOverstock ? 'bold' : 'normal'; ?>;">
                                        (Kho còn: <?php echo $item['SoLuongTon']; ?>)
                                    </small>
                                </div>
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

                <div style="display: flex; justify-content: flex-end; align-items: center; gap: 15px;">
                    <a href="../index.php" style="color: #666; text-decoration: none;">← Tiếp tục mua sắm</a>

                    <button type="submit" name="action" value="update" class="btn-checkout" style="border: none; cursor: pointer; background: #4CAF50;">
                        CẬP NHẬT GIỎ HÀNG
                    </button>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($canCheckout): ?>
                            <a href="Thanhtoan/thanhtoan.php" class="btn-checkout">TIẾN HÀNH THANH TOÁN</a>
                        <?php else: ?>
                            <button type="button" class="btn-checkout" style="background: #ccc; cursor: not-allowed;" onclick="alert('Vui lòng cập nhật lại số lượng khớp với tồn kho trước khi thanh toán!')">
                                TIẾN HÀNH THANH TOÁN
                            </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="Taikhoan/login.php?redirect=giohang" class="btn-checkout" style="background: #f57c00;">
                            ĐĂNG NHẬP ĐỂ THANH TOÁN
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div style="text-align: center; padding: 50px;">
            <p style="font-size: 18px; color: #666;">Giỏ hàng của bạn đang trống.</p>
            <a href="../index.php" class="btn-muangay" style="margin-top: 20px;">QUAY LẠI CỬA HÀNG</a>
        </div>
    <?php endif; ?>
</div>
<script>
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', function() {
            const max = parseInt(this.getAttribute('max'));
            const val = parseInt(this.value);
            const parentRow = this.closest('tr');
            const stockInfo = parentRow.querySelector('small');

            if (val > max) {
                // Đổi sang màu cảnh báo lỗi
                this.style.borderColor = '#d32f2f';
                this.style.backgroundColor = '#FFF0F0';
                parentRow.style.backgroundColor = '#FFF9F9';
                stockInfo.style.color = '#d32f2f';
                stockInfo.style.fontWeight = 'bold';
            } else {
                // Trả về màu bình thường
                this.style.borderColor = '#ddd';
                this.style.backgroundColor = '#fff';
                parentRow.style.backgroundColor = 'transparent';
                stockInfo.style.color = '#666';
                stockInfo.style.fontWeight = 'normal';
            }
        });
    });
</script>
<?php include_once 'includes/footer.php'; ?>