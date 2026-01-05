<?php
// classes/Giohang.class.php
require_once 'DB.class.php';
class Giohang extends Db
{
    public function lay_theo_khach_hang($maKH)
    {
        $sql = "SELECT * FROM giohang WHERE MaKH = ?";
        return $this->query($sql, [$maKH])->fetch();
    }
    public function tao_moi($maKH)
    {
        $sql = "INSERT INTO giohang (MaKH, NgayCapNhat, TongTien) VALUES (?, NOW(), 0)";
        $this->query($sql, [$maKH]);
        return $this->pdo->lastInsertId();
    }
    public function cap_nhat_gio_hang($maGH, $tongTien)
    {
        $sql = "UPDATE giohang SET NgayCapNhat = NOW(), TongTien = ? WHERE MaGH = ?";
        return $this->query($sql, [$tongTien, $maGH]);
    }
    public function xoa_gio_hang($maGH)
    {
        $this->query("DELETE FROM chitietgh WHERE MaGH = ?", [$maGH]);
        $sql = "DELETE FROM giohang WHERE MaGH = ?";
        return $this->query($sql, [$maGH]);
    }
    public function tinh_lai_tong_tien($maGH)
    {
        $sql = "SELECT SUM(SoLuong * DonGia) as Tong FROM chitietgh WHERE MaGH = ?";
        $result = $this->query($sql, [$maGH])->fetch();
        $moi = $result['Tong'] ?? 0;
        $this->cap_nhat_gio_hang($maGH, $moi);
        return $moi;
    }
}
