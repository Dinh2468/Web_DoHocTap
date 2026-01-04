<?php
// admin/Views/Baocao/index.php
include_once '../../includes/header.php';
if ($_SESSION['user_role'] !== 'Quản trị viên') {
    echo "<script>alert('Bạn không có quyền truy cập trang này!'); window.location.href='/Web_DoHocTap/admin/index.php';</script>";
    exit();
}
$db = new Db();

// Lấy tham số từ URL
$filterType = $_GET['filter_type'] ?? 'custom';
$sel_month = $_GET['sel_month'] ?? date('m');
$sel_year = $_GET['sel_year'] ?? date('Y');
$start_input = $_GET['start'] ?? '';
$end_input = $_GET['end'] ?? '';

// Mặc định ban đầu nếu chưa có dữ liệu
$startDate = date('Y-m-d', strtotime('-7 days'));
$endDate = date('Y-m-d');

// Logic: Chỉ tính toán lại startDate/endDate nếu người dùng chọn bộ lọc nhanh (không phải 'custom')
if ($filterType == 'week') {
    $startDate = date('Y-m-d', strtotime('monday this week'));
    $endDate = date('Y-m-d', strtotime('sunday this week'));
} elseif ($filterType == 'month') {
    // Lấy ngày đầu và cuối của tháng được chọn
    $startDate = "$sel_year-$sel_month-01";
    $endDate = date('Y-m-t', strtotime($startDate));
} elseif ($filterType == 'year') {
    // Lấy ngày đầu và cuối của năm được chọn
    $startDate = "$sel_year-01-01";
    $endDate = "$sel_year-12-31";
} elseif ($filterType == 'custom' && !empty($start_input) && !empty($end_input)) {
    $startDate = $start_input;
    $endDate = $end_input;
}

