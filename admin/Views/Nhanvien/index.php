<?php
// admin/Views/Nhanvien/index.php
include_once '../../includes/header.php';
$db = new Db();

$search = $_GET['search'] ?? '';
$role = $_GET['role'] ?? '';
$status = $_GET['status'] ?? '';

// Truy vấn JOIN bảng nhanvien và taikhoan
$sql = "SELECT nv.*, tk.TenDangNhap, tk.VaiTro, tk.TrangThai 
        FROM nhanvien nv 
        JOIN taikhoan tk ON nv.MaTK = tk.MaTK 
        WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (nv.HoTen LIKE ? OR nv.SDT LIKE ? OR tk.VaiTro LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if (!empty($role)) {
    $sql .= " AND tk.VaiTro = ?";
    $params[] = $role;
}

if ($status !== '') {
    $sql .= " AND tk.TrangThai = ?";
    $params[] = $status;
}
$sql .= " ORDER BY nv.MaNV DESC";
$employees = $db->query($sql, $params)->fetchAll();
?>

<div class="main-content-inner">
    <header class="main-header">
        <h2>Quản lý nhân viên</h2>
        <div style="display: flex; gap: 10px;">
            <a href="add.php" class="btn-filter" style="background: var(--primary-color); text-decoration: none;">
                Thêm nhân viên
            </a>
        </div>
    </header>

    <div class="toolbar">
        <form action="" method="GET" style="display: flex; gap: 12px; width: 100%; flex-wrap: wrap;">
            <input type="text" name="search" class="search-input" style="flex: 5; min-width: 200px;"
                placeholder="Tìm tên hoặc SĐT..." value="<?php echo htmlspecialchars($search); ?>">

            <select name="role" class="filter-select" style="flex: 2;">
                <option value="">Tất cả chức vụ</option>
                <option value="Nhân viên" <?php echo ($role == 'Nhân viên') ? 'selected' : ''; ?>>Nhân viên</option>
                <option value="Quản trị viên" <?php echo ($role == 'Quản trị viên') ? 'selected' : ''; ?>>Quản trị viên</option>
            </select>

            <select name="status" class="filter-select" style="flex: 2;">
                <option value="">Tất cả trạng thái</option>
                <option value="1" <?php echo ($status === '1') ? 'selected' : ''; ?>>Đang hoạt động</option>
                <option value="0" <?php echo ($status === '0') ? 'selected' : ''; ?>>Đã bị khóa</option>
            </select>

            <button type="submit" class="btn-filter">Lọc</button>
            <a href="index.php" class="btn-clear">Xóa lọc</a>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã NV</th>
                    <th>Họ tên</th>
                    <th>Giới tính</th>
                    <th>Liên hệ</th>
                    <th>Chức vụ</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $nv): ?>
                    <tr>
                        <td>#NV<?php echo $nv['MaNV']; ?></td>
                        <td><strong><?php echo htmlspecialchars($nv['HoTen']); ?></strong></td>
                        <td><?php echo $nv['GioiTinh']; ?></td>
                        <td>
                            <?php echo $nv['SDT']; ?><br>
                            <small style="color:#777"><?php echo htmlspecialchars($nv['DiaChi']); ?></small>
                        </td>
                        <td>
                            <span class="badge-rank" style="background: #E3F2FD; color: #1976D2;">
                                <?php echo $nv['VaiTro']; ?>
                            </span>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" <?php echo ($nv['TrangThai'] == 1) ? 'checked' : ''; ?>
                                    onchange="toggleAccountStatus(<?php echo $nv['MaTK']; ?>, this.checked)">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <a href="edit.php?id=<?php echo $nv['MaNV']; ?>" class="btn-view-profile" style="text-decoration: none; display: inline-block;">
                                Sửa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleAccountStatus(maTK, isChecked) {
        const status = isChecked ? 1 : 0;
        fetch(`../../Controller/AdminUserController.php?action=toggle_status&id=${maTK}&status=${status}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    // Hiển thị thông báo lỗi cụ thể từ PHP
                    alert(data.message);
                    // Reset lại nút gạt về trạng thái cũ vì không khóa được
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Lỗi kết nối hệ thống!");
            });
    }
</script>

<?php include_once '../../includes/header.php'; ?>