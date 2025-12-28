<?php
// vị trí: classes/Danhgia.class.php
require_once 'DB.class.php';

class Danhgia extends Db
{

    /**
     * Gửi nhận xét và số sao đánh giá cho sản phẩm
     * Phục vụ chức năng: Tương tác - Đánh giá sản phẩm
     */
    public function gui_danh_gia($maKH, $maSP, $soSao, $noiDung)
    {
        $sql = "INSERT INTO danhgia (MaKH, MaSP, SoSao, NoiDung, NgayDG) 
                VALUES (?, ?, ?, ?, CURDATE())";
        return $this->query($sql, [$maKH, $maSP, $soSao, $noiDung]);
    }

    /**
     * Lấy tất cả đánh giá của một sản phẩm cụ thể
     * Kết hợp với bảng khách hàng để hiển thị tên người đánh giá
     */
    public function lay_theo_san_pham($maSP)
    {
        $sql = "SELECT d.*, k.HoTen 
                FROM danhgia d
                JOIN khachhang k ON d.MaKH = k.MaKH
                WHERE d.MaSP = ?
                ORDER BY d.NgayDG DESC";
        return $this->query($sql, [$maSP])->fetchAll();
    }

    /**
     * Tính điểm trung bình số sao của một sản phẩm
     * Dùng để hiển thị ở trang chi tiết sản phẩm hoặc danh sách sản phẩm
     */
    public function tinh_sao_trung_binh($maSP)
    {
        $sql = "SELECT AVG(SoSao) as TrungBinh FROM danhgia WHERE MaSP = ?";
        $result = $this->query($sql, [$maSP])->fetch();
        return $result['TrungBinh'] ? round($result['TrungBinh'], 1) : 0;
    }

    /**
     * Đếm tổng số lượt đánh giá của một sản phẩm
     */
    public function dem_luot_danh_gia($maSP)
    {
        $sql = "SELECT COUNT(*) as Tong FROM danhgia WHERE MaSP = ?";
        $result = $this->query($sql, [$maSP])->fetch();
        return $result['Tong'] ?? 0;
    }

    /**
     * Xóa đánh giá (Dành cho Admin quản lý nội dung không phù hợp)
     */
    public function xoa_danh_gia($maDG)
    {
        $sql = "DELETE FROM danhgia WHERE MaDG = ?";
        return $this->query($sql, [$maDG]);
    }
}
