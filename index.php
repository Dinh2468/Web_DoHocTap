<?php
session_start();
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
$ds_sanpham_hot = $spModel->getHotProducts(8);

// Nạp Header
include_once 'Views/includes/header.php';
?>

<section class="banner">
    <div class="container banner-content">
        <img src="/Web_DoHocTap/assets/images/Home/anhtrai.png" alt="Icon Left" class="banner-img-side">

        <div class="banner-text">
            <h2>DỤNG CỤ HỌC TẬP CHÍNH HÃNG</h2>


        </div>

        <img src="/Web_DoHocTap/assets/images/Home/anhphai.png" alt="Icon Right" class="banner-img-side">
    </div>
</section>

<div class="container section-wrapper">
    <div class="section-header">
        <h3 class="title-left" style="color: #2E7D32;">SẢN PHẨM MỚI</h3>
        <a href="Views/Sanpham/sanpham.php" class="view-more-link">Xem tất cả »</a>
    </div>
    <div class="product-grid">
        <?php
        // Lấy 4 hoặc 8 sản phẩm mới nhất
        $sp_moi = array_slice($ds_sanpham, 0, 4);
        foreach ($sp_moi as $sp):
            include 'Views/Sanpham/the_sanpham.php';
        endforeach;
        ?>
    </div>
</div>

<div class="container section-wrapper">
    <div class="section-header">
        <h3 class="title-left" style="color: #d32f2f;">SẢN PHẨM HOT 🔥</h3>
        <a href="Views/Sanpham/sanpham.php" class="view-more-link">Xem thêm »</a>
    </div>
    <div class="slider-outer">
        <button class="nav-arrow prev" onclick="moveSlider('sliderHot', -1)">❮</button>
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
        <button class="nav-arrow next" onclick="moveSlider('sliderHot', 1)">❯</button>
    </div>
</div>

<div class="container section-wrapper">
    <div class="section-header">
        <h3 class="title-left" style="color: #e65100;">SẢN PHẨM KHUYẾN MÃI</h3>
        <a href="Views/Sanpham/sanpham.php" class="view-more-link">Xem thêm các sản phẩm khác »</a>
    </div>
    <div class="slider-outer">
        <button class="nav-arrow prev" onclick="moveSlider('sliderGiaTot', -1)">❮</button>
        <div id="sliderGiaTot" class="product-slider">
            <?php foreach ($ds_sanpham as $sp): ?>
                <div class="slider-item">
                    <div class="discount-badge">-20%</div>
                    <?php include 'Views/Sanpham/the_sanpham.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="nav-arrow next" onclick="moveSlider('sliderGiaTot', 1)">❯</button>
    </div>
</div>
<script>
    function moveSlider(sliderId, direction) {
        const slider = document.getElementById(sliderId);
        const scrollAmount = slider.offsetWidth; // Cuộn đúng một khung hình (4 sản phẩm)
        slider.scrollLeft += direction * scrollAmount;
    }

    // Hàm tạo thông báo nổi ở góc màn hình
    function showToast(message) {
        // Tạo container nếu chưa có
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            document.body.appendChild(container);
        }

        // Tạo nội dung thông báo
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `✓ ${message}`;

        container.appendChild(toast);

        // Tự động xóa thông báo sau 3 giây
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = '0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Gán sự kiện cho các nút MUA nhanh
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
                        // 1. Hiện thông báo Toast
                        const productName = this.closest('.product-card').querySelector('.product-name').innerText;
                        showToast(`Đã thêm "${productName}" vào giỏ hàng!`);

                        // 2. CẬP NHẬT SỐ LƯỢNG NGAY LẬP TỨC (Không cần load lại trang)
                        let badge = document.querySelector('.cart-badge');
                        if (!badge) {
                            // Nếu chưa có badge (giỏ hàng đang trống), tạo mới và gắn vào icon
                            const cartLink = document.querySelector('a[href*="giohang.php"]');
                            badge = document.createElement('span');
                            badge.className = 'cart-badge';
                            badge.innerText = '0';
                            cartLink.appendChild(badge);
                        }

                        // Lấy số lượng hiện tại và cộng thêm số lượng vừa mua
                        const currentCount = parseInt(badge.innerText);
                        const addedCount = parseInt(this.querySelector('input[name="sl"]').value) || 1;
                        badge.innerText = currentCount + addedCount;
                    }
                });
        };
    });
</script>
<?php
// Nạp Footer
include_once 'Views/includes/footer.php';
?>