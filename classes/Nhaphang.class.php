<?php
// vị trí: classes/Nhaphang.class.php
require_once 'DB.class.php';

class Nhaphang extends Db
{

    /**
     * Tạo phiếu nhập hàng mới (Ghi nhận hàng mới từ nhà cung cấp)
     * Phục vụ chức năng: Nhập hàng 
     */
    public function tao_phieu_nhap($maNV, $maNCC, $tongTien)
    {
        $sql = "INSERT INTO nhaphang (MaNV, MaNCC, NgayNhap, TongTien) VALUES (?, ?, NOW(), ?)";
        $this->query($sql, [$maNV, $maNCC, $tongTien]);
        return $this->pdo->lastInsertId();
    }

    /**
     * Ghi số lượng, giá nhập từng sản phẩm vào chi tiết phiếu nhập
     * Phục vụ chức năng: Quản lý chi tiết nhập hàng 
     */
    public function luu_chi_tiet($maNH, $maSP, $soLuong, $donGia)
    {
        $sql = "INSERT INTO chitietnh (MaNH, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)";
        return $this->query($sql, [$maNH, $maSP, $soLuong, $donGia]);
    }

    /**
     * Tổng hợp các đợt nhập hàng và chi phí
     * Phục vụ chức năng: Báo cáo nhập hàng 
     */
    public function lay_tat_ca_phieu_nhap()
    {
        $sql = "SELECT n.*, nv.HoTen as TenNhanVien, ncc.TenNCC 
                FROM nhaphang n
                JOIN nhanvien nv ON n.MaNV = nv.MaNV
                JOIN nhacungcap ncc ON n.MaNCC = ncc.MaNCC
                ORDER BY n.NgayNhap DESC";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Xem chi tiết các sản phẩm trong một phiếu nhập cụ thể
     */
    public function lay_chi_tiet_theo_id($maNH)
    {
        $sql = "SELECT c.*, s.TenSP 
                FROM chitietnh c
                JOIN sanpham s ON c.MaSP = s.MaSP
                WHERE c.MaNH = ?";
        return $this->query($sql, [$maNH])->fetchAll();
    }

    /**
     * Thống kê tổng tiền nhập hàng theo tháng/năm
     */
    public function thong_ke_nhap_hang($thang, $nam)
    {
        $sql = "SELECT SUM(TongTien) as TongChiPhi FROM nhaphang 
                WHERE MONTH(NgayNhap) = ? AND YEAR(NgayNhap) = ?";
        $result = $this->query($sql, [$thang, $nam])->fetch();
        return $result['TongChiPhi'] ?? 0;
    }
}
