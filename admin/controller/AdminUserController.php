<?php
// admin/controller/AdminUserController.php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';

$db = new Db();
$action = $_GET['action'] ?? '';

if ($action == 'toggle_status') {
    $maTK = $_GET['id'] ?? '';
    $status = $_GET['status'] ?? '';

    if (!empty($maTK)) {
        try {
            // Cập nhật trạng thái trong bảng taikhoan
            $sql = "UPDATE taikhoan SET TrangThai = ? WHERE MaTK = ?";
            $db->query($sql, [$status, $maTK]);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    exit();
}
// Thêm đoạn này vào file AdminUserController.php
if ($action == 'get_details') {
    $id = $_GET['id'] ?? '';
    // 1. Lấy thông tin khách hàng
    $customer = $db->query("SELECT kh.*, tk.TenDangNhap, tk.TrangThai 
                            FROM khachhang kh 
                            JOIN taikhoan tk ON kh.MaTK = tk.MaTK 
                            WHERE kh.MaKH = ?", [$id])->fetch();

    // 2. Lấy lịch sử đơn hàng
    $orders = $db->query("SELECT MaDH, NgayDat, TongTien, TrangThai 
                          FROM donhang WHERE MaKH = ? ORDER BY NgayDat DESC", [$id])->fetchAll();

    echo json_encode([
        'customer' => $customer,
        'orders' => $orders
    ]);
    exit();
}
