<?php
// admin/controller/AdminDanhgiaController.php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';
$db = new Db();
$action = $_GET['action'] ?? '';
if ($action == 'delete') {
    $id = $_GET['id'];
    try {
        $db->query("DELETE FROM danhgia WHERE MaDG = ?", [$id]);
        header("Location: ../Views/Danhgia/index.php?msg=deleted");
    } catch (Exception $e) {
        header("Location: ../Views/Danhgia/index.php?msg=error");
    }
    exit();
}
