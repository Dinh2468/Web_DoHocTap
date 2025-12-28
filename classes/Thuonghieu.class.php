<?php
// vị trí: classes/Thuonghieu.class.php
require_once 'DB.class.php';

class Thuonghieu extends Db
{

    /**
     * Lấy danh sách tất cả các thương hiệu dụng cụ học tập
     * Phục vụ chức năng: Hiển thị danh sách thương hiệu để khách hàng lọc sản phẩm
     */
    public function lay_tat_ca()
    {
        $sql = "SELECT * FROM thuonghieu ORDER BY TenTH ASC";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Lấy thông tin chi tiết của một thương hiệu cụ thể
     */
    public function lay_theo_id($maTH)
    {
        $sql = "SELECT * FROM thuonghieu WHERE MaTH = ?";
        return $this->query($sql, [$maTH])->fetch();
    }

    /**
     * Thêm thương hiệu mới
     * Dành cho quản trị viên (Admin) cập nhật hệ thống
     */
    public function them_moi($tenTH, $quocGia, $moTa)
    {
        $sql = "INSERT INTO thuonghieu (TenTH, QuocGia, MoTa) VALUES (?, ?, ?)";
        return $this->query($sql, [$tenTH, $quocGia, $moTa]);
    }

    /**
     * Cập nhật thông tin thương hiệu
     */
    public function cap_nhat($maTH, $tenTH, $quocGia, $moTa)
    {
        $sql = "UPDATE thuonghieu SET TenTH = ?, QuocGia = ?, MoTa = ? WHERE MaTH = ?";
        return $this->query($sql, [$tenTH, $quocGia, $moTa, $maTH]);
    }

    /**
     * Xóa thương hiệu
     * Lưu ý: Hệ thống sẽ báo lỗi nếu có sản phẩm đang thuộc thương hiệu này
     */
    public function xoa($maTH)
    {
        $sql = "DELETE FROM thuonghieu WHERE MaTH = ?";
        return $this->query($sql, [$maTH]);
    }

    /**
     * Lấy danh sách sản phẩm thuộc một thương hiệu cụ thể
     */
    public function lay_san_pham_theo_thuong_hieu($maTH)
    {
        $sql = "SELECT * FROM sanpham WHERE MaTH = ?";
        return $this->query($sql, [$maTH])->fetchAll();
    }
}
