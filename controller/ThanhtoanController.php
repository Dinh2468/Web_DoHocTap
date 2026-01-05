<?php
// controller/ThanhtoanController.php
session_start();
require_once '../classes/DB.class.php';
require_once '../classes/Giohang.class.php';
require_once '../classes/Chitiet_Giohang.class.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Db();
    $ghModel = new Giohang();
    $ctghModel = new Chitiet_Giohang();
    $hoTen = $_POST['hoTen'];
    $sdt = $_POST['sdt'];
    $diaChi = $_POST['diaChi'];
    $ghiChu = $_POST['ghiChu'];
    $maKH = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    if (!$maKH) {
        header("Location: ../Views/Taikhoan/login.php");
        exit();
    }
    $gioHang = $ghModel->lay_theo_khach_hang($maKH);
    $itemsCheck = $ctghModel->lay_danh_sach_trong_gio($gioHang['MaGH']);
    $errorFound = false;
    foreach ($itemsCheck as $item) {
        $currentSp = $db->query("SELECT SoLuongTon FROM sanpham WHERE MaSP = ?", [$item['MaSP']])->fetch();
        if ($item['SoLuong'] > $currentSp['SoLuongTon']) {
            $errorFound = true;
            $ctghModel->cap_nhat_so_luong($gioHang['MaGH'], $item['MaSP'], $currentSp['SoLuongTon']);
        }
    }
    if ($errorFound) {
        $ghModel->tinh_lai_tong_tien($gioHang['MaGH']);
        header("Location: ../Views/giohang.php?error=out_of_stock");
        exit();
    }
    $ghMoi = $ghModel->lay_theo_khach_hang($maKH);
    $tongTienFinal = (isset($_POST['final_total'])) ? (float)$_POST['final_total'] : $ghMoi['TongTien'];
    $ghiChuKM = (isset($_POST['tenKM'])) ? "Khuyến mãi: " . $_POST['tenKM'] : "";
    $ghiChuFull = $_POST['ghiChu'] . " " . $ghiChuKM;
    if (isset($_POST['tenKM'])) {
        $tenKM = $_POST['tenKM'];
        $checkKM = $db->query("SELECT * FROM khuyenmai WHERE TenKM = ?", [$tenKM])->fetch();
        if ($checkKM) {
            preg_match_all('!\d+!', $checkKM['DieuKienApDung'], $matches);
            $val = isset($matches[0][0]) ? (int)$matches[0][0] : 0;
            $realCondition = (str_contains(strtolower($checkKM['DieuKienApDung']), 'k')) ? $val * 1000 : $val;
            if ($ghMoi['TongTien'] < $realCondition) {
                $tongTienFinal = $ghMoi['TongTien'];
                $ghiChuFull = $_POST['ghiChu'] . " (Mã KM $tenKM không hợp lệ)";
            }
        }
    }
    $sqlDH = "INSERT INTO donhang (MaKH, HoTenNguoiNhan, SDTNguoiNhan, DiaChiGiaoHang, GhiChu, NgayDat, TongTien, TrangThai) 
          VALUES (?, ?, ?, ?, ?, NOW(), ?, 'Chờ xử lý')";
    $db->query($sqlDH, [$maKH, $_POST['hoTen'], $_POST['sdt'], $_POST['diaChi'], $ghiChuFull, $tongTienFinal]);
    $maDH = $db->lastInsertId();
    foreach ($itemsCheck as $item) {
        $sqlCT = "INSERT INTO chitietdh (MaDH, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)";
        $db->query($sqlCT, [$maDH, $item['MaSP'], $item['SoLuong'], $item['DonGia']]);
    }
    $ghModel->xoa_gio_hang($ghMoi['MaGH']);
    header("Location: ../Views/Thanhtoan/thanhcong.php?madh=" . $maDH);
    exit();
}
