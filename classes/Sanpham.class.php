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
        $sql = "SELECT s.*, 
            (SELECT km.PhanTramGiam FROM sp_km sk 
             JOIN khuyenmai km ON sk.MaKM = km.MaKM 
             WHERE sk.MaSP = s.MaSP 
             AND CURDATE() BETWEEN km.NgayBatDau AND km.NgayKetThuc 
             LIMIT 1) as PhanTramGiam
            FROM sanpham s WHERE s.MaSP = ?";

        $item = $this->query($sql, [$id])->fetch();

        if ($item) {
            // Tự động tính toán GiaKM nếu có phần trăm giảm giá
            $item['GiaKM'] = $item['Gia'];
            if (isset($item['PhanTramGiam'])) {
                $item['GiaKM'] = $item['Gia'] * (1 - $item['PhanTramGiam'] / 100);
            }
        }
        return $item;
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

    public function getAll_phantrang($offset, $limit = 21)
    {
        $sql = "SELECT * FROM sanpham ORDER BY MaSP DESC LIMIT $offset, $limit";
        return $this->query($sql)->fetchAll();
    }

    public function filterAdvanced($maLoai, $priceArr, $brandArr, $offset = null, $limit = null)
    {
        $sql = "SELECT * FROM sanpham WHERE 1=1";
        $params = [];

        if ($maLoai) {
            $sql .= " AND MaLoai = ?";
            $params[] = $maLoai;
        }

        if (!empty($priceArr)) {
            $priceQueries = [];
            foreach ($priceArr as $range) {
                $parts = explode('-', $range);
                $priceQueries[] = "(Gia >= ? AND Gia <= ?)";
                $params[] = $parts[0];
                $params[] = $parts[1];
            }
            $sql .= " AND (" . implode(" OR ", $priceQueries) . ")";
        }

        if (!empty($brandArr)) {
            $placeholders = implode(',', array_fill(0, count($brandArr), '?'));
            $sql .= " AND MaTH IN ($placeholders)";
            $params = array_merge($params, $brandArr);
        }

        $sql .= " ORDER BY MaSP DESC";

        // Chỉ thêm LIMIT nếu có truyền tham số phân trang
        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT $offset, $limit";
        }

        return $this->query($sql, $params)->fetchAll();
    }
    // Tính tổng số trang
    public function countAll($maLoai = null, $priceArr = [], $brandArr = [])
    {
        $sql = "SELECT COUNT(*) FROM sanpham WHERE 1=1";
        $params = [];
        // Copy toàn bộ logic điều kiện lọc từ hàm filterAdvanced vào đây (trước LIMIT)
        // ... logic filter ...
        if ($maLoai) {
            $sql .= " AND MaLoai = ?";
            $params[] = $maLoai;
        }

        if (!empty($priceArr)) {
            $priceQueries = [];
            foreach ($priceArr as $range) {
                $parts = explode('-', $range);
                $priceQueries[] = "(Gia >= ? AND Gia <= ?)";
                $params[] = $parts[0];
                $params[] = $parts[1];
            }
            $sql .= " AND (" . implode(" OR ", $priceQueries) . ")";
        }

        if (!empty($brandArr)) {
            $placeholders = implode(',', array_fill(0, count($brandArr), '?'));
            $sql .= " AND MaTH IN ($placeholders)";
            $params = array_merge($params, $brandArr);
        }

        return $this->query($sql, $params)->fetchColumn();
    }
}
