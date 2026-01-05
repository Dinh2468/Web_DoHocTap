<?php
// classes/Nhanvien.class.php
require_once 'DB.class.php';
class Nhanvien extends Db
{
    public function lay_tat_ca()
    {
        $sql = "SELECT n.*, t.TenDangNhap, t.Email as EmailTK, t.VaiTro 
                FROM nhanvien n
                JOIN taikhoan t ON n.MaTK = t.MaTK
                ORDER BY n.MaNV DESC";
        return $this->query($sql)->fetchAll();
    }
    public function lay_theo_id($maNV)
    {
        $sql = "SELECT n.*, t.TenDangNhap, t.Email as EmailTK 
                FROM nhanvien n
                JOIN taikhoan t ON n.MaTK = t.MaTK
                WHERE n.MaNV = ?";
        return $this->query($sql, [$maNV])->fetch();
    }
    public function them_moi($data)
    {
        try {
            $this->pdo->beginTransaction();
            $sqlTK = "INSERT INTO taikhoan (TenDangNhap, MatKhau, Email, VaiTro) VALUES (?, ?, ?, ?)";
            $this->query($sqlTK, [$data['username'], $data['password'], $data['email'], $data['vaitro']]);
            $maTK = $this->pdo->lastInsertId();
            $sqlNV = "INSERT INTO nhanvien (HoTen, GioiTinh, NgaySinh, SDT, DiaChi, MaTK) VALUES (?, ?, ?, ?, ?, ?)";
            $this->query($sqlNV, [
                $data['hoten'],
                $data['gioitinh'],
                $data['ngaysinh'],
                $data['sdt'],
                $data['diachi'],
                $maTK
            ]);
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    public function cap_nhat($maNV, $data)
    {
        $sql = "UPDATE nhanvien SET HoTen = ?, GioiTinh = ?, NgaySinh = ?, SDT = ?, DiaChi = ? WHERE MaNV = ?";
        return $this->query($sql, [
            $data['hoten'],
            $data['gioitinh'],
            $data['ngaysinh'],
            $data['sdt'],
            $data['diachi'],
            $maNV
        ]);
    }
    public function xoa($maNV)
    {
        $nv = $this->lay_theo_id($maNV);
        if ($nv) {
            $sqlNV = "DELETE FROM nhanvien WHERE MaNV = ?";
            $this->query($sqlNV, [$maNV]);
            $sqlTK = "DELETE FROM taikhoan WHERE MaTK = ?";
            return $this->query($sqlTK, [$nv['MaTK']]);
        }
        return false;
    }
}
