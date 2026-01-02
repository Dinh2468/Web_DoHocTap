<?php
// admin/index.php

include_once 'includes/header.php';

$db = new Db();

// 1. Lấy dữ liệu thống kê từ Database
$countOrder = $db->query("SELECT COUNT(*) as total FROM donhang WHERE TrangThai = 'Chờ xử lý'")->fetch();
$countProduct = $db->query("SELECT COUNT(*) as total FROM sanpham")->fetch();
$countCustomer = $db->query("SELECT COUNT(*) as total FROM khachhang")->fetch();
$revenue = $db->query("SELECT SUM(TongTien) as total FROM donhang WHERE TrangThai = 'Đã giao'")->fetch();

// 2. Lấy 5 đơn hàng mới nhất
$recentOrders = $db->query("SELECT * FROM donhang ORDER BY NgayDat DESC LIMIT 5")->fetchAll();
?>

<section class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <h3><?php echo $countOrder['total'] ?? 0; ?></h3>
            <p>Đơn hàng mới</p>
        </div>
        <div style="font-size: 30px;">🛒</div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <h3><?php echo $countProduct['total'] ?? 0; ?></h3>
            <p>Sản phẩm</p>
        </div>
        <div style="font-size: 30px;">📦</div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <h3><?php echo $countCustomer['total'] ?? 0; ?></h3>
            <p>Khách hàng</p>
        </div>
        <div style="font-size: 30px;">👤</div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <h3><?php echo number_format($revenue['total'] ?? 0, 0, ',', '.'); ?>đ</h3>
            <p>Doanh thu</p>
        </div>
        <div style="font-size: 30px;">💰</div>
    </div>
</section>

<section class="table-container">
    <div class="table-header">
        <h3>Đơn hàng gần đây</h3>
        <a href="Views/Donhang/index.php" class="btn-add">Xem tất cả</a>
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
            <?php if (!empty($recentOrders)): ?>
                <?php foreach ($recentOrders as $row): ?>
                    <tr>
                        <td>#<?php echo $row['MaDH']; ?></td>
                        <td><?php echo htmlspecialchars($row['HoTenNguoiNhan'] ?? 'Khách lẻ'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['NgayDat'])); ?></td>
                        <td><?php echo number_format($row['TongTien'], 0, ',', '.'); ?>đ</td>
                        <td>
                            <span class="status <?php echo ($row['TrangThai'] == 'Đã giao') ? 'green' : 'orange'; ?>">
                                <?php echo $row['TrangThai']; ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Chưa có đơn hàng nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php include_once 'includes/footer.php'; ?>