<?php
// vị trí: classes/Donhang.class.php
require_once 'DB.class.php';

class Donhang extends Db
{

    /**
     * Tiến hành thanh toán và tạo đơn hàng mới
     * Phục vụ chức năng: Đặt hàng [cite: 31]
     */
    public function tao_don_hang($data)
    {
        $sql = "INSERT INTO donhang (MaKH, HoTenNguoiNhan, SDTNguoiNhan, NgayDat, TrangThai, TongTien, DiaChiGiaoHang) 
                VALUES (?, ?, ?, NOW(), 'Đang xử lý', ?, ?)";
        $params = [
            $data['MaKH'],
            $data['HoTenNguoiNhan'],
            $data['SDTNguoiNhan'],
            $data['TongTien'],
            $data['DiaChiGiaoHang']
        ];
        $this->query($sql, $params);
        return $this->pdo->lastInsertId(); // Trả về mã đơn hàng vừa tạo để dùng cho bảng chi tiết 
    }

    /**
     * Xác nhận đơn hàng và cập nhật trạng thái
     * Phục vụ chức năng: Xác nhận đơn hàng, Cập nhật trạng thái giao hàng 
     */
    public function cap_nhat_trang_thai($maDH, $trangThaiMoi)
    {
        $sql = "UPDATE donhang SET TrangThai = ? WHERE MaDH = ?";
        return $this->query($sql, [$trangThaiMoi, $maDH]);
    }

    /**
     * Xem danh sách đơn hàng của một khách hàng cụ thể
     * Phục vụ chức năng: Xem trạng thái đơn hàng [cite: 35]
     */
    public function lay_don_hang_khach_hang($maKH)
    {
        $sql = "SELECT * FROM donhang WHERE MaKH = ? ORDER BY NgayDat DESC";
        return $this->query($sql, [$maKH])->fetchAll();
    }

    /**
     * Lấy toàn bộ đơn hàng (Dành cho Admin/Nhân viên quản lý)
     * Phục vụ chức năng: Thống kê đơn hàng [cite: 36]
     */
    public function lay_tat_ca()
    {
        $sql = "SELECT d.*, k.HoTen 
                FROM donhang d
                JOIN khachhang k ON d.MaKH = k.MaKH
                ORDER BY d.NgayDat DESC";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Thống kê doanh thu theo tháng/năm
     * Phục vụ chức năng: Xem thống kê doanh thu 
     */
    public function thong_ke_doanh_thu($thang, $nam)
    {
        $sql = "SELECT SUM(TongTien) as DoanhThu 
                FROM donhang 
                WHERE MONTH(NgayDat) = ? AND YEAR(NgayDat) = ? AND TrangThai = 'Hoàn tất'";
        $result = $this->query($sql, [$thang, $nam])->fetch();
        return $result['DoanhThu'] ?? 0;
    }

    /**
     * Hủy đơn hàng (Dành cho khách hàng hoặc nhân viên)
     * Phục vụ chức năng: Hủy đơn hàng [cite: 36]
     */
    public function huy_don_hang($maDH)
    {
        $sql = "UPDATE donhang SET TrangThai = 'Đã hủy' WHERE MaDH = ?";
        return $this->query($sql, [$maDH]);
    }
}
