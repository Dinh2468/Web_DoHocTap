<?php
session_start();
require_once '../../classes/Sanpham.class.php';
require_once '../../classes/Loaisanpham.class.php';


$spModel = new Sanpham();
$loaiModel = new Loaisanpham();

// Lấy danh sách loại sản phẩm cho menu bên trái
$ds_loai = $loaiModel->lay_tat_ca();
$ds_thuonghieu = $spModel->query("SELECT * FROM thuonghieu")->fetchAll();

$limit = 21;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;


$maLoai = isset($_GET['loai']) ? $_GET['loai'] : null;
$priceFilters = isset($_GET['price']) ? $_GET['price'] : [];
$brandFilters = isset($_GET['brand']) ? $_GET['brand'] : [];
// Lấy dữ liệu sản phẩm và tổng số lượng để tính trang
$totalProducts = $spModel->countAll($maLoai, $priceFilters, $brandFilters);
$totalPages = ceil($totalProducts / $limit);

// Kiểm tra nếu có bất kỳ bộ lọc nào được kích hoạt
if ($maLoai || !empty($priceFilters) || !empty($brandFilters)) {
    // SỬA THÀNH: truyền thêm biến offset và limit đã tính ở trên vào
    $ds_sanpham = $spModel->filterAdvanced($maLoai, $priceFilters, $brandFilters, $offset, $limit);
    $title = "Kết quả lọc sản phẩm";
} else {
    // Dòng này bạn đã gọi đúng hàm phân trang rồi
    $ds_sanpham = $spModel->getAll_phantrang($offset, $limit);
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
            <div style="background: #fcfcfc; padding: 20px; border: 1px solid #eee; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                <h3 style="color: var(--primary-color); font-size: 18px; border-bottom: 2px solid var(--accent-color); padding-bottom: 10px; margin-bottom: 15px;">
                    Danh mục sản phẩm
                </h3>
                <ul class="category-list">
                    <li>
                        <a href="sanpham.php" style="<?php echo !isset($_GET['loai']) ? 'color: var(--accent-color); font-weight: bold;' : ''; ?>">
                            Tất cả sản phẩm
                        </a>
                    </li>
                    <?php foreach ($ds_loai as $loai): ?>
                        <li>
                            <a href="sanpham.php?loai=<?php echo $loai['MaLoai']; ?>"
                                style="<?php echo (isset($_GET['loai']) && $_GET['loai'] == $loai['MaLoai']) ? 'color: var(--accent-color); font-weight: bold;' : ''; ?>">
                                <?php echo $loai['TenLoai']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <form action="sanpham.php" method="GET">
                <?php if (isset($_GET['loai'])): ?>
                    <input type="hidden" name="loai" value="<?php echo $_GET['loai']; ?>">
                <?php endif; ?>

                <div style="background: #fcfcfc; padding: 20px; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                    <h3 style="color: var(--primary-color); font-size: 18px; border-bottom: 2px solid var(--accent-color); padding-bottom: 10px; margin-bottom: 15px;">
                        Bộ lọc
                    </h3>

                    <div style="margin-bottom: 25px;">
                        <p style="font-weight: bold; margin-bottom: 12px; font-size: 15px; color: #444;">Khoảng giá</p>
                        <?php
                        $price_ranges = [
                            '0-200000' => 'Dưới 200.000đ',
                            '200000-400000' => '200.000đ - 400.000đ',
                            '400000-600000' => '400.000đ - 600.000đ',
                            '600000-800000' => '600.000đ - 800.000đ',
                            '800000-9999999' => 'Trên 800.000đ'
                        ];
                        foreach ($price_ranges as $val => $label):
                        ?>
                            <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-size: 14px; color: #666; cursor: pointer;">
                                <input type="checkbox" name="price[]" value="<?php echo $val; ?>"
                                    <?php echo (isset($_GET['price']) && in_array($val, $_GET['price'])) ? 'checked' : ''; ?>>
                                <?php echo $label; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="filter-group">
                        <p style="font-weight: bold; margin-bottom: 12px; font-size: 15px; color: #444;">Thương hiệu</p>
                        <div style="display: grid; grid-template-columns: 1fr; gap: 8px;">
                            <?php foreach ($ds_thuonghieu as $th): ?>
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #666; cursor: pointer;">
                                    <input type="checkbox" name="brand[]" value="<?php echo $th['MaTH']; ?>"
                                        <?php echo (isset($_GET['brand']) && in_array($th['MaTH'], $_GET['brand'])) ? 'checked' : ''; ?>>
                                    <?php echo $th['TenTH']; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn-buy-now" style="width: 100%; margin-top: 20px; font-size: 13px; padding: 12px; letter-spacing: 0.5px;">
                        ÁP DỤNG BỘ LỌC
                    </button>
                    <a href="sanpham.php<?php echo isset($_GET['loai']) ? '?loai=' . $_GET['loai'] : ''; ?>"
                        style="display: block; text-align: center; margin-top: 15px; font-size: 13px; color: #999; text-decoration: none;">
                        ✕ Xóa tất cả lọc
                    </a>
                </div>
            </form>
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
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="page-item">
                        < </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"
                                class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="page-item">></a>
                        <?php endif; ?>
            </div>
        </main>

    </div>
</div>

<?php include_once '../../Views/includes/footer.php'; ?>