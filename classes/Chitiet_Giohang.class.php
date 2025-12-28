<?php
// vị trí: classes/Chitiet_Giohang.class.php
require_once 'DB.class.php';

class Chitiet_Giohang extends Db
{

    /**
     * Lưu sản phẩm vào giỏ để mua sau
     * Phục vụ chức năng: Thêm sản phẩm vào giỏ hàng 
     */
    public function them_san_pham($maGH, $maSP, $soLuong, $donGia)
    {
        // Kiểm tra xem sản phẩm đã có trong giỏ chưa
        $sqlCheck = "SELECT SoLuong FROM chitietgh WHERE MaGH = ? AND MaSP = ?";
        $tonTai = $this->query($sqlCheck, [$maGH, $maSP])->fetch();

        if ($tonTai) {
            // Nếu đã có thì cập nhật tăng số lượng 
            $sql = "UPDATE chitietgh SET SoLuong = SoLuong + ? WHERE MaGH = ? AND MaSP = ?";
            return $this->query($sql, [$soLuong, $maGH, $maSP]);
        } else {
            // Nếu chưa có thì thêm mới 
            $sql = "INSERT INTO chitietgh (MaGH, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)";
            return $this->query($sql, [$maGH, $maSP, $soLuong, $donGia]);
        }
    }

    /**
     * Thay đổi số lượng sản phẩm trong giỏ
     * Phục vụ chức năng: Cập nhật sản phẩm trong giỏ 
     */
    public function cap_nhat_so_luong($maGH, $maSP, $soLuongMoi)
    {
        $sql = "UPDATE chitietgh SET SoLuong = ? WHERE MaGH = ? AND MaSP = ?";
        return $this->query($sql, [$soLuongMoi, $maGH, $maSP]);
    }

    /**
     * Bỏ sản phẩm khỏi giỏ hàng
     * Phục vụ chức năng: Xóa sản phẩm trong giỏ 
     */
    public function xoa_san_pham($maGH, $maSP)
    {
        $sql = "DELETE FROM chitietgh WHERE MaGH = ? AND MaSP = ?";
        return $this->query($sql, [$maGH, $maSP]);
    }

    /**
     * Lấy danh sách sản phẩm trong giỏ kèm thông tin chi tiết từ bảng Sanpham
     * Phục vụ chức năng: Xem giỏ hàng [cite: 35, 36]
     */
    public function lay_danh_sach_trong_gio($maGH)
    {
        $sql = "SELECT c.*, s.TenSP, s.HinhAnh, s.Gia as GiaHienTai 
                FROM chitietgh c
                JOIN sanpham s ON c.MaSP = s.MaSP
                WHERE c.MaGH = ?";
        return $this->query($sql, [$maGH])->fetchAll();
    }
}
