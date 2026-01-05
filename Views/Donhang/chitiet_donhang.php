<?php
// Views/Donhang/chitiet_donhang.php
session_start();
require_once '../../classes/DB.class.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Taikhoan/login.php");
    exit();
}
$maDH = isset($_GET['id']) ? $_GET['id'] : 0;
$db = new Db();
$sqlDH = "SELECT * FROM donhang WHERE MaDH = ? AND MaKH = ?";
$donhang = $db->query($sqlDH, [$maDH, $_SESSION['user_id']])->fetch();
if (!$donhang) {
    echo "<script>alert('Không tìm thấy đơn hàng!'); window.location.href='lichsu_donhang.php';</script>";
    exit();
}
$sqlCT = "SELECT ct.*, sp.TenSP, sp.HinhAnh 
          FROM chitietdh ct 
          JOIN sanpham sp ON ct.MaSP = sp.MaSP 
          WHERE ct.MaDH = ?";
$listSP = $db->query($sqlCT, [$maDH])->fetchAll();
include_once '../includes/header.php';
?>
<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2E7D32;">Chi Tiết Đơn Hàng #<?php echo $maDH; ?></h2>
        <a href="lichsu_donhang.php" style="color: #338dbc; text-decoration: none;">← Quay lại lịch sử</a>
    </div>
    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee; margin-bottom: 25px; display: flex; gap: 50px;">
        <div>
            <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($donhang['NgayDat'])); ?></p>
            <p><strong>Trạng thái:</strong> <span style="color: #E65100; font-weight: bold;"><?php echo $donhang['TrangThai']; ?></span></p>
        </div>
        <div>
            <p><strong>Tổng giá trị đơn hàng:</strong></p>
            <p style="font-size: 20px; color: #d32f2f; font-weight: bold;"><?php echo number_format($donhang['TongTien'], 0, ',', '.'); ?>đ</p>
        </div>
    </div>
    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <thead>
            <tr style="background-color: #f2f2f2; text-align: left;">
                <th style="padding: 15px;">Sản phẩm</th>
                <th style="padding: 15px;">Đơn giá</th>
                <th style="padding: 15px; text-align: center;">Số lượng</th>
                <th style="padding: 15px; text-align: right;">Thành tiền</th>
                <?php if ($donhang['TrangThai'] === 'Hoàn thành'): ?>
                    <th style="padding: 15px; text-align: center;">Đánh giá</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listSP as $sp): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px; display: flex; align-items: center; gap: 15px;">
                        <img src="../../assets/images/Sanpham/<?php echo $sp['HinhAnh']; ?>" width="60" style="border: 1px solid #eee; border-radius: 5px;">
                        <span><?php echo $sp['TenSP']; ?></span>
                    </td>
                    <td style="padding: 15px;"><?php echo number_format($sp['DonGia'], 0, ',', '.'); ?>đ</td>
                    <td style="padding: 15px; text-align: center;"><?php echo $sp['SoLuong']; ?></td>
                    <td style="padding: 15px; text-align: right; font-weight: bold;">
                        <?php echo number_format($sp['DonGia'] * $sp['SoLuong'], 0, ',', '.'); ?>đ
                    </td>
                    <?php
                    $ngayDat = new DateTime($donhang['NgayDat']);
                    $ngayHienTai = new DateTime();
                    $diff = $ngayHienTai->diff($ngayDat);
                    $soNgay = $diff->days;
                    $conHanDanhGia = ($soNgay <= 30);
                    ?>
                    <td style="padding: 15px; text-align: center; min-width: 200px;">
                        <?php if ($donhang['TrangThai'] === 'Hoàn thành'): ?>
                            <?php
                            $ngayDat = new DateTime($donhang['NgayDat']);
                            $ngayHienTai = new DateTime();
                            $diff = $ngayHienTai->diff($ngayDat);
                            $daysPassed = $diff->days;
                            $daysLeft = 30 - $daysPassed;
                            if ($daysLeft >= 0): ?>
                                <?php
                                $sqlCount = "SELECT COUNT(*) FROM danhgia WHERE MaKH = ? AND MaSP = ? AND MaDH = ?";
                                $daDanhGia = $db->query($sqlCount, [$_SESSION['user_id'], $sp['MaSP'], $maDH])->fetchColumn();
                                $soLuongMua = $sp['SoLuong'];
                                $luotConLai = $soLuongMua - $daDanhGia;
                                if ($luotConLai > 0): ?>
                                    <div style="margin-bottom: 8px;">
                                        <span style="display: block; font-size: 12px; color: #E65100; font-weight: bold;">
                                            ⏳ Còn <?php echo $daysLeft; ?> ngày để đánh giá
                                        </span>
                                        <span style="display: block; font-size: 11px; color: #666;">
                                            (Bạn còn <?php echo $luotConLai; ?>/<?php echo $soLuongMua; ?> lượt chưa dùng)
                                        </span>
                                    </div>
                                    <a href="../Sanpham/viet_danhgia.php?idsp=<?php echo $sp['MaSP']; ?>&iddh=<?php echo $maDH; ?>"
                                        class="btn-buy-now" style="font-size: 11px; padding: 6px 12px; background: #FF9800; border-radius: 4px; display: inline-block;">
                                        VIẾT ĐÁNH GIÁ NGAY
                                    </a>
                                <?php else: ?>
                                    <div style="color: #2E7D32; font-weight: bold; font-size: 13px;">
                                        <span style="font-size: 18px;">✓</span> Đã đánh giá đủ <?php echo $soLuongMua; ?> lượt
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color: #d32f2f; font-size: 11px; font-style: italic; background: #FFEBEE; padding: 5px; border-radius: 4px;">
                                    🚫 Đã hết hạn (Quá 30 ngày)
                                </span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color: #999; font-size: 12px;">Chờ hoàn thành đơn hàng</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include_once '../includes/footer.php'; ?>