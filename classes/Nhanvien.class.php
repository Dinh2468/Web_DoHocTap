<?php
// vị trí: classes/Nhanvien.class.php
require_once 'DB.class.php';

class Nhanvien extends Db {

    /**
     * Lấy danh sách tất cả nhân viên kèm theo thông tin tài khoản
     * Phục vụ chức năng: Quản lý nhân viên của Admin
     */
    public function lay_tat_ca() {
        $sql = "SELECT n.*, t.TenDangNhap, t.Email as EmailTK, t.VaiTro 
                FROM nhanvien n
                JOIN taikhoan t ON n.MaTK = t.MaTK
                ORDER BY n.MaNV DESC";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Lấy thông tin chi tiết của một nhân viên theo mã
     */
    public function lay_theo_id($maNV) {
        $sql = "SELECT n.*, t.TenDangNhap, t.Email as EmailTK 
                FROM nhanvien n
                JOIN taikhoan t ON n.MaTK = t.MaTK
                WHERE n.MaNV = ?";
        return $this->query($sql, [$maNV])->fetch();
    }

    /**
     * Thêm nhân viên mới (bao gồm tạo tài khoản hệ thống)
     * Phục vụ chức năng: Thêm nhân viên
     */
    public function them_moi($data) {
        try {
            $this->pdo->beginTransaction();

            // 1. Tạo tài khoản cho nhân viên trước
            $sqlTK = "INSERT INTO taikhoan (TenDangNhap, MatKhau, Email, VaiTro) VALUES (?, ?, ?, ?)";
            $this->query($sqlTK, [$data['username'], $data['password'], $data['email'], $data['vaitro']]);
            $maTK = $this->pdo->lastInsertId();

            // 2. Lưu thông tin cá nhân vào bảng nhanvien
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

    /**
     * Cập nhật thông tin nhân viên
     * Phục vụ chức năng: Sửa thông tin nhân viên
     */
    public function cap_nhat($maNV, $data) {
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

    /**
     * Xóa nhân viên (Admin xóa nhân viên khỏi hệ thống)
     */
    public function xoa($maNV) {
        // Cần lấy MaTK để xóa tài khoản trước khi xóa nhân viên hoặc dùng ON DELETE CASCADE
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