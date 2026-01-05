<?php
// classes/Donhang.class.php
require_once 'DB.class.php';
class Donhang extends Db
{
    public function tao_don_hang($data)
    {
        $sql = "INSERT INTO donhang (MaKH, HoTenNguoiNhan, SDTNguoiNhan, NgayDat, TrangThai, TongTien, DiaChiGiaoHang) 
                VALUES (?, ?, ?, NOW(), 'Đang xử lý', ?, ?)";
        $params = [
            $data['MaKH'],
            $data['HoTenNguoiNhan'],
            $data['SDTNguoiNhan'],
            $data['TongTien'],
            $data['DiaChiGiaoHang']
        ];
        $this->query($sql, $params);
        return $this->pdo->lastInsertId();
    }
    public function cap_nhat_trang_thai($maDH, $trangThaiMoi)
    {
        $sql = "UPDATE donhang SET TrangThai = ? WHERE MaDH = ?";
        return $this->query($sql, [$trangThaiMoi, $maDH]);
    }
    public function lay_don_hang_khach_hang($maKH)
    {
        $sql = "SELECT * FROM donhang WHERE MaKH = ? ORDER BY NgayDat DESC";
        return $this->query($sql, [$maKH])->fetchAll();
    }
    public function lay_tat_ca()
    {
        $sql = "SELECT d.*, k.HoTen 
                FROM donhang d
                JOIN khachhang k ON d.MaKH = k.MaKH
                ORDER BY d.NgayDat DESC";
        return $this->query($sql)->fetchAll();
    }
    public function thong_ke_doanh_thu($thang, $nam)
    {
        $sql = "SELECT SUM(TongTien) as DoanhThu 
                FROM donhang 
                WHERE MONTH(NgayDat) = ? AND YEAR(NgayDat) = ? AND TrangThai = 'Hoàn tất'";
        $result = $this->query($sql, [$thang, $nam])->fetch();
        return $result['DoanhThu'] ?? 0;
    }
    public function huy_don_hang($maDH)
    {
        $sql = "UPDATE donhang SET TrangThai = 'Đã hủy' WHERE MaDH = ?";
        return $this->query($sql, [$maDH]);
    }
}
