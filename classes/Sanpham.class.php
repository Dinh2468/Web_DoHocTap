<?php
// vị trí: classes/Sanpham.class.php
require_once 'DB.class.php';

class Sanpham extends Db
{

    /**
     * Lấy danh sách tất cả sản phẩm hiện có
     * Phục vụ chức năng: Hiển thị các sản phẩm có trên hệ thống
     */
    public function getAll()
    {
        $sql = "SELECT * FROM sanpham ORDER BY MaSP DESC";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Lấy danh sách sản phẩm theo từng loại (vở, bút, thước...)
     * Phục vụ chức năng lọc sản phẩm
     */
    public function getByCategory($maLoai)
    {
        $sql = "SELECT * FROM sanpham WHERE MaLoai = ?";
        return $this->query($sql, [$maLoai])->fetchAll();
    }

    /**
     * Xem thông tin chi tiết một sản phẩm cụ thể
     * Phục vụ chức năng: Xem thông tin, giá, hình ảnh chi tiết
     */
    public function getDetail($maSP)
    {
        $sql = "SELECT s.*, l.TenLoai, t.TenTH 
                FROM sanpham s
                LEFT JOIN loaisp l ON s.MaLoai = l.MaLoai
                LEFT JOIN thuonghieu t ON s.MaTH = t.MaTH
                WHERE s.MaSP = ?";
        return $this->query($sql, [$maSP])->fetch();
    }

    /**
     * Tìm kiếm sản phẩm
     * Phục vụ chức năng: Tìm theo tên, loại sản phẩm hoặc thương hiệu
     */
    public function search($keyword)
    {
        $sql = "SELECT * FROM sanpham WHERE TenSP LIKE ? OR MoTa LIKE ?";
        $searchTerm = "%$keyword%";
        return $this->query($sql, [$searchTerm, $searchTerm])->fetchAll();
    }

    /**
     * Cập nhật số lượng tồn kho sau khi đặt hàng/nhập hàng
     */
    public function updateStock($maSP, $soLuong)
    {
        $sql = "UPDATE sanpham SET SoLuongTon = SoLuongTon + ? WHERE MaSP = ?";
        return $this->query($sql, [$soLuong, $maSP]);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM sanpham WHERE MaSP = ?";
        return $this->query($sql, [$id])->fetch();
    }
    public function getHotProducts($limit = 8)
    {
        // Truy vấn lấy sản phẩm có trung bình sao cao nhất
        $sql = "SELECT s.*, AVG(d.SoSao) as TrungBinhSao 
            FROM sanpham s 
            LEFT JOIN danhgia d ON s.MaSP = d.MaSP 
            GROUP BY s.MaSP 
            ORDER BY TrungBinhSao DESC 
            LIMIT $limit";
        $result = $this->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
