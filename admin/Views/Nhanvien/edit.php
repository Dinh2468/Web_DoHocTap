<?php
// admin/Views/Nhanvien/edit.php
include_once '../../includes/header.php';
$db = new Db();

// 1. Lấy ID nhân viên từ URL
$id = $_GET['id'] ?? '';
if (empty($id)) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT nv.*, tk.TenDangNhap, tk.Email, tk.VaiTro, tk.TrangThai 
        FROM nhanvien nv 
        JOIN taikhoan tk ON nv.MaTK = tk.MaTK 
        WHERE nv.MaNV = ?";
$employee = $db->query($sql, [$id])->fetch();

if (!$employee) {
    echo "Nhân viên không tồn tại!";
    exit();
}
?>

<div class="main-content-inner">
    <header class="main-header">
        <h2>Chỉnh sửa nhân viên: <?php echo htmlspecialchars($employee['HoTen']); ?></h2>
        <a href="index.php" style="text-decoration: none; color: var(--primary-color);">← Quay lại</a>
    </header>

    <div class="form-container">
        <form action="../../Controller/AdminUserController.php?action=edit_employee" method="POST">
            <input type="hidden" name="maNV" value="<?php echo $employee['MaNV']; ?>">
            <input type="hidden" name="maTK" value="<?php echo $employee['MaTK']; ?>">

            <h3 style="margin-bottom: 20px; color: var(--primary-color); border-bottom: 1px solid #eee; padding-bottom: 10px;">Thông tin cá nhân</h3>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="hoTen" class="form-control"
                        value="<?php echo htmlspecialchars($employee['HoTen']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Giới tính</label>
                    <select name="gioiTinh" class="form-control">
                        <option value="Nam" <?php echo ($employee['GioiTinh'] == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                        <option value="Nữ" <?php echo ($employee['GioiTinh'] == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="sdt" class="form-control"
                        value="<?php echo htmlspecialchars($employee['SDT']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="ngaySinh" class="form-control"
                        value="<?php echo $employee['NgaySinh']; ?>">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="diaChi" class="form-control"
                    value="<?php echo htmlspecialchars($employee['DiaChi']); ?>">
            </div>

            <h3 style="margin-bottom: 20px; color: var(--primary-color); border-bottom: 1px solid #eee; padding-bottom: 10px;">Thông tin tài khoản</h3>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" style="background: #f0f0f0;"
                        value="<?php echo $employee['TenDangNhap']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" style="background: #f0f0f0;"
                        value=" <?php echo htmlspecialchars($employee['Email']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Vai trò</label>
                    <select name="vaiTro" class="form-control">
                        <option value="Nhân viên" <?php echo ($employee['VaiTro'] == 'Nhân viên') ? 'selected' : ''; ?>>Nhân viên</option>
                        <option value="Quản trị viên" <?php echo ($employee['VaiTro'] == 'Quản trị viên') ? 'selected' : ''; ?>>Quản trị viên</option>
                    </select>
                </div>
            </div>

            <div style="text-align: right; margin-top: 30px;">
                <button type="submit" class="btn-save">LƯU THAY ĐỔI</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../../includes/header.php'; ?>