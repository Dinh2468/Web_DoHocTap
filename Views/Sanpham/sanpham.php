<?php
require_once '../../classes/Sanpham.class.php';
require_once '../../classes/Loaisanpham.class.php';

$spModel = new Sanpham();
$loaiModel = new Loaisanpham();

// Lấy danh sách loại sản phẩm cho menu bên trái
$ds_loai = $loaiModel->lay_tat_ca();

// Xử lý lọc theo loại nếu có
$maLoai = isset($_GET['loai']) ? $_GET['loai'] : null;
if ($maLoai) {
    $ds_sanpham = $spModel->getByCategory($maLoai);
    $title = "Sản phẩm theo loại";
} else {
    $ds_sanpham = $spModel->getAll();
    $title = "Tất cả sản phẩm";
}

include_once '../../Views/includes/header.php';
?>

<style>
    .product-page-container {
        display: flex;
        gap: 30px;
        margin-top: 30px;
    }

    /* Cột danh mục bên trái */
    .sidebar {
        width: 25%;
        border-right: 1px solid #ddd;
        padding-right: 20px;
    }

    .sidebar h3 {
        font-size: 20px;
        margin-bottom: 20px;
        color: var(--primary-color);
    }

    .category-list {
        list-style: none;
    }

    .category-list li {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .category-list li a {
        color: #333;
        transition: 0.3s;
    }

    .category-list li a:hover {
        color: var(--accent-color);
        padding-left: 5px;
    }

    /* Cột sản phẩm bên phải */
    .main-products {
        width: 75%;
    }

    .main-products h2 {
        margin-bottom: 20px;
        font-size: 24px;
    }

    /* Grid sản phẩm 3 cột cho trang này */
    .product-list-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    /* Phân trang */
    .pagination {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 30px;
        margin-bottom: 50px;
    }

    .page-item {
        padding: 8px 15px;
        border: 1px solid #ddd;
        background: #f9f9f9;
        color: #333;
        border-radius: 4px;
    }

    .page-item.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
</style>

<div class="container">
    <div class="product-page-container">

        <aside class="sidebar">
            <h3>Danh mục sản phẩm</h3>
            <ul class="category-list">
                <li><a href="sanpham.php">Tất cả sản phẩm</a></li>
                <?php foreach ($ds_loai as $loai): ?>
                    <li>
                        <a href="sanpham.php?loai=<?php echo $loai['MaLoai']; ?>">
                            <?php echo $loai['TenLoai']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <main class="main-products">
            <h2><?php echo $title; ?></h2>

            <div class="product-list-grid">
                <?php if ($ds_sanpham): ?>
                    <?php foreach ($ds_sanpham as $sp): ?>
                        <div class="product-card">
                            <a href="/Web_DoHocTap/Views/Sanpham/chitiet.php?id=<?php echo $sp['MaSP']; ?>" style="text-decoration: none; color: inherit;">
                                <img src="../../assets/images/Sanpham/<?php echo $sp['HinhAnh']; ?>"
                                    class="product-img">
                                <!-- onerror="this.src='https://via.placeholder.com/200x200?text=San+Pham'"> -->
                                <div class="product-name"><?php echo $sp['TenSP']; ?></div>
                                <div class="product-price"><?php echo number_format($sp['Gia'], 0, ',', '.'); ?>đ</div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không có sản phẩm nào trong mục này.</p>
                <?php endif; ?>
            </div>

            <div class="pagination">
                <a href="#" class="page-item active">1</a>
                <a href="#" class="page-item">2</a>
                <a href="#" class="page-item">></a>
            </div>
        </main>

    </div>
</div>

<?php include_once '../../Views/includes/footer.php'; ?>