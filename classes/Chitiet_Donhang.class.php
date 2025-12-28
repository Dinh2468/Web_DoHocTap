<?php
// vị trí: classes/Chitiet_Donhang.class.php
require_once 'DB.class.php';

class Chitiet_Donhang extends Db
{

    /**
     * Lưu chi tiết các sản phẩm trong đơn hàng
     * Phục vụ chức năng: Đặt hàng
     */
    public function them_chi_tiet($maDH, $maSP, $soLuong, $donGia)
    {
        $sql = "INSERT INTO chitietdh (MaDH, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)";
        return $this->query($sql, [$maDH, $maSP, $soLuong, $donGia]);
    }

    /**
     * Lấy danh sách sản phẩm của một đơn hàng cụ thể
     * Kết hợp với bảng sanpham để lấy tên và hình ảnh hiển thị
     */
    public function lay_theo_ma_don_hang($maDH)
    {
        $sql = "SELECT c.*, s.TenSP, s.HinhAnh 
                FROM chitietdh c
                JOIN sanpham s ON c.MaSP = s.MaSP
                WHERE c.MaDH = ?";
        return $this->query($sql, [$maDH])->fetchAll();
    }

    /**
     * Thống kê các sản phẩm bán chạy nhất
     * Phục vụ chức năng: Thống kê theo sản phẩm
     */
    public function san_pham_ban_chay($limit = 5)
    {
        $sql = "SELECT s.TenSP, SUM(c.SoLuong) as TongSoLuong
                FROM chitietdh c
                JOIN sanpham s ON c.MaSP = s.MaSP
                GROUP BY c.MaSP
                ORDER BY TongSoLuong DESC
                LIMIT ?";
        return $this->query($sql, [$limit])->fetchAll();
    }

    /**
     * Xóa chi tiết đơn hàng (Dùng khi hủy hoặc xóa đơn hàng)
     */
    public function xoa_theo_ma_don_hang($maDH)
    {
        $sql = "DELETE FROM chitietdh WHERE MaDH = ?";
        return $this->query($sql, [$maDH]);
    }
}
