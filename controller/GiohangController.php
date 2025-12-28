<?php
// controller/GiohangController.php
session_start();
require_once '../classes/Giohang.class.php';
require_once '../classes/Chitiet_Giohang.class.php';

$ghModel = new Giohang();
$ctghModel = new Chitiet_Giohang();

// Giả định bạn đã có cơ chế đăng nhập và lưu MaKH vào session
$maKH = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // Tạm thời để id = 1 để test

// Kiểm tra khách hàng đã có giỏ hàng chưa, nếu chưa thì tạo mới
$gioHang = $ghModel->lay_theo_khach_hang($maKH);
if (!$gioHang) {
    $maGH = $ghModel->tao_moi($maKH);
} else {
    $maGH = $gioHang['MaGH'];
}

// Xử lý thêm sản phẩm
if (isset($_POST['maSP'])) {
    $maSP = $_POST['maSP'];
    $sl = isset($_POST['sl']) ? (int)$_POST['sl'] : 1;
    $gia = isset($_POST['gia']) ? $_POST['gia'] : 0; // Lấy giá từ form hoặc DB

    $ctghModel->them_san_pham($maGH, $maSP, $sl, $gia);
    $ghModel->tinh_lai_tong_tien($maGH);

    header("Location: ../Views/giohang.php"); // Chuyển hướng sang trang giỏ hàng
}

// Xử lý xóa sản phẩm
if (isset($_GET['action']) && $_GET['action'] == 'xoa') {
    $maSP = $_GET['idsp'];
    $ctghModel->xoa_san_pham($maGH, $maSP);
    $ghModel->tinh_lai_tong_tien($maGH);
    header("Location: ../Views/giohang.php");
}

// Xử lý CẬP NHẬT số lượng
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    if (isset($_POST['sl']) && is_array($_POST['sl'])) {
        foreach ($_POST['sl'] as $maSP => $slMoi) {
            $maSP = (int)$maSP;
            $slMoi = (int)$slMoi;

            if ($slMoi > 0) {
                $ctghModel->cap_nhat_so_luong($maGH, $maSP, $slMoi);
            } else {
                $ctghModel->xoa_san_pham($maGH, $maSP);
            }
        }
        // Tính lại tiền tổng trong DB
        $ghModel->tinh_lai_tong_tien($maGH);
    }
    header("Location: ../Views/giohang.php");
    exit(); // Luôn luôn dùng exit sau header chuyển hướng
}
