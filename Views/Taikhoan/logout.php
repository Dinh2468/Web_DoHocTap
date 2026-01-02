<?php
// Views/Taikhoan/logout.php
session_start();

// Lưu lại vai trò trước khi hủy session để quyết định trang chuyển hướng (tùy chọn)
$isAdmin = (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'Quản trị viên' || $_SESSION['user_role'] === 'Nhân viên'));

// Xóa và hủy session
session_unset();
session_destroy();

// Nếu là Admin/Nhân viên đăng xuất, về trang login. Nếu là khách, về trang chủ.
if ($isAdmin) {
    header("Location: /Web_DoHocTap/Views/Taikhoan/login.php");
} else {
    header("Location: /Web_DoHocTap/index.php");
}
exit();
