<?php
// vị trí: classes/Sanpham_Khuyenmai.class.php
require_once 'DB.class.php';

class Sanpham_Khuyenmai extends Db
{

    /**
     * Lấy danh sách tất cả sản phẩm đang có khuyến mãi
     * Phục vụ chức năng: Hiển thị sản phẩm giảm giá trên trang chủ
     */
    public function lay_tat_ca_sp_km()
    {
        $sql = "SELECT s.*, k.TenKM, k.PhanTramGiam, k.NgayKetThuc 
                FROM sp_km sk
                JOIN sanpham s ON sk.MaSP = s.MaSP
                JOIN khuyenmai k ON sk.MaKM = k.MaKM
                WHERE CURDATE() BETWEEN k.NgayBatDau AND k.NgayKetThuc";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Gắn một mã khuyến mãi cho một sản phẩm cụ thể
     * Phục vụ chức năng: Áp dụng khuyến mãi cho sản phẩm [cite: 33]
     */
    public function ap_dung_km($maSP, $maKM)
    {
        $sql = "INSERT INTO sp_km (MaSP, MaKM) VALUES (?, ?)";
        return $this->query($sql, [$maSP, $maKM]);
    }

    /**
     * Hủy khuyến mãi của một sản phẩm
     */
    public function huy_ap_dung($maSP, $maKM)
    {
        $sql = "DELETE FROM sp_km WHERE MaSP = ? AND MaKM = ?";
        return $this->query($sql, [$maSP, $maKM]);
    }

    /**
     * Kiểm tra một sản phẩm có đang trong chương trình khuyến mãi nào không
     */
    public function kiem_tra_km_sp($maSP)
    {
        $sql = "SELECT k.* FROM khuyenmai k
                JOIN sp_km sk ON k.MaKM = sk.MaKM
                WHERE sk.MaSP = ? AND CURDATE() BETWEEN k.NgayBatDau AND k.NgayKetThuc";
        return $this->query($sql, [$maSP])->fetch();
    }

    /**
     * Lấy danh sách sản phẩm theo một mã khuyến mãi cụ thể
     */
    public function lay_sp_theo_ma_km($maKM)
    {
        $sql = "SELECT s.* FROM sanpham s
                JOIN sp_km sk ON s.MaSP = sk.MaSP
                WHERE sk.MaKM = ?";
        return $this->query($sql, [$maKM])->fetchAll();
    }
}
