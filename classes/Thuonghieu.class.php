<?php
// classes/Thuonghieu.class.php
require_once 'DB.class.php';
class Thuonghieu extends Db
{
    public function lay_tat_ca()
    {
        $sql = "SELECT * FROM thuonghieu ORDER BY TenTH ASC";
        return $this->query($sql)->fetchAll();
    }
    public function lay_theo_id($maTH)
    {
        $sql = "SELECT * FROM thuonghieu WHERE MaTH = ?";
        return $this->query($sql, [$maTH])->fetch();
    }
    public function them_moi($tenTH, $quocGia, $moTa)
    {
        $sql = "INSERT INTO thuonghieu (TenTH, QuocGia, MoTa) VALUES (?, ?, ?)";
        return $this->query($sql, [$tenTH, $quocGia, $moTa]);
    }
    public function cap_nhat($maTH, $tenTH, $quocGia, $moTa)
    {
        $sql = "UPDATE thuonghieu SET TenTH = ?, QuocGia = ?, MoTa = ? WHERE MaTH = ?";
        return $this->query($sql, [$tenTH, $quocGia, $moTa, $maTH]);
    }
    public function xoa($maTH)
    {
        $sql = "DELETE FROM thuonghieu WHERE MaTH = ?";
        return $this->query($sql, [$maTH]);
    }
    public function lay_san_pham_theo_thuong_hieu($maTH)
    {
        $sql = "SELECT * FROM sanpham WHERE MaTH = ?";
        return $this->query($sql, [$maTH])->fetchAll();
    }
}
