<?php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';

$db = new Db();
$action = $_GET['action'] ?? '';

if ($action == 'update_status') {
    $maDH = $_POST['maDH'];
    $status = $_POST['status'];

    $db->query("UPDATE donhang SET TrangThai = ? WHERE MaDH = ?", [$status, $maDH]);
    header("Location: ../Views/Donhang/detail.php?id=$maDH&msg=updated");
    exit();
}
