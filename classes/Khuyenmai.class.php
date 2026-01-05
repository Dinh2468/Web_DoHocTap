<?php
// classes/Khuyenmai.class.php
require_once 'DB.class.php';
class Khuyenmai extends Db
{
    public function lay_tat_ca()
    {
        $sql = "SELECT * FROM khuyenmai ORDER BY NgayBatDau DESC";
        return $this->query($sql)->fetchAll();
    }
    public function lay_khuyen_mai_hien_tai()
    {
        $sql = "SELECT * FROM khuyenmai WHERE CURDATE() BETWEEN NgayBatDau AND NgayKetThuc";
        return $this->query($sql)->fetchAll();
    }
    public function lay_giam_gia_theo_sp($maSP)
    {
        $sql = "SELECT k.PhanTramGiam 
                FROM khuyenmai k
                JOIN sp_km sk ON k.MaKM = sk.MaKM
                WHERE sk.MaSP = ? AND CURDATE() BETWEEN k.NgayBatDau AND k.NgayKetThuc
                ORDER BY k.PhanTramGiam DESC LIMIT 1";
        $result = $this->query($sql, [$maSP])->fetch();
        return $result ? $result['PhanTramGiam'] : 0;
    }
    public function them_moi($tenKM, $ngayBD, $ngayKT, $phanTram, $dieuKien)
    {
        $sql = "INSERT INTO khuyenmai (TenKM, NgayBatDau, NgayKetThuc, PhanTramGiam, DieuKienApDung) 
                VALUES (?, ?, ?, ?, ?)";
        return $this->query($sql, [$tenKM, $ngayBD, $ngayKT, $phanTram, $dieuKien]);
    }
    public function ap_dung_cho_san_pham($maSP, $maKM)
    {
        $sql = "INSERT INTO sp_km (MaSP, MaKM) VALUES (?, ?)";
        return $this->query($sql, [$maSP, $maKM]);
    }
    public function xoa($maKM)
    {
        $this->query("DELETE FROM sp_km WHERE MaKM = ?", [$maKM]);
        $sql = "DELETE FROM khuyenmai WHERE MaKM = ?";
        return $this->query($sql, [$maKM]);
    }
}
