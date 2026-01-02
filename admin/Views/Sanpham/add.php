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
                <div class="form-group" style="flex: 2;">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="tenSP" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Danh mục</label>
                    <select name="maLoai" class="form-control">
                        <?php foreach ($loaiSP as $l): ?>
                            <option value="<?php echo $l['MaLoai']; ?>"><?php echo $l['TenLoai']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Giá bán (VNĐ)</label>
                    <input type="number" name="gia" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Số lượng nhập</label>
                    <input type="number" name="soLuong" class="form-control" value="0">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Thương hiệu</label>
                    <select name="maTH" class="form-control">
                        <?php foreach ($thuongHieu as $t): ?>
                            <option value="<?php echo $t['MaTH']; ?>"><?php echo $t['TenTH']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Nhà cung cấp</label>
                    <select name="maNCC" class="form-control">
                        <?php foreach ($nhaCC as $n): ?>
                            <option value="<?php echo $n['MaNCC']; ?>"><?php echo $n['TenNCC']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Hình ảnh</label>
                <input type="file" name="hinhAnh" class="form-control" accept="image/*" required>
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả sản phẩm</label>
                <textarea name="moTa" class="form-control" rows="4"></textarea>
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <button type="submit" class="btn-save" style="padding: 12px 30px;">LƯU SẢN PHẨM</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>