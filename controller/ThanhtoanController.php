<?php
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

    // 1. Tính tổng tiền lại một lần nữa cho chắc chắn
    $tongTien = 0;
    $items = [];
    if ($maKH) {
        $gioHang = $ghModel->lay_theo_khach_hang($maKH);
        $items = $ctghModel->lay_danh_sach_trong_gio($gioHang['MaGH']);
        $tongTien = $gioHang['TongTien'];
    }

    // 2. Lưu vào bảng donhang
    $sqlDH = "INSERT INTO donhang (MaKH, HoTenNguoiNhan, SDTNguoiNhan, DiaChiGiaoHang, GhiChu, NgayDat, TongTien, TrangThai) 
          VALUES (?, ?, ?, ?, ?, NOW(), ?, 'Chờ xử lý')";
    $db->query($sqlDH, [$maKH, $hoTen, $sdt, $diaChi, $ghiChu, $tongTien]);
    $maDH = $db->lastInsertId();

    // 3. Lưu chi tiết đơn hàng (chitietdh)
    foreach ($items as $item) {
        $sqlCT = "INSERT INTO chitietdh (MaDH, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)";
        $db->query($sqlCT, [$maDH, $item['MaSP'], $item['SoLuong'], $item['DonGia']]);
    }

    // 4. Xóa giỏ hàng sau khi đặt thành công
    if ($maKH) {
        $ghModel->xoa_gio_hang($gioHang['MaGH']);
    }
    header("Location: ../Views/Thanhtoan/thanhcong.php?madh=" . $maDH);
    exit();
}
