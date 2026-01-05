<?php
// classes/Nhaphang.class.php
require_once 'DB.class.php';
class Nhaphang extends Db
{
    public function tao_phieu_nhap($maNV, $maNCC, $tongTien)
    {
        $sql = "INSERT INTO nhaphang (MaNV, MaNCC, NgayNhap, TongTien) VALUES (?, ?, NOW(), ?)";
        $this->query($sql, [$maNV, $maNCC, $tongTien]);
        return $this->pdo->lastInsertId();
    }
    public function luu_chi_tiet($maNH, $maSP, $soLuong, $donGia)
    {
        $sql = "INSERT INTO chitietnh (MaNH, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)";
        return $this->query($sql, [$maNH, $maSP, $soLuong, $donGia]);
    }
    public function lay_tat_ca_phieu_nhap()
    {
        $sql = "SELECT n.*, nv.HoTen as TenNhanVien, ncc.TenNCC 
                FROM nhaphang n
                JOIN nhanvien nv ON n.MaNV = nv.MaNV
                JOIN nhacungcap ncc ON n.MaNCC = ncc.MaNCC
                ORDER BY n.NgayNhap DESC";
        return $this->query($sql)->fetchAll();
    }
    public function lay_chi_tiet_theo_id($maNH)
    {
        $sql = "SELECT c.*, s.TenSP 
                FROM chitietnh c
                JOIN sanpham s ON c.MaSP = s.MaSP
                WHERE c.MaNH = ?";
        return $this->query($sql, [$maNH])->fetchAll();
    }
    public function thong_ke_nhap_hang($thang, $nam)
    {
        $sql = "SELECT SUM(TongTien) as TongChiPhi FROM nhaphang 
                WHERE MONTH(NgayNhap) = ? AND YEAR(NgayNhap) = ?";
        $result = $this->query($sql, [$thang, $nam])->fetch();
        return $result['TongChiPhi'] ?? 0;
    }
}
