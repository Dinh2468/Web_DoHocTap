<?php
// classes/Taikhoan.class.php
require_once 'DB.class.php';
class Taikhoan extends Db
{
    public function dang_nhap($tenDangNhap, $matKhau)
    {
        $sql = "SELECT * FROM taikhoan WHERE TenDangNhap = ? AND MatKhau = ?";
        return $this->query($sql, [$tenDangNhap, $matKhau])->fetch();
    }
    public function dang_ky_khach_hang($data)
    {
        try {
            $this->pdo->beginTransaction();
            $sqlTK = "INSERT INTO taikhoan (TenDangNhap, MatKhau, Email, VaiTro) VALUES (?, ?, ?, 'Khách hàng')";
            $this->query($sqlTK, [$data['username'], $data['password'], $data['email']]);
            $maTK = $this->pdo->lastInsertId();
            $sqlKH = "INSERT INTO khachhang (HoTen, SDT, DiaChi, Email, MaTK) VALUES (?, ?, ?, ?, ?)";
            $this->query($sqlKH, [$data['hoten'], $data['sdt'], $data['diachi'], $data['email'], $maTK]);
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    public function lay_thong_tin_chi_tiet($maTK, $vaiTro)
    {
        if ($vaiTro == 'Khách hàng') {
            $sql = "SELECT * FROM khachhang WHERE MaTK = ?";
        } else {
            $sql = "SELECT * FROM nhanvien WHERE MaTK = ?";
        }
        return $this->query($sql, [$maTK])->fetch();
    }
    public function cap_nhat_vai_tro($maTK, $vaiTroMoi)
    {
        $sql = "UPDATE taikhoan SET VaiTro = ? WHERE MaTK = ?";
        return $this->query($sql, [$vaiTroMoi, $maTK]);
    }
    public function cap_nhat_thong_tin_kh($maKH, $hoTen, $sdt, $diaChi)
    {
        $sql = "UPDATE khachhang SET HoTen = ?, SDT = ?, DiaChi = ? WHERE MaKH = ?";
        return $this->query($sql, [$hoTen, $sdt, $diaChi, $maKH]);
    }
}
