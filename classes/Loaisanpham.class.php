<?php
// classes/Loaisanpham.class.php
require_once 'DB.class.php';
class Loaisanpham extends Db
{
    public function lay_tat_ca()
    {
        $sql = "SELECT * FROM loaisp ORDER BY MaLoai ASC";
        return $this->query($sql)->fetchAll();
    }
    public function lay_theo_id($maLoai)
    {
        $sql = "SELECT * FROM loaisp WHERE MaLoai = ?";
        return $this->query($sql, [$maLoai])->fetch();
    }
    public function them_moi($tenLoai, $moTa)
    {
        $sql = "INSERT INTO loaisp (TenLoai, MoTa) VALUES (?, ?)";
        return $this->query($sql, [$tenLoai, $moTa]);
    }
    public function cap_nhat($maLoai, $tenLoai, $moTa)
    {
        $sql = "UPDATE loaisp SET TenLoai = ?, MoTa = ? WHERE MaLoai = ?";
        return $this->query($sql, [$tenLoai, $moTa, $maLoai]);
    }
    public function xoa($maLoai)
    {
        $sql = "DELETE FROM loaisp WHERE MaLoai = ?";
        return $this->query($sql, [$maLoai]);
    }
    public function dem_san_pham($maLoai)
    {
        $sql = "SELECT COUNT(*) as tong FROM sanpham WHERE MaLoai = ?";
        $result = $this->query($sql, [$maLoai])->fetch();
        return $result['tong'];
    }
}
