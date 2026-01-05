<?php
// admin/Views/Khuyenmai/add.php
include_once '../../includes/header.php';
$db = new Db();
$products = $db->query("SELECT MaSP, TenSP FROM sanpham")->fetchAll();
?>
<div class="main-content-inner">
    <div class="form-container">
        <form action="../../controller/AdminKhuyenmaiController.php?action=add" method="POST">
            <h3 style="margin-bottom: 20px;">Thông tin khuyến mãi</h3>
            <div class="form-row">
                <div class="form-group" style="flex: 2;">
                    <label class="form-label">Tên chương trình</label>
                    <input type="text" name="tenKM" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Phần trăm giảm (%)</label>
                    <input type="number" name="phanTramGiam" class="form-control" min="1" max="100" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Ngày bắt đầu</label>
                    <input type="date" name="ngayBatDau" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ngày kết thúc</label>
                    <input type="date" name="ngayKetThuc" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Sản phẩm áp dụng</label>
                <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 8px;">
                    <?php foreach ($products as $sp): ?>
                        <div style="margin-bottom: 5px;">
                            <input type="checkbox" name="applied_products[]" value="<?php echo $sp['MaSP']; ?>">
                            <label><?php echo $sp['TenSP']; ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div style="margin-top: 25px; text-align: right;">
                <button type="submit" class="btn-save">LƯU CHƯƠNG TRÌNH</button>
            </div>
        </form>
    </div>
</div>