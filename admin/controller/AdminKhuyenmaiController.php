<?php
//admin/controller/AdminKhuyenmaiController.php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';
$db = new Db();
$action = $_GET['action'] ?? '';

if ($action == 'add' || $action == 'edit') {
    $tenKM = $_POST['tenKM'];
    $ngayBD = $_POST['ngayBatDau'];
    $ngayKT = $_POST['ngayKetThuc'];
    $phanTram = $_POST['phanTramGiam'];
    $dieuKien = $_POST['dieuKien'];
    $selectedProducts = $_POST['applied_products'] ?? [];

    if ($action == 'add') {
        $db->query(
            "INSERT INTO khuyenmai (TenKM, NgayBatDau, NgayKetThuc, PhanTramGiam, DieuKienApDung) VALUES (?, ?, ?, ?, ?)",
            [$tenKM, $ngayBD, $ngayKT, $phanTram, $dieuKien]
        );
        $maKM = $db->query("SELECT LAST_INSERT_ID() as id")->fetch()['id'];
    } else {
        $maKM = $_POST['maKM'];
        $db->query(
            "UPDATE khuyenmai SET TenKM=?, NgayBatDau=?, NgayKetThuc=?, PhanTramGiam=?, DieuKienApDung=? WHERE MaKM=?",
            [$tenKM, $ngayBD, $ngayKT, $phanTram, $dieuKien, $maKM]
        );
        // Xóa liên kết cũ để cập nhật mới
        $db->query("DELETE FROM sp_km WHERE MaKM = ?", [$maKM]);
    }

    // Lưu danh sách sản phẩm áp dụng
    foreach ($selectedProducts as $maSP) {
        $db->query("INSERT INTO sp_km (MaSP, MaKM) VALUES (?, ?)", [$maSP, $maKM]);
    }

    header("Location: ../Views/Khuyenmai/index.php?msg=success");
}

if ($action == 'delete') {
    $id = $_GET['id'];
    $db->query("DELETE FROM sp_km WHERE MaKM = ?", [$id]);
    $db->query("DELETE FROM khuyenmai WHERE MaKM = ?", [$id]);
    header("Location: ../Views/Khuyenmai/index.php?msg=deleted");
}
