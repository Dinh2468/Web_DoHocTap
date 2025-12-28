<?php
// vị trí: classes/Chitiet_Nhaphang.class.php
require_once 'DB.class.php';

class Chitiet_Nhaphang extends Db
{

    /**
     * Ghi số lượng và giá nhập cho từng sản phẩm trong một phiếu nhập
     * Phục vụ chức năng: Quản lý chi tiết nhập hàng [cite: 32]
     */
    public function them_chi_tiet($maNH, $maSP, $soLuong, $donGia)
    {
        $sql = "INSERT INTO chitietnh (MaNH, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)";
        return $this->query($sql, [$maNH, $maSP, $soLuong, $donGia]);
    }

    /**
     * Lấy danh sách các sản phẩm thuộc một phiếu nhập cụ thể
     * Kết hợp bảng sanpham để lấy tên sản phẩm hiển thị lên giao diện [cite: 45]
     */
    public function lay_theo_ma_nhap($maNH)
    {
        $sql = "SELECT c.*, s.TenSP 
                FROM chitietnh c
                JOIN sanpham s ON c.MaSP = s.MaSP
                WHERE c.MaNH = ?";
        return $this->query($sql, [$maNH])->fetchAll();
    }

    /**
     * Xóa toàn bộ chi tiết của một phiếu nhập (Dùng khi hủy phiếu nhập hàng)
     */
    public function xoa_theo_ma_nhap($maNH)
    {
        $sql = "DELETE FROM chitietnh WHERE MaNH = ?";
        return $this->query($sql, [$maNH]);
    }

    /**
     * Tính tổng số lượng sản phẩm đã nhập về theo từng mã sản phẩm
     * Phục vụ chức năng: Báo cáo nhập hàng 
     */
    public function thong_ke_so_luong_nhap($maSP)
    {
        $sql = "SELECT SUM(SoLuong) as TongNhap FROM chitietnh WHERE MaSP = ?";
        $result = $this->query($sql, [$maSP])->fetch();
        return $result['TongNhap'] ?? 0;
    }
}
