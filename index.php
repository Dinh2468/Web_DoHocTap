<?php
// Nạp các lớp cần thiết
require_once 'classes/Sanpham.class.php';
require_once 'classes/Thuonghieu.class.php';
require_once 'classes/Danhgia.class.php';

// Khởi tạo đối tượng
$spModel = new Sanpham();
$thModel = new Thuonghieu();
$dgModel = new Danhgia();

// Xử lý tìm kiếm hoặc lấy toàn bộ
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $ds_sanpham = $spModel->search($_GET['search']);
    $title = "Kết quả tìm kiếm cho: " . htmlspecialchars($_GET['search']);
} else {
    $ds_sanpham = $spModel->getAll();
    $title = "Sản phẩm mới";
}

$ds_thuonghieu = $thModel->lay_tat_ca();

// Nạp Header
include_once 'Views/includes/header.php';
?>
<section class="banner">
    <div class="container banner-content">
        <img src="/Web_DoHocTap/assets/images/Home/anhtrai.png" alt="Icon Left" class="banner-img-side">

        <div class="banner-text">
            <h2>DỤNG CỤ HỌC TẬP CHÍNH HÃNG</h2>
            <p>Giảm giá 10% cho học sinh, sinh viên khi mua combo!</p>

        </div>

        <img src="/Web_DoHocTap/assets/images/Home/anhphai.png" alt="Icon Right" class="banner-img-side">
    </div>
</section>

<div class="container">
    <h3 class="section-title"><?php echo $title; ?></h3>

    <div class="product-grid">

        <?php if ($ds_sanpham): ?>
            <?php foreach ($ds_sanpham as $sp): ?>
                <div class="product-card">
                    <a href="/Web_DoHocTap/Views/Sanpham/chitiet.php?id=<?php echo $sp['MaSP']; ?>" style="text-decoration: none; color: inherit;">
                        <img src="assets/images/Sanpham/<?php echo $sp['HinhAnh']; ?>"
                            class="product-img">


                        <div class="product-name"><?php echo $sp['TenSP']; ?></div>

                        <div style="color: #FFD700; font-size: 12px; margin-bottom: 5px;">
                            <?php
                            $sao = $dgModel->tinh_sao_trung_binh($sp['MaSP']);
                            echo str_repeat('★', floor($sao)) . str_repeat('☆', 5 - floor($sao));
                            ?>
                        </div>

                        <div class="product-price">
                            <?php echo number_format($sp['Gia'], 0, ',', '.'); ?>đ
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="grid-column: span 4; text-align: center;">Không tìm thấy sản phẩm nào.</p>
        <?php endif; ?>
    </div>


    <h3 class="section-title">Thương hiệu đối tác</h3>
    <div class="brands-row">
        <?php foreach ($ds_thuonghieu as $th): ?>
            <div class="brand-box"><?php echo $th['TenTH']; ?></div>
        <?php endforeach; ?>
    </div>
</div>

<?php
// Nạp Footer
include_once 'Views/includes/footer.php';
?>