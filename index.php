<?php
session_start();
require_once 'classes/Sanpham.class.php';
require_once 'classes/Thuonghieu.class.php';
require_once 'classes/Danhgia.class.php';
$spModel = new Sanpham();
$thModel = new Thuonghieu();
$dgModel = new Danhgia();
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $ds_sanpham = $spModel->search($_GET['search']);
    $title = "Kết quả tìm kiếm cho: " . htmlspecialchars($_GET['search']);
} else {
    $ds_sanpham = $spModel->getAll();
    $title = "Sản phẩm mới";
}
$ds_thuonghieu = $thModel->lay_tat_ca();
$ds_sanpham_hot = $spModel->getHotProducts(8);
include_once 'Views/includes/header.php';
?>
<?php if (!isset($_GET['search']) || empty($_GET['search'])): ?>
    <section class="banner">
        <div class="container banner-content">
            <img src="/Web_DoHocTap/assets/images/Home/anhtrai.png" alt="Icon Left" class="banner-img-side">
            <div class="banner-text">
                <h2>DỤNG CỤ HỌC TẬP CHÍNH HÃNG</h2>
            </div>
            <img src="/Web_DoHocTap/assets/images/Home/anhphai.png" alt="Icon Right" class="banner-img-side">
        </div>
    </section>
<?php endif; ?>
<div class="container section-wrapper">
    <div class="section-header">
        <h3 class="title-left" style="color: #2E7D32;"><?php echo mb_strtoupper($title); ?></h3>
        <?php if (!isset($_GET['search'])): ?>
            <a href="Views/Sanpham/sanpham.php" class="view-more-link">Xem tất cả »</a>
        <?php endif; ?>
    </div>
    <?php if (empty($ds_sanpham)): ?>
        <div style="text-align: center; padding: 50px 0;">
            <p style="font-size: 18px; color: #666;">Không tìm thấy sản phẩm nào phù hợp với từ khóa của bạn.</p>
            <a href="index.php" style="color: var(--primary-color); font-weight: bold;">Quay lại trang chủ</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php
            $hien_thi = isset($_GET['search']) ? $ds_sanpham : array_slice($ds_sanpham, 0, 4);
            foreach ($hien_thi as $sp):
                include 'Views/Sanpham/the_sanpham.php';
            endforeach;
            ?>
        </div>
    <?php endif; ?>
</div>
<?php if (!isset($_GET['search']) || empty($_GET['search'])): ?>
    <div class="container section-wrapper">
        <div class="section-header">
            <h3 class="title-left" style="color: #d32f2f;">SẢN PHẨM HOT 🔥</h3>
            <a href="Views/Sanpham/sanpham.php" class="view-more-link">Xem thêm »</a>
        </div>
        <div class="slider-outer">

            <div id="sliderHot" class="product-slider">
                <?php if ($ds_sanpham_hot): ?>
                    <?php foreach ($ds_sanpham_hot as $sp): ?>
                        <div class="slider-item">
                            <?php include 'Views/Sanpham/the_sanpham.php'; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Đang cập nhật sản phẩm nổi bật...</p>
                <?php endif; ?>
            </div>
            <button class="nav-arrow prev" onclick="moveSlider('sliderHot', -1)">❮</button>
            <button class="nav-arrow next" onclick="moveSlider('sliderHot', 1)">❯</button>
        </div>
    </div>
<?php endif; ?>
<!-- <div class="container section-wrapper">
    <div class="section-header">
        <h3 class="title-left" style="color: #e65100;">SẢN PHẨM KHUYẾN MÃI</h3>
        <a href="Views/Sanpham/sanpham.php" class="view-more-link">Xem thêm các sản phẩm khác »</a>
    </div>
    <div class="slider-outer">
        <button class="nav-arrow prev" onclick="moveSlider('sliderGiaTot', -1)">❮</button>
        <div id="sliderGiaTot" class="product-slider">
            <?php foreach ($ds_sanpham as $sp): ?>
                <div class="slider-item">
                    <?php include 'Views/Sanpham/the_sanpham.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="nav-arrow next" onclick="moveSlider('sliderGiaTot', 1)">❯</button>
    </div>
</div> -->
<script>
    function moveSlider(sliderId, direction) {
        const slider = document.getElementById(sliderId);
        const scrollAmount = slider.offsetWidth;
        slider.scrollLeft += direction * scrollAmount;
    }

    function showToast(message) {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            document.body.appendChild(container);
        }
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `✓ ${message}`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = '0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    document.querySelectorAll('.add-to-cart-quick').forEach(form => {
        form.onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "Thành công") {
                        const productName = this.closest('.product-card').querySelector('.product-name').innerText;
                        showToast(`Đã thêm "${productName}" vào giỏ hàng!`);
                        let badge = document.querySelector('.cart-badge');
                        if (!badge) {
                            const cartLink = document.querySelector('a[href*="giohang.php"]');
                            badge = document.createElement('span');
                            badge.className = 'cart-badge';
                            badge.innerText = '0';
                            cartLink.appendChild(badge);
                        }
                        const currentCount = parseInt(badge.innerText);
                        const addedCount = parseInt(this.querySelector('input[name="sl"]').value) || 1;
                        badge.innerText = currentCount + addedCount;
                    }
                });
        };
    });
</script>
<?php
include_once 'Views/includes/footer.php';
?>