// Truy vấn dữ liệu dựa trên $startDate và $endDate đã chốt ở trên
$stats = $db->query("SELECT SUM(TongTien) as TotalRevenue, COUNT(MaDH) as TotalOrders, AVG(TongTien) as AvgValue 
                     FROM donhang WHERE NgayDat BETWEEN ? AND ?", [$startDate, $endDate])->fetch();

$dailyData = $db->query("SELECT DATE(NgayDat) as Day, SUM(TongTien) as Revenue 
                         FROM donhang WHERE NgayDat BETWEEN ? AND ? 
                         GROUP BY DATE(NgayDat) ORDER BY Day ASC", [$startDate, $endDate])->fetchAll();

$categoryData = $db->query("SELECT l.TenLoai, SUM(ct.SoLuong * ct.DonGia) as Revenue
                            FROM chitietdh ct
                            JOIN sanpham s ON ct.MaSP = s.MaSP
                            JOIN loaisp l ON s.MaLoai = l.MaLoai
                            JOIN donhang d ON ct.MaDH = d.MaDH
                            WHERE d.NgayDat BETWEEN ? AND ?
                            GROUP BY l.TenLoai", [$startDate, $endDate])->fetchAll();
?>

<div class="main-content-inner">
    <header class="main-header" style="margin-bottom: 20px;">
        <h2>Báo cáo doanh thu</h2>

    </header>

    <div class="table-container" style="margin-bottom: 20px; padding: 15px;">
        <form action="" method="GET" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;" id="reportForm">
            <div style="display: flex; align-items: center; gap: 8px;">
                <label>Thời gian:</label>
                <select name="filter_type" id="filter_type" class="form-control" style="width: 130px;" onchange="toggleFilterInputs()">
                    <option value="custom" <?php echo ($filterType == 'custom') ? 'selected' : ''; ?>>Tùy chọn</option>
                    <option value="week" <?php echo ($filterType == 'week') ? 'selected' : ''; ?>>Tuần này</option>
                    <option value="month" <?php echo ($filterType == 'month') ? 'selected' : ''; ?>>Theo tháng</option>
                    <option value="year" <?php echo ($filterType == 'year') ? 'selected' : ''; ?>>Theo năm</option>
                </select>
            </div>

            <div id="month_select_group" style="display: <?php echo ($filterType == 'month') ? 'flex' : 'none'; ?>; align-items: center; gap: 8px;">
                <select name="sel_month" class="form-control" style="width: 150px;">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo sprintf('%02d', $m); ?>" <?php echo ($sel_month == $m) ? 'selected' : ''; ?>>
                            Tháng <?php echo $m; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div id="year_select_group" style="display: <?php echo ($filterType == 'month' || $filterType == 'year') ? 'flex' : 'none'; ?>; align-items: center; gap: 8px;">
                <input type="number" name="sel_year" class="form-control" style="width: 90px;"
                    value="<?php echo $sel_year; ?>" min="2000" max="2099">
            </div>

            <div id="custom_date_group" style="display: <?php echo ($filterType == 'custom' || $filterType == 'week') ? 'flex' : 'none'; ?>; align-items: center; gap: 8px;">
                <input type="date" name="start" id="start_date" class="form-control" value="<?php echo $startDate; ?>" onchange="switchToCustom()">
                <span>đến</span>
                <input type="date" name="end" id="end_date" class="form-control" value="<?php echo $endDate; ?>" onchange="switchToCustom()">
            </div>

            <button type="submit" class="btn-filter" style="background: #2E7D32;">Xem báo cáo</button>
            <button type="button" class="btn-print" style="background: #455a64;" onclick="window.print()">Xuất báo cáo</button>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-card" style="border-left: 5px solid #2E7D32;">
            <div>
                <p style="color: #888; font-size: 12px; font-weight: 600;">TỔNG DOANH THU</p>
                <h3 style="color: #2E7D32; font-size: 22px; margin-top: 5px;">
                    <?php echo number_format($stats['TotalRevenue'] ?? 0); ?>đ
                </h3>
            </div>
        </div>
        <div class="stat-card" style="border-left: 5px solid #1976D2;">
            <div>
                <p style="color: #888; font-size: 12px; font-weight: 600;">TỔNG ĐƠN HÀNG</p>
                <h3 style="color: #333; font-size: 22px; margin-top: 5px;"><?php echo $stats['TotalOrders']; ?></h3>
            </div>
        </div>
        <div class="stat-card" style="border-left: 5px solid #FFA000;">
            <div>
                <p style="color: #888; font-size: 12px; font-weight: 600;">GIÁ TRỊ ĐƠN TRUNG BÌNH</p>
                <h3 style="color: #333; font-size: 22px; margin-top: 5px;">
                    <?php echo number_format($stats['AvgValue'] ?? 0); ?>đ
                </h3>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 20px;">
        <div class="table-container">
            <h4 style="margin-bottom: 15px;">Biểu đồ doanh thu</h4>
            <canvas id="revenueChart" height="280"></canvas>
        </div>
        <div class="table-container">
            <h4 style="margin-bottom: 15px;">Doanh thu theo danh mục</h4>
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <div class="table-container" style="margin-top: 20px;">
        <h4 style="margin-bottom: 15px;">Chi tiết theo ngày</h4>
        <table>
            <thead>
                <tr style="background: #f8f9fa;">
                    <th>Ngày</th>
                    <th>Số đơn hàng</th>
                    <th>Doanh thu</th>
                    <th>Thực thu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dailyData as $row):
                    $count = $db->query("SELECT COUNT(*) as t FROM donhang WHERE DATE(NgayDat) = ?", [$row['Day']])->fetch();
                ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($row['Day'])); ?></td>
                        <td><?php echo $count['t']; ?></td>
                        <td><?php echo number_format($row['Revenue']); ?>đ</td>
                        <td style="font-weight: bold; color: #2E7D32;"><?php echo number_format($row['Revenue']); ?>đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Cấu hình Biểu đồ Cột
    const ctxRev = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRev, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($dailyData, 'Day')); ?>,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: <?php echo json_encode(array_column($dailyData, 'Revenue')); ?>,
                backgroundColor: '#4CAF50'
            }]
        }
    });

    // Cấu hình Biểu đồ Tròn
    const ctxCat = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCat, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($categoryData, 'TenLoai')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($categoryData, 'Revenue')); ?>,
                backgroundColor: ['#2E7D32', '#4CAF50', '#81C784', '#C8E6C9']
            }]
        }
    });

    function switchToCustom() {
        // Nếu người dùng chọn ngày thủ công, tự động chuyển dropdown sang "Tùy chọn"
        document.getElementById('filter_type').value = 'custom';
    }

    function toggleFilterInputs() {
        const type = document.getElementById('filter_type').value;
        const monthGroup = document.getElementById('month_select_group');
        const yearGroup = document.getElementById('year_select_group');
        const customGroup = document.getElementById('custom_date_group');

        // Reset hiển thị
        monthGroup.style.display = 'none';
        yearGroup.style.display = 'none';
        customGroup.style.display = 'none';

        if (type === 'month') {
            monthGroup.style.display = 'flex';
            yearGroup.style.display = 'flex';
        } else if (type === 'year') {
            yearGroup.style.display = 'flex';
        } else {
            customGroup.style.display = 'flex';
        }
    }

    function switchToCustom() {
        document.getElementById('filter_type').value = 'custom';
        toggleFilterInputs(); // Cập nhật lại giao diện khi đổi sang custom
    }
</script>