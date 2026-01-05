<?php
// classes/Chitiet_Donhang.class.php
require_once 'DB.class.php';
class Chitiet_Donhang extends Db
{
    public function them_chi_tiet($maDH, $maSP, $soLuong, $donGia)
    {
        $sql = "INSERT INTO chitietdh (MaDH, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)";
        return $this->query($sql, [$maDH, $maSP, $soLuong, $donGia]);
    }
    public function lay_theo_ma_don_hang($maDH)
    {
        $sql = "SELECT c.*, s.TenSP, s.HinhAnh 
                FROM chitietdh c
                JOIN sanpham s ON c.MaSP = s.MaSP
                WHERE c.MaDH = ?";
        return $this->query($sql, [$maDH])->fetchAll();
    }
    public function san_pham_ban_chay($limit = 5)
    {
        $sql = "SELECT s.TenSP, SUM(c.SoLuong) as TongSoLuong
                FROM chitietdh c
                JOIN sanpham s ON c.MaSP = s.MaSP
                GROUP BY c.MaSP
                ORDER BY TongSoLuong DESC
                LIMIT ?";
        return $this->query($sql, [$limit])->fetchAll();
    }
    public function xoa_theo_ma_don_hang($maDH)
    {
        $sql = "DELETE FROM chitietdh WHERE MaDH = ?";
        return $this->query($sql, [$maDH]);
    }
}
