<?php
// admin/Views/Thuonghieu/index.php
include_once '../../includes/header.php';
if ($_SESSION['user_role'] !== 'Quản trị viên') {
    header("Location: /Web_DoHocTap/admin/index.php");
    exit();
}
$db = new Db();
$brands = $db->query("SELECT * FROM thuonghieu ORDER BY MaTH DESC")->fetchAll();
?>
<div class="main-content-inner">
    <header class="main-header">
        <h2>Quản lý Thương hiệu</h2>
        <button onclick="openModal('add')" class="btn-filter" style="background: var(--primary-color);">+ Thêm thương hiệu</button>
    </header>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Tên thương hiệu</th>
                    <th>Quốc gia</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($brands as $brand): ?>
                    <tr>
                        <td>#<?php echo $brand['MaTH']; ?></td>
                        <td style="font-weight: bold;"><?php echo $brand['TenTH']; ?></td>
                        <td><?php echo $brand['QuocGia'] ?? 'N/A'; ?></td>
                        <td><?php echo $brand['MoTa']; ?></td>
                        <td>
                            <div class="action-group">
                                <button class="btn-action btn-edit" style="border:none; outline:none;"
                                    onclick="openModal('edit', <?php echo htmlspecialchars(json_encode($brand)); ?>)">Sửa</button>
                                <a href="../../controller/AdminThuonghieuController.php?action=delete&id=<?php echo $brand['MaTH']; ?>"
                                    class="btn-action btn-delete" style="border:none;"
                                    onclick="return confirm('Xóa thương hiệu này?')">Xóa</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div id="brandModal" class="modal" style="display:none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; margin: 8% auto; padding: 25px; width: 450px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
        <h3 id="modalTitle" style="color: var(--primary-color); margin-bottom: 20px;">Thêm thương hiệu</h3>
        <form id="brandForm" action="../../controller/AdminThuonghieuController.php?action=add" method="POST">
            <input type="hidden" name="maTH" id="maTH">
            <div class="form-group">
                <label class="form-label">Tên thương hiệu</label>
                <input type="text" name="tenTH" id="tenTH" class="form-control" required>
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <label class="form-label">Quốc gia</label>
                <input type="text" name="quocGia" id="quocGia" class="form-control" placeholder="Ví dụ: Việt Nam, Nhật Bản...">
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <label class="form-label">Mô tả</label>
                <textarea name="moTa" id="moTa" class="form-control" rows="3"></textarea>
            </div>
            <div style="margin-top: 25px; text-align: right; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal()" class="btn-clear">Hủy</button>
                <button type="submit" class="btn-save">Lưu lại</button>
            </div>
        </form>
    </div>
</div>
<script>
    function openModal(type, data = null) {
        const modal = document.getElementById('brandModal');
        const form = document.getElementById('brandForm');
        const title = document.getElementById('modalTitle');
        modal.style.display = 'block';
        if (type === 'edit' && data) {
            title.innerText = 'Chỉnh sửa thương hiệu';
            form.action = '../../controller/AdminThuonghieuController.php?action=edit';
            document.getElementById('maTH').value = data.MaTH;
            document.getElementById('tenTH').value = data.TenTH;
            document.getElementById('quocGia').value = data.QuocGia;
            document.getElementById('moTa').value = data.MoTa;
        } else {
            title.innerText = 'Thêm thương hiệu mới';
            form.action = '../../controller/AdminThuonghieuController.php?action=add';
            form.reset();
        }
    }

    function closeModal() {
        document.getElementById('brandModal').style.display = 'none';
    }
    window.onclick = function(event) {
        if (event.target == document.getElementById('brandModal')) closeModal();
    }
</script>