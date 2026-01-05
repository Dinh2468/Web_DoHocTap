<?php
// classes/Khachhang.class.php
require_once 'DB.class.php';
class Khachhang extends Db
{
    public function lay_tat_ca()
    {
        $sql = "SELECT k.*, t.TenDangNhap, t.VaiTro 
                FROM khachhang k
                JOIN taikhoan t ON k.MaTK = t.MaTK
                ORDER BY k.MaKH DESC";
        return $this->query($sql)->fetchAll();
    }
    public function lay_theo_id($maKH)
    {
        $sql = "SELECT * FROM khachhang WHERE MaKH = ?";
        return $this->query($sql, [$maKH])->fetch();
    }
    public function lay_theo_ma_tk($maTK)
    {
        $sql = "SELECT * FROM khachhang WHERE MaTK = ?";
        return $this->query($sql, [$maTK])->fetch();
    }
    public function cap_nhat_thong_tin($maKH, $hoTen, $sdt, $diaChi, $email)
    {
        $sql = "UPDATE khachhang SET HoTen = ?, SDT = ?, DiaChi = ?, Email = ? WHERE MaKH = ?";
        return $this->query($sql, [$hoTen, $sdt, $diaChi, $email, $maKH]);
    }
    public function tim_kiem($tuKhoa)
    {
        $sql = "SELECT * FROM khachhang WHERE HoTen LIKE ? OR SDT LIKE ?";
        $param = "%$tuKhoa%";
        return $this->query($sql, [$param, $param])->fetchAll();
    }
}
