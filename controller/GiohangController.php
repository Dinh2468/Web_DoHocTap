<?php
// controller/GiohangController.php
session_start();
require_once '../classes/Sanpham.class.php';
require_once '../classes/Giohang.class.php';
require_once '../classes/Chitiet_Giohang.class.php';

$spModel = new Sanpham();
$ctghModel = new Chitiet_Giohang();


// Xử lý thêm sản phẩm
if (isset($_POST['maSP'])) {
    $maSP = $_POST['maSP'];
    $sl = isset($_POST['sl']) ? (int)$_POST['sl'] : 1;
    $isAjax = isset($_POST['ajax']) ? true : false;

    // Kiểm tra nếu ĐÃ đăng nhập
    if (isset($_SESSION['user_id'])) {
        $maKH = $_SESSION['user_id'];
        // Logic cũ: Lưu vào Database
        $ghModel = new Giohang();
        $gioHang = $ghModel->lay_theo_khach_hang($maKH) ?: $ghModel->tao_moi($maKH);
        $maGH = $gioHang['MaGH'] ?? $gioHang;
        $sp = $spModel->getById($maSP);
        $ctghModel->them_san_pham($maGH, $maSP, $sl, $sp['Gia']);
        $ghModel->tinh_lai_tong_tien($maGH);
    } else {
        // Lưu vào SESSION (Dành cho khách chưa đăng nhập)
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$maSP])) {
            $_SESSION['cart'][$maSP] += $sl;
        } else {
            $_SESSION['cart'][$maSP] = $sl;
        }
    }
    if ($isAjax) {
        echo "Thành công";
        exit();
    }
    header("Location: ../Views/giohang.php");
    exit();
}
// Xử lý xóa sản phẩm
if (isset($_GET['action']) && $_GET['action'] == 'xoa') {
    $maSP = $_GET['idsp'];
    if (isset($_SESSION['user_id'])) {
        // Nếu đã đăng nhập thì xóa trong Database
        $maKH = $_SESSION['user_id'];
        $ghModel = new Giohang();
        $gioHang = $ghModel->lay_theo_khach_hang($maKH);
        if ($gioHang) {
            $ctghModel->xoa_san_pham($gioHang['MaGH'], $maSP);
            $ghModel->tinh_lai_tong_tien($gioHang['MaGH']);
        }
    } else {
        // Nếu chưa đăng nhập thì xóa trong SESSION
        if (isset($_SESSION['cart'][$maSP])) {
            unset($_SESSION['cart'][$maSP]);
        }
    }
    header("Location: ../Views/giohang.php");
    exit();
}
// Xử lý CẬP NHẬT số lượng
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    if (isset($_POST['sl']) && is_array($_POST['sl'])) {
        if (isset($_SESSION['user_id'])) {
            // Cập nhật Database cho thành viên
            $maKH = $_SESSION['user_id'];
            $ghModel = new Giohang();
            $gioHang = $ghModel->lay_theo_khach_hang($maKH);
            if ($gioHang) {
                foreach ($_POST['sl'] as $maSP => $slMoi) {
                    $slMoi = (int)$slMoi;
                    if ($slMoi > 0) {
                        $ctghModel->cap_nhat_so_luong($gioHang['MaGH'], $maSP, $slMoi);
                    } else {
                        $ctghModel->xoa_san_pham($gioHang['MaGH'], $maSP);
                    }
                }
                $ghModel->tinh_lai_tong_tien($gioHang['MaGH']);
            }
        } else {
            // Cập nhật SESSION cho khách vãng lai
            foreach ($_POST['sl'] as $maSP => $slMoi) {
                $slMoi = (int)$slMoi;
                if ($slMoi > 0) {
                    $_SESSION['cart'][$maSP] = $slMoi;
                } else {
                    unset($_SESSION['cart'][$maSP]);
                }
            }
        }
    }
    header("Location: ../Views/giohang.php");
    exit();
}
