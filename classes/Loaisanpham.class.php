<?php
// vị trí: classes/Loaisanpham.class.php
require_once 'DB.class.php';

class Loaisanpham extends Db
{

    /**
     * Lấy danh sách tất cả các loại sản phẩm (Bút, Vở, Thước...)
     * Dùng để hiển thị menu danh mục trên Website
     */
    public function lay_tat_ca()
    {
        $sql = "SELECT * FROM loaisp ORDER BY MaLoai ASC";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Lấy thông tin chi tiết một loại sản phẩm theo ID
     */
    public function lay_theo_id($maLoai)
    {
        $sql = "SELECT * FROM loaisp WHERE MaLoai = ?";
        return $this->query($sql, [$maLoai])->fetch();
    }

    /**
     * Thêm loại sản phẩm mới
     * Dành cho chức năng quản trị của Admin
     */
    public function them_moi($tenLoai, $moTa)
    {
        $sql = "INSERT INTO loaisp (TenLoai, MoTa) VALUES (?, ?)";
        return $this->query($sql, [$tenLoai, $moTa]);
    }

    /**
     * Cập nhật thông tin loại sản phẩm
     */
    public function cap_nhat($maLoai, $tenLoai, $moTa)
    {
        $sql = "UPDATE loaisp SET TenLoai = ?, MoTa = ? WHERE MaLoai = ?";
        return $this->query($sql, [$tenLoai, $moTa, $maLoai]);
    }

    /**
     * Xóa loại sản phẩm
     * Lưu ý: Chỉ xóa được nếu không có sản phẩm nào thuộc loại này (ràng buộc khóa ngoại)
     */
    public function xoa($maLoai)
    {
        $sql = "DELETE FROM loaisp WHERE MaLoai = ?";
        return $this->query($sql, [$maLoai]);
    }

    /**
     * Đếm xem có bao nhiêu sản phẩm thuộc loại này
     * Giúp hiển thị số lượng cạnh tên danh mục (Ví dụ: Bút bi (20))
     */
    public function dem_san_pham($maLoai)
    {
        $sql = "SELECT COUNT(*) as tong FROM sanpham WHERE MaLoai = ?";
        $result = $this->query($sql, [$maLoai])->fetch();
        return $result['tong'];
    }
}
