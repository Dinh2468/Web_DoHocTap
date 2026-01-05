<?php
// classes/Nhacungcap.class.php
require_once 'DB.class.php';
class Nhacungcap extends Db
{
    public function lay_tat_ca()
    {
        $sql = "SELECT * FROM nhacungcap ORDER BY MaNCC DESC";
        return $this->query($sql)->fetchAll();
    }
    public function lay_theo_id($maNCC)
    {
        $sql = "SELECT * FROM nhacungcap WHERE MaNCC = ?";
        return $this->query($sql, [$maNCC])->fetch();
    }
    public function them_moi($tenNCC, $diaChi, $sdt, $email)
    {
        $sql = "INSERT INTO nhacungcap (TenNCC, DiaChi, SDT, Email) VALUES (?, ?, ?, ?)";
        return $this->query($sql, [$tenNCC, $diaChi, $sdt, $email]);
    }
    public function cap_nhat($maNCC, $tenNCC, $diaChi, $sdt, $email)
    {
        $sql = "UPDATE nhacungcap SET TenNCC = ?, DiaChi = ?, SDT = ?, Email = ? WHERE MaNCC = ?";
        return $this->query($sql, [$tenNCC, $diaChi, $sdt, $email, $maNCC]);
    }
    public function xoa($maNCC)
    {
        $sql = "DELETE FROM nhacungcap WHERE MaNCC = ?";
        return $this->query($sql, [$maNCC]);
    }
    public function lay_san_pham_theo_ncc($maNCC)
    {
        $sql = "SELECT * FROM sanpham WHERE MaNCC = ?";
        return $this->query($sql, [$maNCC])->fetchAll();
    }
}
