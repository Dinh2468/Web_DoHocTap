<?php
// admin/Views/Nhanvien/add.php
include_once '../../includes/header.php';
// Không cần khởi tạo Db ở đây vì header.php đã xử lý session và db cơ bản
?>

<div class="main-content-inner">
    <header class="main-header">
        <h2>Thêm nhân viên mới</h2>
        <a href="index.php" style="text-decoration: none; color: var(--primary-color);">← Quay lại danh sách</a>
    </header>

    <div class="form-container">
        <form action="../../Controller/AdminUserController.php?action=add_employee" method="POST">

            <h3 style="margin-bottom: 20px; color: var(--primary-color); border-bottom: 1px solid #eee; padding-bottom: 10px;">
                THÔNG TIN CÁ NHÂN
            </h3>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="hoTen" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Giới tính</label>
                    <select name="gioiTinh" class="form-control">
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="sdt" class="form-control" placeholder="090..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="ngaySinh" class="form-control" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="diaChi" class="form-control" placeholder="Địa chỉ thường trú...">
            </div>

            <h3 style="margin-bottom: 20px; color: var(--primary-color); border-bottom: 1px solid #eee; padding-bottom: 10px;">
                THÔNG TIN TÀI KHOẢN
            </h3>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" placeholder="Dùng để đăng nhập hệ thống" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="Thiết lập mật khẩu ban đầu" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email liên hệ..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Vai trò hệ thống</label>
                    <select name="vaiTro" class="form-control">
                        <option value="Nhân viên">Nhân viên</option>
                        <option value="Quản trị viên">Quản trị viên</option>
                    </select>
                </div>
            </div>

            <div style="text-align: right; margin-top: 30px;">
                <button type="reset" class="btn-clear" style="margin-right: 10px; cursor: pointer;">Nhập lại</button>
                <button type="submit" class="btn-save">XÁC NHẬN THÊM</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, select, textarea');
        const storageKey = 'employee_form_draft';

        const savedData = JSON.parse(localStorage.getItem(storageKey));
        if (savedData) {
            inputs.forEach(input => {
                if (savedData[input.name] !== undefined && input.type !== 'password') {
                    input.value = savedData[input.name];
                }
            });
        }

        form.addEventListener('input', function() {
            const formData = {};
            inputs.forEach(input => {
                if (input.name && input.type !== 'password') {
                    formData[input.name] = input.value;
                }
            });
            localStorage.setItem(storageKey, JSON.stringify(formData));
        });

        form.addEventListener('submit', function() {
            localStorage.removeItem(storageKey);
        });

        const btnReset = document.querySelector('.btn-clear');
        if (btnReset) {
            btnReset.addEventListener('click', function() {
                localStorage.removeItem(storageKey);
            });
        }
    });
</script>
<?php include_once '../../includes/header.php'; ?>