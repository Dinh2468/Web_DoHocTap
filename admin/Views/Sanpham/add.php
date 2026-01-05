<?php
// admin/Views/Sanpham/add.php
include_once '../../includes/header.php';
$db = new Db();
$loaiSP = $db->query("SELECT * FROM loaisp")->fetchAll();
?>
<div class="main-content-inner">
    <header class="main-header">
        <h2>Thêm sản phẩm mới</h2>
        <a href="index.php" style="color: var(--primary-color); text-decoration: none;">← Quay lại danh sách</a>
    </header>
    <div class="table-container">
        <form action="../../Controller/AdminSanphamController.php?action=add" method="POST" enctype="multipart/form-data" id="productForm">
            <h3 style="margin-bottom: 20px; color: var(--primary-color); border-bottom: 1px solid #eee; padding-bottom: 10px;">THÔNG TIN CƠ BẢN</h3>
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
                    <label class="form-label">Số lượng</label>
                    <input type="number" name="soLuong" class="form-control"
                        value="0" required min="0"
                        oninput="if(this.value < 0) this.value = 0;">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Hình ảnh đại diện</label>
                <input type="file" name="hinhAnh" class="form-control" accept="image/*" onchange="previewImage(this)">
                <div id="imagePreview" style="margin-top: 10px;"></div>
            </div>
            <div class="form-group">
                <label class="form-label">Mô tả chi tiết</label>
                <textarea name="moTa" class="form-control" rows="5" placeholder="Viết mô tả giúp khách hàng hiểu rõ về sản phẩm..."></textarea>
            </div>
            <div style="text-align: right; margin-top: 30px;">
                <button type="reset" id="btnReset" class="btn-clear" style="margin-right: 10px; cursor: pointer;">Hủy nhập</button>
                <button type="submit" class="btn-save">LƯU SẢN PHẨM</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('productForm');
        const inputs = form.querySelectorAll('input:not([type="file"]), select, textarea');
        const storageKey = 'product_form_draft';

        const savedData = JSON.parse(localStorage.getItem(storageKey));
        if (savedData) {
            inputs.forEach(input => {
                if (savedData[input.name] !== undefined) {
                    input.value = savedData[input.name];
                }
            });
        }
        form.addEventListener('input', function() {
            const formData = {};
            inputs.forEach(input => {
                if (input.name) {
                    formData[input.name] = input.value;
                }
            });
            localStorage.setItem(storageKey, JSON.stringify(formData));
        });
        form.addEventListener('submit', () => localStorage.removeItem(storageKey));
        document.getElementById('btnReset').addEventListener('click', () => localStorage.removeItem(storageKey));
    });

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" style="max-width: 150px; border-radius: 8px; border: 1px solid #ddd;">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php include_once '../../includes/footer.php'; ?>