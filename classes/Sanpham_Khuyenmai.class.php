<?php
// classes/Sanpham_Khuyenmai.class.php
require_once 'DB.class.php';
class Sanpham_Khuyenmai extends Db
{
    public function lay_tat_ca_sp_km()
    {
        $sql = "SELECT s.*, k.TenKM, k.PhanTramGiam, k.NgayKetThuc 
                FROM sp_km sk
                JOIN sanpham s ON sk.MaSP = s.MaSP
                JOIN khuyenmai k ON sk.MaKM = k.MaKM
                WHERE CURDATE() BETWEEN k.NgayBatDau AND k.NgayKetThuc";
        return $this->query($sql)->fetchAll();
    }
    public function ap_dung_km($maSP, $maKM)
    {
        $sql = "INSERT INTO sp_km (MaSP, MaKM) VALUES (?, ?)";
        return $this->query($sql, [$maSP, $maKM]);
    }
    public function huy_ap_dung($maSP, $maKM)
    {
        $sql = "DELETE FROM sp_km WHERE MaSP = ? AND MaKM = ?";
        return $this->query($sql, [$maSP, $maKM]);
    }
    public function kiem_tra_km_sp($maSP)
    {
        $sql = "SELECT k.* FROM khuyenmai k
                JOIN sp_km sk ON k.MaKM = sk.MaKM
                WHERE sk.MaSP = ? AND CURDATE() BETWEEN k.NgayBatDau AND k.NgayKetThuc";
        return $this->query($sql, [$maSP])->fetch();
    }
    public function lay_sp_theo_ma_km($maKM)
    {
        $sql = "SELECT s.* FROM sanpham s
                JOIN sp_km sk ON s.MaSP = sk.MaSP
                WHERE sk.MaKM = ?";
        return $this->query($sql, [$maKM])->fetchAll();
    }
}
