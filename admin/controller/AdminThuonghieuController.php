<?php
// admin/controller/AdminThuonghieuController.php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';
$db = new Db();
$action = $_GET['action'] ?? '';

if ($action == 'add') {
    $tenTH = $_POST['tenTH'];
    $quocGia = $_POST['quocGia'];
    $moTa = $_POST['moTa'];
    $db->query("INSERT INTO thuonghieu (TenTH, QuocGia, MoTa) VALUES (?, ?, ?)", [$tenTH, $quocGia, $moTa]);
    header("Location: ../Views/Thuonghieu/index.php?msg=added");
    exit();
}

if ($action == 'edit') {
    $maTH = $_POST['maTH'];
    $tenTH = $_POST['tenTH'];
    $quocGia = $_POST['quocGia'];
    $moTa = $_POST['moTa'];
    $db->query("UPDATE thuonghieu SET TenTH = ?, QuocGia = ?, MoTa = ? WHERE MaTH = ?", [$tenTH, $quocGia, $moTa, $maTH]);
    header("Location: ../Views/Thuonghieu/index.php?msg=updated");
    exit();
}

if ($action == 'delete') {
    $id = $_GET['id'];
    // Kiểm tra ràng buộc: Nếu có sản phẩm thuộc thương hiệu này thì không cho xóa
    $check = $db->query("SELECT COUNT(*) as total FROM sanpham WHERE MaTH = ?", [$id])->fetch();
    if ($check['total'] > 0) {
        header("Location: ../Views/Thuonghieu/index.php?msg=error_constrain");
    } else {
        $db->query("DELETE FROM thuonghieu WHERE MaTH = ?", [$id]);
        header("Location: ../Views/Thuonghieu/index.php?msg=deleted");
    }
    exit();
}
