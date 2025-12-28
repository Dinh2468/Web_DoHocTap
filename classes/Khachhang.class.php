<?php
// vị trí: classes/Khachhang.class.php
require_once 'DB.class.php';

class Khachhang extends Db
{

    /**
     * Lấy danh sách tất cả khách hàng
     * Phục vụ chức năng: Quản lý tài khoản người dùng của Admin
     */
    public function lay_tat_ca()
    {
        $sql = "SELECT k.*, t.TenDangNhap, t.VaiTro 
                FROM khachhang k
                JOIN taikhoan t ON k.MaTK = t.MaTK
                ORDER BY k.MaKH DESC";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Lấy thông tin chi tiết một khách hàng theo mã khách hàng
     */
    public function lay_theo_id($maKH)
    {
        $sql = "SELECT * FROM khachhang WHERE MaKH = ?";
        return $this->query($sql, [$maKH])->fetch();
    }

    /**
     * Lấy thông tin khách hàng dựa trên mã tài khoản (MaTK)
     * Dùng khi khách hàng đã đăng nhập và muốn xem trang cá nhân
     */
    public function lay_theo_ma_tk($maTK)
    {
        $sql = "SELECT * FROM khachhang WHERE MaTK = ?";
        return $this->query($sql, [$maTK])->fetch();
    }

    /**
     * Sửa thông tin như họ tên, địa chỉ, số điện thoại
     * Phục vụ chức năng: Cập nhật thông tin cá nhân của Khách hàng
     */
    public function cap_nhat_thong_tin($maKH, $hoTen, $sdt, $diaChi, $email)
    {
        $sql = "UPDATE khachhang SET HoTen = ?, SDT = ?, DiaChi = ?, Email = ? WHERE MaKH = ?";
        return $this->query($sql, [$hoTen, $sdt, $diaChi, $email, $maKH]);
    }

    /**
     * Tìm kiếm khách hàng theo tên hoặc số điện thoại
     */
    public function tim_kiem($tuKhoa)
    {
        $sql = "SELECT * FROM khachhang WHERE HoTen LIKE ? OR SDT LIKE ?";
        $param = "%$tuKhoa%";
        return $this->query($sql, [$param, $param])->fetchAll();
    }
}
