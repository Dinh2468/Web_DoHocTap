<?php
// admin/index.php
include_once 'includes/header.php';
$db = new Db();
$countOrderPending = $db->query("SELECT COUNT(*) as total FROM donhang WHERE TrangThai = 'Chờ xử lý'")->fetch();
$lowStock = $db->query("SELECT COUNT(*) as total FROM sanpham WHERE SoLuongTon < 5")->fetch();
$countProduct = $db->query("SELECT COUNT(*) as total FROM sanpham")->fetch();
if ($_SESSION['user_role'] === 'Quản trị viên') {
    $countCustomer = $db->query("SELECT COUNT(*) as total FROM khachhang")->fetch();
    $totalRevenue = $db->query("SELECT SUM(TongTien) as total FROM donhang WHERE TrangThai = 'Hoàn thành'")->fetch();
    $chartData = $db->query("SELECT DATE(NgayDat) as date, SUM(TongTien) as revenue 
                         FROM donhang 
                         WHERE TrangThai IN ('Hoàn thành', 'Đang giao') 
                         AND NgayDat >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                         GROUP BY DATE(NgayDat) ORDER BY date ASC")->fetchAll();
}
$recentOrders = $db->query("SELECT dh.*, kh.HoTen FROM donhang dh 
                             LEFT JOIN khachhang kh ON dh.MaKH = kh.MaKH 
                             ORDER BY dh.NgayDat DESC LIMIT 5")->fetchAll();
?>
<style>
    .status-alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        color: white;
    }

    .bg-warning {
        background: #ffa000;
    }

    .bg-danger {
        background: #d32f2f;
    }

    .chart-container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-top: 20px;
    }

    .status.green {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .status.orange {
        background: #FFF3E0;
        color: #EF6C00;
    }

    .status.blue {
        background: #E3F2FD;
        color: #1565C0;
    }

    .status.red {
        background: #FFEBEE;
        color: #C62828;
    }
</style>
<div class="main-content-inner">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <?php if ($countOrderPending['total'] > 0): ?>
            <div class="status-alert bg-warning">
                <span style="font-size: 24px;">🔔</span>
                <div>
                    <strong><?php echo $countOrderPending['total']; ?> đơn hàng</strong> đang chờ xử lý.
                    <a href="Views/Donhang/index.php" style="color: white; text-decoration: underline;">Xem ngay</a>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($lowStock['total'] > 0): ?>
            <div class="status-alert bg-danger">
                <span style="font-size: 24px;">⚠️</span>
                <div>
                    <strong><?php echo $lowStock['total']; ?> sản phẩm</strong> sắp hết hàng (tồn < 5).
                        <a href="Views/Sanpham/index.php" style="color: white; text-decoration: underline;">Nhập hàng</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h3><?php echo $countProduct['total']; ?></h3>
                <p>Tổng sản phẩm</p>
            </div>
            <div style="font-size: 30px;">📦</div>
        </div>
        <?php if ($_SESSION['user_role'] === 'Quản trị viên'): ?>
            <div class="stat-card">
                <div class="stat-info">
                    <h3><?php echo number_format($totalRevenue['total'] ?? 0, 0, ',', '.'); ?>đ</h3>
                    <p>Doanh thu (Thực thu)</p>
                </div>
                <div style="font-size: 30px;">💰</div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3><?php echo $countCustomer['total']; ?></h3>
                    <p>Khách hàng</p>
                </div>
                <div style="font-size: 30px;">👥</div>
            </div>
        <?php endif; ?>
    </section>
    <?php if ($_SESSION['user_role'] === 'Quản trị viên' && !empty($chartData)): ?>
        <div class="chart-container">
            <h3 style="margin-bottom: 15px;">Biểu đồ doanh thu 7 ngày gần nhất</h3>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode(array_column($chartData, 'date')); ?>,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: <?php echo json_encode(array_column($chartData, 'revenue')); ?>,
                        borderColor: '#2E7D32',
                        backgroundColor: 'rgba(46, 125, 50, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                }
            });
        </script>
    <?php endif; ?>
    <div class="table-container" style="margin-top: 20px;">
        <div class="main-header" style="padding: 0; margin-bottom: 15px;">
            <h3>Đơn hàng mới nhất</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['MaDH']; ?></td>
                        <td><?php echo $order['HoTen'] ?? 'Khách lẻ'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($order['NgayDat'])); ?></td>
                        <td><?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ</td>
                        <td>
                            <?php
                            $statusClass = '';
                            switch ($order['TrangThai']) {
                                case 'Hoàn thành':
                                    $statusClass = 'green';
                                    break;
                                case 'Đang giao':
                                    $statusClass = 'blue';
                                    break;
                                case 'Chờ xử lý':
                                    $statusClass = 'orange';
                                    break;
                                case 'Đã hủy':
                                    $statusClass = 'red';
                                    break;
                                default:
                                    $statusClass = '';
                            }
                            ?>
                            <span class="status <?php echo $statusClass; ?>">
                                <?php echo $order['TrangThai']; ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once 'includes/footer.php'; ?>