<?php
// admin/Views/Sanpham/edit.php
include_once '../../includes/header.php';
$db = new Db();

// 1. Lấy ID sản phẩm từ URL
$id = $_GET['id'] ?? '';
if (empty($id)) {
    header("Location: index.php");
    exit();
}

// 2. Truy vấn thông tin sản phẩm hiện tại
$product = $db->query("SELECT * FROM sanpham WHERE MaSP = ?", [$id])->fetch();
if (!$product) {
    echo "Sản phẩm không tồn tại!";
    exit();
}

// 3. Lấy dữ liệu cho các ô chọn (Select)
$loaiSP = $db->query("SELECT * FROM loaisp")->fetchAll();
$nhaCC = $db->query("SELECT * FROM nhacungcap")->fetchAll();
$thuongHieu = $db->query("SELECT * FROM thuonghieu")->fetchAll();
?>

<div class="main-content-inner">
    <header class="main-header">
        <h2>Chỉnh sửa sản phẩm: #<?php echo $id; ?></h2>
        <a href="index.php" style="color: var(--primary-color); text-decoration: none;">← Quay lại danh sách</a>
    </header>

    <div class="form-container">
        <form action="../../Controller/AdminSanphamController.php?action=edit" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="maSP" value="<?php echo $product['MaSP']; ?>">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="tenSP" class="form-control"
                        value="<?php echo htmlspecialchars($product['TenSP']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Danh mục</label>
                    <select name="maLoai" class="form-control" required>
                        <?php foreach ($loaiSP as $loai): ?>
                            <option value="<?php echo $loai['MaLoai']; ?>"
                                <?php echo ($loai['MaLoai'] == $product['MaLoai']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($loai['TenLoai']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Giá bán (VNĐ)</label>
                    <input type="number" name="gia" class="form-control"
                        value="<?php echo (int)$product['Gia']; ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Số lượng tồn</label>
                    <input type="number" name="soLuong" class="form-control"
                        value="<?php echo $product['SoLuongTon']; ?>" required min="0"
                        oninput="if(this.value < 0) this.value = 0;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Hình ảnh hiện tại</label>
                <div style="margin-bottom: 10px;">
                    <img src="/Web_DoHocTap/assets/images/Sanpham/<?php echo $product['HinhAnh']; ?>"
                        style="width: 100px; border-radius: 8px; border: 1px solid #ddd;"
                        onerror="this.src='/Web_DoHocTap/assets/images/error.jpg'">
                </div>
                <label class="form-label">Thay đổi hình ảnh (Bỏ qua nếu giữ nguyên)</label>
                <input type="file" name="hinhAnh" class="form-control">
                <input type="hidden" name="hinhAnhCu" value="<?php echo $product['HinhAnh']; ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả sản phẩm</label>
                <textarea name="moTa" class="form-control" rows="5"><?php echo htmlspecialchars($product['MoTa']); ?></textarea>
            </div>

            <div style="text-align: right; margin-top: 30px;">
                <button type="submit" class="btn-save">CẬP NHẬT SẢN PHẨM</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>