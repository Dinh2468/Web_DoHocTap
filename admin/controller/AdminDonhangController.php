<?php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';

$db = new Db();
$action = $_GET['action'] ?? '';

if ($action == 'update_status') {
    $maDH = $_POST['maDH'];
    $status = $_POST['status'];

    // 1. Lấy trạng thái hiện tại của đơn hàng trước khi cập nhật
    $oldOrder = $db->query("SELECT TrangThai FROM donhang WHERE MaDH = ?", [$maDH])->fetch();

    // 2. Nếu chuyển sang "Hoàn thành" từ một trạng thái chưa trừ kho
    if ($status == 'Hoàn thành' && $oldOrder['TrangThai'] != 'Hoàn thành') {
        $items = $db->query("SELECT MaSP, SoLuong FROM chitietdh WHERE MaDH = ?", [$maDH])->fetchAll();
        foreach ($items as $item) {
            // Trừ tồn kho
            $db->query("UPDATE sanpham SET SoLuongTon = SoLuongTon - ? WHERE MaSP = ?", [$item['SoLuong'], $item['MaSP']]);
        }
    }

    // 3. Cập nhật trạng thái
    $db->query("UPDATE donhang SET TrangThai = ? WHERE MaDH = ?", [$status, $maDH]);
    header("Location: ../Views/Donhang/detail.php?id=$maDH&msg=updated");
    exit();
}
