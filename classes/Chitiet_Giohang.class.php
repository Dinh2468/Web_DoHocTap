<?php
// vị trí: classes/Chitiet_Giohang.class.php
require_once 'DB.class.php';

class Chitiet_Giohang extends Db
{


    public function them_san_pham($maGH, $maSP, $soLuong, $donGia)
    {
        // Kiểm tra xem sản phẩm đã có trong giỏ chưa
        $sqlCheck = "SELECT SoLuong FROM chitietgh WHERE MaGH = ? AND MaSP = ?";
        $tonTai = $this->query($sqlCheck, [$maGH, $maSP])->fetch();

        if ($tonTai) {

            $sql = "UPDATE chitietgh SET SoLuong = SoLuong + ? WHERE MaGH = ? AND MaSP = ?";
            return $this->query($sql, [$soLuong, $maGH, $maSP]);
        } else {

            $sql = "INSERT INTO chitietgh (MaGH, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)";
            return $this->query($sql, [$maGH, $maSP, $soLuong, $donGia]);
        }
    }


    public function cap_nhat_so_luong($maGH, $maSP, $soLuongMoi)
    {
        $sql = "UPDATE chitietgh SET SoLuong = ? WHERE MaGH = ? AND MaSP = ?";
        return $this->query($sql, [$soLuongMoi, $maGH, $maSP]);
    }


    public function xoa_san_pham($maGH, $maSP)
    {
        $sql = "DELETE FROM chitietgh WHERE MaGH = ? AND MaSP = ?";
        return $this->query($sql, [$maGH, $maSP]);
    }

    public function lay_danh_sach_trong_gio($maGH)
    {
        $sql = "SELECT ct.*, sp.TenSP, sp.HinhAnh, sp.SoLuongTon 
            FROM chitietgh ct 
            JOIN sanpham sp ON ct.MaSP = sp.MaSP 
            WHERE ct.MaGH = ?";
        return $this->query($sql, [$maGH])->fetchAll();
    }
}
