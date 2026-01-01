<?php
// Views/Sanpham/chitiet.php
session_start();
require_once '../../classes/Sanpham.class.php';
require_once '../../classes/Danhgia.class.php';

$spModel = new Sanpham();
$dgModel = new Danhgia();

// 1. Lấy mã sản phẩm từ URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$sp = $spModel->getById($id); // Bạn cần đảm bảo trong Sanpham.class.php có hàm getById

if (!$sp) {
    echo "Sản phẩm không tồn tại!";
    exit;
}

// 2. Lấy danh sách đánh giá
$ds_danhgia = $dgModel->lay_theo_san_pham($id);
$sao_tb = $dgModel->tinh_sao_trung_binh($id);

include_once '../../Views/includes/header.php';
?>

<style>
    .detail-container {
        display: flex;
        gap: 50px;
        margin-top: 40px;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
    }

    .detail-left {
        flex: 1;
    }

    .detail-left img {
        width: 100%;
        max-height: 500px;
        object-fit: contain;
        border: 1px solid #eee;
    }

    .detail-right {
        flex: 1;
    }

    .detail-right h1 {
        font-size: 28px;
        color: #333;
        margin-bottom: 15px;
    }

    .price-detail {
        font-size: 24px;
        color: #d32f2f;
        font-weight: bold;
        margin: 20px 0;
    }

    .description {
        line-height: 1.8;
        color: #666;
        margin-bottom: 30px;
    }

    .btn-add-cart {
        background: #2E7D32;
        color: white;
        border: none;
        padding: 15px 40px;
        font-size: 18px;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-add-cart:hover {
        background: #1B5E20;
    }

    .review-section {
        margin-top: 50px;
        border-top: 1px solid #eee;
        padding-top: 30px;
    }

    .review-item {
        border-bottom: 1px solid #f5f5f5;
        padding: 15px 0;
    }

    .user-name {
        font-weight: bold;
        color: #2E7D32;
    }

    .rating-stars {
        color: #FFD700;
        margin-bottom: 5px;
    }
</style>

<div class="container">
    <div class="detail-container">
        <div class="detail-left">
            <img src="/Web_DoHocTap/assets/images/Sanpham/<?php echo $sp['HinhAnh']; ?>"
                onerror="this.src='https://via.placeholder.com/500x500?text=No+Image'">
        </div>

        <div class="detail-right">
            <h1><?php echo $sp['TenSP']; ?></h1>
            <div class="rating-stars">
                <?php echo str_repeat('★', floor($sao_tb)) . str_repeat('☆', 5 - floor($sao_tb)); ?>
                (<?php echo $sao_tb; ?>/5)
            </div>

            <div class="price-detail"><?php echo number_format($sp['Gia'], 0, ',', '.'); ?> VNĐ</div>

            <div class="description">
                <strong>Mô tả sản phẩm:</strong><br>
                <?php echo nl2br($sp['MoTa']); // Giả sử bảng sanpham có cột MoTa 
                ?>
            </div>

            <form id="formadd_giohang" action="../../controller/GiohangController.php" method="POST">
                <input type="hidden" name="maSP" value="<?php echo $sp['MaSP']; ?>">
                <input type="hidden" name="ajax" value="1">
                <label>Số lượng: </label>
                <input type="number" name="sl" value="1" min="1" style="width: 60px; padding: 10px; margin-right: 10px;">
                <button type="submit" class="btn-add-cart">THÊM VÀO GIỎ HÀNG</button>
            </form>

            <div id="toast-message" style="display: none; position: fixed; top: 20px; right: 20px; background: #2E7D32; color: white; padding: 15px 25px; border-radius: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10001; font-weight: bold;">
                ✓ Đã thêm vào giỏ hàng thành công!
            </div>
        </div>
    </div>

    <div class="review-section">
        <h3>Khách hàng đánh giá</h3>
        <?php if ($ds_danhgia): ?>
            <?php foreach ($ds_danhgia as $dg): ?>
                <div class="review-item">
                    <div class="user-name"><?php echo $dg['HoTen']; ?></div>
                    <div class="rating-stars"><?php echo str_repeat('★', $dg['SoSao']); ?></div>
                    <p><?php echo $dg['NoiDung']; ?></p>
                    <small style="color: #999;"><?php echo $dg['NgayDG']; ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
        <?php endif; ?>
    </div>
</div>
<script>
    document.getElementById('formadd_giohang').onsubmit = function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const toast = document.getElementById('toast-message');

        fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Kiểm tra dữ liệu trả về từ Controller
                if (data.trim() === "Thành công") {
                    // Hiển thị thông báo ở góc
                    toast.style.display = 'block';
                    // Tự động ẩn sau 3 giây
                    setTimeout(() => {
                        toast.style.display = 'none';
                    }, 3000);
                }
            })
            .catch(error => console.error('Lỗi:', error));
    };
</script>
<?php include_once '../../Views/includes/footer.php'; ?>