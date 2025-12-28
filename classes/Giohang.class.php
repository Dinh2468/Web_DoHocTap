<?php
// vị trí: classes/Giohang.class.php
require_once 'DB.class.php';

class Giohang extends Db
{

    /**
     * Lấy thông tin giỏ hàng của một khách hàng
     * Phục vụ chức năng: Xem giỏ hàng
     */
    public function lay_theo_khach_hang($maKH)
    {
        $sql = "SELECT * FROM giohang WHERE MaKH = ?";
        return $this->query($sql, [$maKH])->fetch();
    }

    /**
     * Tạo giỏ hàng mới cho khách hàng nếu chưa có
     */
    public function tao_moi($maKH)
    {
        $sql = "INSERT INTO giohang (MaKH, NgayCapNhat, TongTien) VALUES (?, NOW(), 0)";
        $this->query($sql, [$maKH]);
        return $this->pdo->lastInsertId();
    }

    /**
     * Cập nhật tổng tiền và thời gian thay đổi của giỏ hàng
     */
    public function cap_nhat_gio_hang($maGH, $tongTien)
    {
        $sql = "UPDATE giohang SET NgayCapNhat = NOW(), TongTien = ? WHERE MaGH = ?";
        return $this->query($sql, [$tongTien, $maGH]);
    }

    /**
     * Xóa sạch giỏ hàng (Sau khi đã đặt hàng thành công)
     * Phục vụ chức năng: Xóa giỏ hàng trong sơ đồ tuần tự
     */
    public function xoa_gio_hang($maGH)
    {
        // Cần xóa chi tiết trước để tránh lỗi ràng buộc khóa ngoại
        $this->query("DELETE FROM chitietgh WHERE MaGH = ?", [$maGH]);
        $sql = "DELETE FROM giohang WHERE MaGH = ?";
        return $this->query($sql, [$maGH]);
    }

    /**
     * Tính toán lại tổng tiền của giỏ hàng dựa trên các chi tiết sản phẩm
     */
    public function tinh_lai_tong_tien($maGH)
    {
        $sql = "SELECT SUM(SoLuong * DonGia) as Tong FROM chitietgh WHERE MaGH = ?";
        $result = $this->query($sql, [$maGH])->fetch();
        $moi = $result['Tong'] ?? 0;
        $this->cap_nhat_gio_hang($maGH, $moi);
        return $moi;
    }
}
