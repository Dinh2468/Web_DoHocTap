<?php
// Views/Donhang/lichsu_donhang.php
session_start();
require_once '../../classes/DB.class.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Taikhoan/login.php");
    exit();
}
$db = new Db();
$maKH = $_SESSION['user_id'];
$sql = "SELECT * FROM donhang WHERE MaKH = ? ORDER BY NgayDat DESC";
$donhangs = $db->query($sql, [$maKH])->fetchAll();
include_once '../includes/header.php';
?>
<style>
    /* Căn giữa tiêu đề và nội dung bảng */
    .order-table th,
    .order-table td {
        text-align: center;
        /* Căn giữa tất cả các cột */
        vertical-align: middle;
        padding: 15px;
    }
    /* Căn lề riêng cho cột Mã Đơn nếu muốn trông tự nhiên hơn */
    .order-table td:first-child {
        font-weight: bold;
    }
    /* Khống chế độ rộng cột Thao tác để không bị dài quá mức */
    .col-action {
        width: 220px;
    }
    .action-group {
        display: flex;
        gap: 8px;
        justify-content: center;
        /* Căn giữa các nút trong ô Thao tác */
        align-items: center;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        min-width: 100px;
        /* Đảm bảo các nhãn trạng thái có độ dài bằng nhau */
    }
    .btn-action {
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid transparent;
        white-space: nowrap;
        /* Không cho chữ xuống dòng */
    }
    .btn-view {
        color: #338dbc;
        border-color: #338dbc;
        background: #fff;
    }
    .btn-view:hover {
        background: #338dbc;
        color: #fff;
    }
    .btn-cancel {
        color: #d32f2f;
        background: #FFEBEE;
        border-color: #ef9a9a;
    }
    .btn-cancel:hover {
        background: #d32f2f;
        color: #fff;
    }
</style>
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
        <table class="order-table" style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <thead>
                <tr style="background-color: #2E7D32; color: white;">
                    <th>Mã Đơn</th>
                    <th>Ngày Đặt</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th class="col-action">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donhangs as $dh):
                    $maDH = $dh['MaDH'];
                    $statusBg = '#FFF3E0';
                    $statusColor = '#E65100';
                    switch ($dh['TrangThai']) {
                        case 'Hoàn thành':
                            $statusBg = '#E8F5E9';
                            $statusColor = '#2E7D32';
                            break;
                        case 'Đang giao':
                            $statusBg = '#E3F2FD';
                            $statusColor = '#1565C0';
                            break;
                        case 'Đã hủy':
                            $statusBg = '#FFEBEE';
                            $statusColor = '#C62828';
                            break;
                    }
                    $showReviewBadge = false;
                    $pendingReviews = 0;
                    if ($dh['TrangThai'] === 'Hoàn thành') {
                        $sqlItems = "SELECT MaSP, SoLuong FROM chitietdh WHERE MaDH = ?";
                        $items = $db->query($sqlItems, [$maDH])->fetchAll();
                        foreach ($items as $item) {
                            $sqlCount = "SELECT COUNT(*) FROM danhgia WHERE MaSP = ? AND MaDH = ? AND MaKH = ?";
                            $daDanhGia = $db->query($sqlCount, [$item['MaSP'], $maDH, $_SESSION['user_id']])->fetchColumn();
                            if ($item['SoLuong'] > $daDanhGia) {
                                $pendingReviews += ($item['SoLuong'] - $daDanhGia);
                                $showReviewBadge = true;
                            }
                        }
                    }
                ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td>#<?php echo $maDH; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($dh['NgayDat'])); ?></td>
                        <td style="color: #d32f2f; font-weight: bold;"><?php echo number_format($dh['TongTien'], 0, ',', '.'); ?>đ</td>
                        <td>
                            <span class="status-badge" style="background: <?php echo $statusBg; ?>; color: <?php echo $statusColor; ?>;">
                                <?php echo $dh['TrangThai']; ?>
                            </span>
                            <?php if ($showReviewBadge): ?>
                                <div style="margin-top: 5px;">
                                    <span style="background: #FFF3E0; color: #E65100; font-size: 10px; padding: 2px 8px; border-radius: 10px; border: 1px solid #FFB74D; font-weight: bold;">
                                        ⭐ Còn <?php echo $pendingReviews; ?> sản phẩm chưa đánh giá
                                    </span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="col-action">
                            <div class="action-group">
                                <a href="chitiet_donhang.php?id=<?php echo $maDH; ?>" class="btn-action btn-view">Xem chi tiết</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php include_once '../includes/footer.php'; ?>