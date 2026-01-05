<?php
// classes/Chitiet_Nhaphang.class.php
require_once 'DB.class.php';
class Chitiet_Nhaphang extends Db
{
    public function them_chi_tiet($maNH, $maSP, $soLuong, $donGia)
    {
        $sql = "INSERT INTO chitietnh (MaNH, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)";
        return $this->query($sql, [$maNH, $maSP, $soLuong, $donGia]);
    }
    public function lay_theo_ma_nhap($maNH)
    {
        $sql = "SELECT c.*, s.TenSP 
                FROM chitietnh c
                JOIN sanpham s ON c.MaSP = s.MaSP
                WHERE c.MaNH = ?";
        return $this->query($sql, [$maNH])->fetchAll();
    }
    public function xoa_theo_ma_nhap($maNH)
    {
        $sql = "DELETE FROM chitietnh WHERE MaNH = ?";
        return $this->query($sql, [$maNH]);
    }
    public function thong_ke_so_luong_nhap($maSP)
    {
        $sql = "SELECT SUM(SoLuong) as TongNhap FROM chitietnh WHERE MaSP = ?";
        $result = $this->query($sql, [$maSP])->fetch();
        return $result['TongNhap'] ?? 0;
    }
}
