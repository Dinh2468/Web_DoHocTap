<?php
// vị trí: classes/Nhacungcap.class.php
require_once 'DB.class.php';

class Nhacungcap extends Db
{

    /**
     * Lấy danh sách tất cả các nhà cung cấp dụng cụ học tập
     * Phục vụ chức năng: Quản lý nhà cung cấp của Quản trị viên
     */
    public function lay_tat_ca()
    {
        $sql = "SELECT * FROM nhacungcap ORDER BY MaNCC DESC";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Lấy thông tin chi tiết một nhà cung cấp theo mã
     */
    public function lay_theo_id($maNCC)
    {
        $sql = "SELECT * FROM nhacungcap WHERE MaNCC = ?";
        return $this->query($sql, [$maNCC])->fetch();
    }

    /**
     * Thêm nhà cung cấp mới vào hệ thống
     */
    public function them_moi($tenNCC, $diaChi, $sdt, $email)
    {
        $sql = "INSERT INTO nhacungcap (TenNCC, DiaChi, SDT, Email) VALUES (?, ?, ?, ?)";
        return $this->query($sql, [$tenNCC, $diaChi, $sdt, $email]);
    }

    /**
     * Cập nhật thông tin các nhà cung cấp sản phẩm
     * Phục vụ chức năng: Cập nhật thông tin nhà cung cấp
     */
    public function cap_nhat($maNCC, $tenNCC, $diaChi, $sdt, $email)
    {
        $sql = "UPDATE nhacungcap SET TenNCC = ?, DiaChi = ?, SDT = ?, Email = ? WHERE MaNCC = ?";
        return $this->query($sql, [$tenNCC, $diaChi, $sdt, $email, $maNCC]);
    }

    /**
     * Xóa nhà cung cấp
     * Lưu ý: Chỉ xóa được nếu nhà cung cấp này chưa từng có phiếu nhập hàng (ràng buộc khóa ngoại)
     */
    public function xoa($maNCC)
    {
        $sql = "DELETE FROM nhacungcap WHERE MaNCC = ?";
        return $this->query($sql, [$maNCC]);
    }

    /**
     * Lấy danh sách sản phẩm thuộc một nhà cung cấp cụ thể
     */
    public function lay_san_pham_theo_ncc($maNCC)
    {
        $sql = "SELECT * FROM sanpham WHERE MaNCC = ?";
        return $this->query($sql, [$maNCC])->fetchAll();
    }
}
