<?php
// vị trí: classes/Taikhoan.class.php
require_once 'DB.class.php';

class Taikhoan extends Db
{

    /**
     * Xác thực thông tin để vào hệ thống
     * Phục vụ chức năng: Đăng nhập hệ thống 
     */
    public function dang_nhap($tenDangNhap, $matKhau)
    {
        // Trong thực tế nên dùng password_verify, ở đây dùng so khớp trực tiếp theo dữ liệu mẫu [cite: 45]
        $sql = "SELECT * FROM taikhoan WHERE TenDangNhap = ? AND MatKhau = ?";
        return $this->query($sql, [$tenDangNhap, $matKhau])->fetch();
    }

    /**
     * Nhập thông tin cá nhân để tạo tài khoản mới
     * Phục vụ chức năng: Đăng ký tài khoản khách hàng 
     */
    public function dang_ky_khach_hang($data)
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Thêm vào bảng taikhoan
            $sqlTK = "INSERT INTO taikhoan (TenDangNhap, MatKhau, Email, VaiTro) VALUES (?, ?, ?, 'Khách hàng')";
            $this->query($sqlTK, [$data['username'], $data['password'], $data['email']]);
            $maTK = $this->pdo->lastInsertId();

            // 2. Thêm vào bảng khachhang liên kết với MaTK 
            $sqlKH = "INSERT INTO khachhang (HoTen, SDT, DiaChi, Email, MaTK) VALUES (?, ?, ?, ?, ?)";
            $this->query($sqlKH, [$data['hoten'], $data['sdt'], $data['diachi'], $data['email'], $maTK]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    /**
     * Lấy thông tin chi tiết người dùng tùy theo vai trò
     */
    public function lay_thong_tin_chi_tiet($maTK, $vaiTro)
    {
        if ($vaiTro == 'Khách hàng') {
            $sql = "SELECT * FROM khachhang WHERE MaTK = ?";
        } else {
            $sql = "SELECT * FROM nhanvien WHERE MaTK = ?";
        }
        return $this->query($sql, [$maTK])->fetch();
    }

    /**
     * Khóa, mở hoặc chỉnh quyền tài khoản
     * Phục vụ chức năng: Quản lý tài khoản người dùng của Admin [cite: 33]
     */
    public function cap_nhat_vai_tro($maTK, $vaiTroMoi)
    {
        $sql = "UPDATE taikhoan SET VaiTro = ? WHERE MaTK = ?";
        return $this->query($sql, [$vaiTroMoi, $maTK]);
    }

    /**
     * Sửa thông tin như họ tên, địa chỉ, số điện thoại
     * Phục vụ chức năng: Cập nhật thông tin cá nhân 
     */
    public function cap_nhat_thong_tin_kh($maKH, $hoTen, $sdt, $diaChi)
    {
        $sql = "UPDATE khachhang SET HoTen = ?, SDT = ?, DiaChi = ? WHERE MaKH = ?";
        return $this->query($sql, [$hoTen, $sdt, $diaChi, $maKH]);
    }
}
