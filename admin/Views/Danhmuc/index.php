<?php
// admin/Views/Danhmuc/index.php
include_once '../../includes/header.php';
if ($_SESSION['user_role'] !== 'Quản trị viên') {
    header("Location: /Web_DoHocTap/admin/index.php");
    exit();
}
$db = new Db();
$categories = $db->query("SELECT * FROM loaisp ORDER BY MaLoai DESC")->fetchAll();
?>

<div class="main-content-inner">
    <header class="main-header">
        <h2>Quản lý Danh mục sản phẩm</h2>
        <button onclick="openModal('add')" class="btn-filter" style="background: var(--primary-color);">+ Thêm danh mục</button>
    </header>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Tên danh mục</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td>#<?php echo $cat['MaLoai']; ?></td>
                        <td style="font-weight: bold;"><?php echo $cat['TenLoai']; ?></td>
                        <td><?php echo $cat['MoTa']; ?></td>
                        <td>
                            <div class="action-group">
                                <button class="btn-action btn-edit"
                                    onclick="openModal('edit', <?php echo htmlspecialchars(json_encode($cat)); ?>)">
                                    Sửa
                                </button>

                                <a href="../../controller/AdminDanhmucController.php?action=delete&id=<?php echo $cat['MaLoai']; ?>"
                                    class="btn-action btn-delete"
                                    onclick="return confirm('Xóa danh mục này?')">
                                    Xóa
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="categoryModal" class="modal" style="display:none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; margin: 10% auto; padding: 20px; width: 400px; border-radius: 8px;">
        <h3 id="modalTitle">Thêm danh mục</h3>
        <form id="categoryForm" action="../../controller/AdminDanhmucController.php?action=add" method="POST">
            <input type="hidden" name="maLoai" id="maLoai">
            <div class="form-group" style="margin-top: 15px;">
                <label class="form-label">Tên danh mục</label>
                <input type="text" name="tenLoai" id="tenLoai" class="form-control" required>
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <label class="form-label">Mô tả</label>
                <textarea name="moTa" id="moTa" class="form-control" rows="3"></textarea>
            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="button" onclick="closeModal()" class="btn-clear">Hủy</button>
                <button type="submit" class="btn-save">Lưu lại</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(type, data = null) {
        const modal = document.getElementById('categoryModal');
        const form = document.getElementById('categoryForm');
        const title = document.getElementById('modalTitle');

        modal.style.display = 'block';
        if (type === 'edit' && data) {
            title.innerText = 'Chỉnh sửa danh mục';
            form.action = '../../controller/AdminDanhmucController.php?action=edit';
            document.getElementById('maLoai').value = data.MaLoai;
            document.getElementById('tenLoai').value = data.TenLoai;
            document.getElementById('moTa').value = data.MoTa;
        } else {
            title.innerText = 'Thêm danh mục mới';
            form.action = '../../controller/AdminDanhmucController.php?action=add';
            form.reset();
        }
    }

    function closeModal() {
        document.getElementById('categoryModal').style.display = 'none';
    }
</script>