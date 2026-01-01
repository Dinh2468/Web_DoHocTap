<?php
session_start();
require_once '../../classes/DB.class.php';

// Kiểm tra nếu chưa đăng nhập thì chuyển về trang login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Taikhoan/login.php");
    exit();
}

$db = new Db();
$maKH = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của khách hàng này
$sql = "SELECT * FROM donhang WHERE MaKH = ? ORDER BY NgayDat DESC";
$donhangs = $db->query($sql, [$maKH])->fetchAll();

include_once '../includes/header.php';
?>

<div class="container" style="margin-top: 30px; margin-bottom: 50px; min-height: 60vh;">
    <h2 style="color: #2E7D32; margin-bottom: 25px; border-bottom: 2px solid #4CAF50; padding-bottom: 10px;">
        Lịch sử mua hàng
    </h2>

    <?php if (empty($donhangs)): ?>
        <div style="text-align: center; padding: 50px; background: white; border-radius: 8px;">
            <p>Bạn chưa có đơn hàng nào.</p>
            <a href="../index.php" class="btn-buy-now" style="display: inline-block; margin-top: 15px;">MUA SẮM NGAY</a>
        </div>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <thead>
                <tr style="background-color: #2E7D32; color: white; text-align: left;">
                    <th style="padding: 15px;">Mã Đơn</th>
                    <th style="padding: 15px;">Ngày Đặt</th>
                    <th style="padding: 15px;">Tổng Tiền</th>
                    <th style="padding: 15px;">Trạng Thái</th>
                    <th style="padding: 15px;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donhangs as $dh): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px; font-weight: bold;">#<?php echo $dh['MaDH']; ?></td>
                        <td style="padding: 15px;"><?php echo date('d/m/Y H:i', strtotime($dh['NgayDat'])); ?></td>
                        <td style="padding: 15px; color: #d32f2f; font-weight: bold;">
                            <?php echo number_format($dh['TongTien'], 0, ',', '.'); ?>đ
                        </td>
                        <td style="padding: 15px;">
                            <span style="padding: 5px 10px; border-radius: 15px; font-size: 12px; background: #FFF3E0; color: #E65100;">
                                <?php echo $dh['TrangThai']; ?>
                            </span>
                        </td>
                        <td style="padding: 15px;">
                            <a href="chitiet_donhang.php?id=<?php echo $dh['MaDH']; ?>" style="color: #338dbc; text-decoration: underline;">Xem chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>