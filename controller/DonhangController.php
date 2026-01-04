<?php
// controller/DonhangController.php
session_start();
require_once '../classes/DB.class.php';

// Kiểm tra đăng nhập bảo mật
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Views/Taikhoan/login.php");
    exit();
}

$db = new Db();
$maKH = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';
$maDH = $_GET['id'] ?? 0;

if ($action === 'huy' && $maDH > 0) {
    // 1. Kiểm tra xem đơn hàng có thực sự thuộc về khách này và đang ở trạng thái 'Chờ xử lý' không
    $sqlCheck = "SELECT * FROM donhang WHERE MaDH = ? AND MaKH = ? AND TrangThai = 'Chờ xử lý'";
    $donhang = $db->query($sqlCheck, [$maDH, $maKH])->fetch();

    if ($donhang) {
        // 2. Cập nhật trạng thái thành 'Đã hủy'
        $sqlHuy = "UPDATE donhang SET TrangThai = 'Đã hủy' WHERE MaDH = ?";
        $db->query($sqlHuy, [$maDH]);

        // Chuyển hướng về trang lịch sử kèm thông báo thành công
        header("Location: ../Views/Donhang/lichsu_donhang.php?msg=cancelled");
    } else {
        // Đơn hàng không thể hủy (đã giao hoặc không tồn tại)
        header("Location: ../Views/Donhang/lichsu_donhang.php?msg=error");
    }
    exit();
}
