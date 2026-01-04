<?php
// admin/controller/AdminDanhmucController.php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';
$db = new Db();
$action = $_GET['action'] ?? '';

if ($action == 'add') {
    $tenLoai = $_POST['tenLoai'];
    $moTa = $_POST['moTa'];
    $db->query("INSERT INTO loaisp (TenLoai, MoTa) VALUES (?, ?)", [$tenLoai, $moTa]);
    header("Location: ../Views/Danhmuc/index.php?msg=added");
}

if ($action == 'edit') {
    $maLoai = $_POST['maLoai'];
    $tenLoai = $_POST['tenLoai'];
    $moTa = $_POST['moTa'];
    $db->query("UPDATE loaisp SET TenLoai = ?, MoTa = ? WHERE MaLoai = ?", [$tenLoai, $moTa, $maLoai]);
    header("Location: ../Views/Danhmuc/index.php?msg=updated");
}

if ($action == 'delete') {
    $id = $_GET['id'];
    // Kiểm tra xem có sản phẩm nào thuộc danh mục này không trước khi xóa
    $check = $db->query("SELECT COUNT(*) as total FROM sanpham WHERE MaLoai = ?", [$id])->fetch();
    if ($check['total'] > 0) {
        header("Location: ../Views/Danhmuc/index.php?msg=error_constrain");
    } else {
        $db->query("DELETE FROM loaisp WHERE MaLoai = ?", [$id]);
        header("Location: ../Views/Danhmuc/index.php?msg=deleted");
    }
}
