<?php
// controller/GiohangController.php
session_start();
require_once '../classes/Sanpham.class.php';
require_once '../classes/Giohang.class.php';
require_once '../classes/Chitiet_Giohang.class.php';
$spModel = new Sanpham();
$ctghModel = new Chitiet_Giohang();
if (isset($_POST['maSP'])) {
    $maSP = $_POST['maSP'];
    $sl = isset($_POST['sl']) ? (int)$_POST['sl'] : 1;
    $isAjax = isset($_POST['ajax']) ? true : false;
    if (isset($_SESSION['user_id'])) {
        $maKH = $_SESSION['user_id'];
        $ghModel = new Giohang();
        $gioHang = $ghModel->lay_theo_khach_hang($maKH) ?: $ghModel->tao_moi($maKH);
        $maGH = $gioHang['MaGH'] ?? $gioHang;
        $sp = $spModel->getById($maSP);
        if ($sl > $sp['SoLuongTon']) {
            $sl = $sp['SoLuongTon'];
        }
        $ctghModel->them_san_pham($maGH, $maSP, $sl, $sp['Gia']);
        $ghModel->tinh_lai_tong_tien($maGH);
    } else {
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
if (isset($_GET['action']) && $_GET['action'] == 'xoa') {
    $maSP = $_GET['idsp'];
    if (isset($_SESSION['user_id'])) {
        $maKH = $_SESSION['user_id'];
        $ghModel = new Giohang();
        $gioHang = $ghModel->lay_theo_khach_hang($maKH);
        if ($gioHang) {
            $ctghModel->xoa_san_pham($gioHang['MaGH'], $maSP);
            $ghModel->tinh_lai_tong_tien($gioHang['MaGH']);
        }
    } else {
        if (isset($_SESSION['cart'][$maSP])) {
            unset($_SESSION['cart'][$maSP]);
        }
    }
    header("Location: ../Views/giohang.php");
    exit();
}
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $ghModel = new Giohang();
    if (isset($_POST['sl']) && is_array($_POST['sl'])) {
        foreach ($_POST['sl'] as $maSP => $slMoi) {
            $slMoi = (int)$slMoi;
            $sp = $spModel->getById($maSP);
            if ($slMoi > $sp['SoLuongTon']) {
                $slMoi = $sp['SoLuongTon'];
            }
            if (isset($_SESSION['user_id'])) {
                $maKH = $_SESSION['user_id'];
                $gioHang = $ghModel->lay_theo_khach_hang($maKH);
                if ($slMoi > 0) {
                    $ctghModel->cap_nhat_so_luong($gioHang['MaGH'], $maSP, $slMoi);
                } else {
                    $ctghModel->xoa_san_pham($gioHang['MaGH'], $maSP);
                }
            } else {
                if ($slMoi > 0) {
                    $_SESSION['cart'][$maSP] = $slMoi;
                } else {
                    unset($_SESSION['cart'][$maSP]);
                }
            }
        }
        if (isset($_SESSION['user_id'])) $ghModel->tinh_lai_tong_tien($gioHang['MaGH']);
    }
    header("Location: ../Views/giohang.php?msg=updated");
    exit();
}
