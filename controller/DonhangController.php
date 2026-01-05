<?php
// controller/DonhangController.php
session_start();
require_once '../classes/DB.class.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Views/Taikhoan/login.php");
    exit();
}
$db = new Db();
$maKH = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';
$maDH = $_GET['id'] ?? 0;
if ($action === 'huy' && $maDH > 0) {
    $sqlCheck = "SELECT * FROM donhang WHERE MaDH = ? AND MaKH = ? AND TrangThai = 'Chờ xử lý'";
    $donhang = $db->query($sqlCheck, [$maDH, $maKH])->fetch();
    if ($donhang) {
        $sqlHuy = "UPDATE donhang SET TrangThai = 'Đã hủy' WHERE MaDH = ?";
        $db->query($sqlHuy, [$maDH]);
        header("Location: ../Views/Donhang/lichsu_donhang.php?msg=cancelled");
    } else {
        header("Location: ../Views/Donhang/lichsu_donhang.php?msg=error");
    }
    exit();
}
