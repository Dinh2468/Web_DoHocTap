<?php
// Views/Sanpham/viet_danhgia.php
session_start();
require_once '../../classes/DB.class.php';
$db = new Db();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Taikhoan/login.php");
    exit();
}
$maSP = isset($_GET['idsp']) ? $_GET['idsp'] : 0;
$maDH = isset($_GET['iddh']) ? $_GET['iddh'] : 0;
$product = $db->query("SELECT TenSP, HinhAnh FROM sanpham WHERE MaSP = ?", [$maSP])->fetch();
if (!$product) {
    echo "Sản phẩm không tồn tại.";
    exit();
}
$donHang = $db->query("SELECT NgayDat FROM donhang WHERE MaDH = ?", [$maDH])->fetch();
if ($donHang) {
    $ngayDat = new DateTime($donHang['NgayDat']);
    $ngayHienTai = new DateTime();
    $soNgay = $ngayHienTai->diff($ngayDat)->days;
    if ($soNgay > 30) {
        echo "<script>alert('Đã quá thời hạn 30 ngày để đánh giá sản phẩm này!'); window.location.href='../Donhang/chitiet_donhang.php?id=$maDH';</script>";
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maKH = $_SESSION['user_id'];
    $sao = $_POST['rating'];
    $noidung = htmlspecialchars($_POST['noidung']);
    $sql = "INSERT INTO danhgia (MaKH, MaSP, MaDH, SoSao, NoiDung, NgayDG) VALUES (?, ?, ?, ?, ?, NOW())";
    $db->query($sql, [$maKH, $maSP, $maDH, $sao, $noidung]);
    header("Location: ../Donhang/chitiet_donhang.php?id=" . $maDH);
    exit();
}
include_once '../includes/header.php';
?>
<style>
    .rating-container {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 10px;
        margin: 20px 0;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 40px;
        color: #ccc;
        cursor: pointer;
        transition: 0.2s;
    }

    .star-rating label:hover,
    .star-rating label:hover~label,
    .star-rating input:checked~label {
        color: #FFD700;
    }

    .review-textarea {
        width: 100%;
        height: 120px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 20px;
        font-family: inherit;
    }

    .btn-submit-review {
        background: #2E7D32;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
    }
</style>
<div class="container">
    <div class="rating-container">
        <h2 style="color: #2E7D32;">Đánh giá sản phẩm</h2>
        <div style="margin: 20px 0;">
            <img src="/Web_DoHocTap/assets/images/Sanpham/<?php echo $product['HinhAnh']; ?>" width="100">
            <p style="font-weight: bold; margin-top: 10px;"><?php echo $product['TenSP']; ?></p>
        </div>
        <form action="" method="POST">
            <input type="hidden" name="maSP" value="<?php echo $maSP; ?>">
            <input type="hidden" name="maDH" value="<?php echo $maDH; ?>">
            <p>Bạn cảm thấy sản phẩm này thế nào?</p>
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5" required /><label for="star5">★</label>
                <input type="radio" id="star4" name="rating" value="4" /><label for="star4">★</label>
                <input type="radio" id="star3" name="rating" value="3" /><label for="star3">★</label>
                <input type="radio" id="star2" name="rating" value="2" /><label for="star2">★</label>
                <input type="radio" id="star1" name="rating" value="1" /><label for="star1">★</label>
            </div>
            <textarea name="noidung" class="review-textarea" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..." required></textarea>
            <button type="submit" class="btn-submit-review">GỬI ĐÁNH GIÁ</button>
            <a href="../Donhang/chitiet_donhang.php?id=<?php echo $maDH; ?>" style="display: block; margin-top: 15px; color: #666; font-size: 14px;">Quay lại</a>
        </form>
    </div>
</div>
<?php include_once '../includes/footer.php'; ?>