<?php
// admin/controller/AdminDonhangController.php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';
$db = new Db();
$action = $_GET['action'] ?? '';
if ($action == 'update_status') {
    $maDH = $_POST['maDH'];
    $status = $_POST['status'];
    $oldOrder = $db->query("SELECT TrangThai FROM donhang WHERE MaDH = ?", [$maDH])->fetch();
    if ($oldOrder) {
        if ($status == 'Hoàn thành' && $oldOrder['TrangThai'] != 'Hoàn thành') {
            $items = $db->query("SELECT MaSP, SoLuong FROM chitietdh WHERE MaDH = ?", [$maDH])->fetchAll();
            foreach ($items as $item) {
                $db->query("UPDATE sanpham SET SoLuongTon = SoLuongTon - ? WHERE MaSP = ?", [$item['SoLuong'], $item['MaSP']]);
            }
        }
        if ($status == 'Đã hủy' && $oldOrder['TrangThai'] == 'Hoàn thành') {
            $items = $db->query("SELECT MaSP, SoLuong FROM chitietdh WHERE MaDH = ?", [$maDH])->fetchAll();
            foreach ($items as $item) {
                $db->query("UPDATE sanpham SET SoLuongTon = SoLuongTon + ? WHERE MaSP = ?", [$item['SoLuong'], $item['MaSP']]);
            }
        }
        $db->query("UPDATE donhang SET TrangThai = ? WHERE MaDH = ?", [$status, $maDH]);
    }
    header("Location: ../Views/Donhang/detail.php?id=$maDH&msg=updated");
    exit();
}
