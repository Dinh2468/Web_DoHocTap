<?php
// Views/Taikhoan/logout.php
session_start();
$isAdmin = (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'Quản trị viên' || $_SESSION['user_role'] === 'Nhân viên'));
session_unset();
session_destroy();
if ($isAdmin) {
    header("Location: /Web_DoHocTap/Views/Taikhoan/login.php");
} else {
    header("Location: /Web_DoHocTap/index.php");
}
exit();
