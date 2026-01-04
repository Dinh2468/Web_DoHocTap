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

    if ($oldOrder) {
        // TRƯỜNG HỢP 1: Chuyển sang Hoàn thành (Trừ tồn kho)
        if ($status == 'Hoàn thành' && $oldOrder['TrangThai'] != 'Hoàn thành') {
            $items = $db->query("SELECT MaSP, SoLuong FROM chitietdh WHERE MaDH = ?", [$maDH])->fetchAll();
            foreach ($items as $item) {
                $db->query("UPDATE sanpham SET SoLuongTon = SoLuongTon - ? WHERE MaSP = ?", [$item['SoLuong'], $item['MaSP']]);
            }
        }

        // TRƯỜNG HỢP 2: Đang từ Hoàn thành chuyển sang Đã hủy (Cộng lại tồn kho)
        if ($status == 'Đã hủy' && $oldOrder['TrangThai'] == 'Hoàn thành') {
            $items = $db->query("SELECT MaSP, SoLuong FROM chitietdh WHERE MaDH = ?", [$maDH])->fetchAll();
            foreach ($items as $item) {
                $db->query("UPDATE sanpham SET SoLuongTon = SoLuongTon + ? WHERE MaSP = ?", [$item['SoLuong'], $item['MaSP']]);
            }
        }

        // 2. Tiến hành cập nhật trạng thái mới vào database
        $db->query("UPDATE donhang SET TrangThai = ? WHERE MaDH = ?", [$status, $maDH]);
    }
    header("Location: ../Views/Donhang/detail.php?id=$maDH&msg=updated");
    exit();
}
