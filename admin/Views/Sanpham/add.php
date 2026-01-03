<?php
include_once '../../includes/header.php';
$db = new Db();
$loaiSP = $db->query("SELECT * FROM loaisp")->fetchAll();
$nhaCC = $db->query("SELECT * FROM nhacungcap")->fetchAll();
$thuongHieu = $db->query("SELECT * FROM thuonghieu")->fetchAll();
?>

<div class="main-content-inner">
    <header class="main-header">
        <h2>Thêm sản phẩm mới</h2>
        <a href="index.php" style="color: var(--primary-color); text-decoration: none;">← Quay lại</a>
    </header>

    <div class="table-container">
        <form action="../../Controller/AdminSanphamController.php?action=add" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="tenSP" class="form-control" placeholder="Nhập tên sản phẩm..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Danh mục</label>
                    <select name="maLoai" class="form-control" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($loaiSP as $loai): ?>
                            <option value="<?php echo $loai['MaLoai']; ?>">
                                <?php echo htmlspecialchars($loai['TenLoai']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Giá bán (VNĐ)</label>
                    <input type="number" name="gia" class="form-control" placeholder="0" required min="0" step="1000">
                </div>
                <div class="form-group">
                    <label class="form-label">Số lượng nhập</label>
                    <input type="number" name="soLuong" class="form-control" value="0" required min="0">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Hình ảnh sản phẩm</label>
                <input type="file" name="hinhAnh" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả sản phẩm</label>
                <textarea name="moTa" class="form-control" rows="4" placeholder="Viết mô tả chi tiết..."></textarea>
            </div>

            <div style="text-align: right; margin-top: 30px;">
                <button type="submit" class="btn-save">LƯU SẢN PHẨM</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>