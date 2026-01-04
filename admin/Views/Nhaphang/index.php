<?php
// admin/Views/Nhaphang/index.php
include_once '../../includes/header.php';
$db = new Db();

// Lấy tham số tìm kiếm
$searchName = $_GET['search_name'] ?? '';
$searchNCC = $_GET['search_ncc'] ?? '';
$searchDate = $_GET['search_date'] ?? '';

$sql = "SELECT nh.*, nv.HoTen as TenNV, ncc.TenNCC 
        FROM nhaphang nh
        JOIN nhanvien nv ON nh.MaNV = nv.MaNV
        JOIN nhacungcap ncc ON nh.MaNCC = ncc.MaNCC
        WHERE 1=1";
$params = [];

// Tìm theo tên nhân viên
if (!empty($searchName)) {
    $sql .= " AND nv.HoTen LIKE ?";
    $params[] = "%$searchName%";
}

// Tìm theo tên nhà cung cấp
if (!empty($searchNCC)) {
    $sql .= " AND ncc.TenNCC LIKE ?";
    $params[] = "%$searchNCC%";
}

// Tìm chính xác theo ngày nhập
if (!empty($searchDate)) {
    $sql .= " AND nh.NgayNhap = ?";
    $params[] = $searchDate;
}

$sql .= " ORDER BY nh.NgayNhap DESC";
$importLogs = $db->query($sql, $params)->fetchAll();
?>
<div class="main-content-inner">
    <header class="main-header">
        <h2>Quản lý nhập hàng</h2>
        <a href="add.php" class="btn-filter" style="background: var(--primary-color); text-decoration: none;">+ Tạo phiếu nhập mới</a>
    </header>
    <div class="table-container" style="margin-bottom: 20px; padding: 15px;">
        <form action="" method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label class="form-label">Nhân viên</label>
                <input type="text" name="search_name" class="form-control" placeholder="Tên nhân viên..." value="<?php echo htmlspecialchars($searchName); ?>">
            </div>

            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label class="form-label">Nhà cung cấp</label>
                <input type="text" name="search_ncc" class="form-control" placeholder="Tên nhà cung cấp..." value="<?php echo htmlspecialchars($searchNCC); ?>">
            </div>

            <div class="form-group" style="width: 180px;">
                <label class="form-label">Ngày nhập</label>
                <input type="date" name="search_date" class="form-control" value="<?php echo $searchDate; ?>">
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn-filter">Tìm kiếm</button>
                <a href="index.php" class="btn-clear">Xóa lọc</a>
            </div>
        </form>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã phiếu</th>
                    <th>Ngày nhập</th>
                    <th>Nhân viên</th>
                    <th>Nhà cung cấp</th>
                    <th>Tổng tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($importLogs as $log): ?>
                    <tr>
                        <td>#NH<?php echo $log['MaNH']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($log['NgayNhap'])); ?></td>
                        <td><?php echo $log['TenNV']; ?></td>
                        <td><?php echo $log['TenNCC']; ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($log['TongTien']); ?>đ</td>
                        <td>
                            <button class="btn-view-profile" onclick="viewDetail(<?php echo $log['MaNH']; ?>)">Chi tiết</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="detailModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; margin: 5% auto; padding: 20px; width: 70%; border-radius: 8px; position: relative;">
        <span onclick="closeModal()" style="position: absolute; right: 20px; top: 10px; font-size: 28px; cursor: pointer;">&times;</span>
        <h3 id="modalTitle" style="margin-bottom: 20px; color: var(--primary-color);">Chi tiết phiếu nhập #</h3>

        <div id="modalContent">
            <p>Đang tải dữ liệu...</p>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 25px; padding-top: 15px; border-top: 1px solid #eee;">
            <button onclick="closeModal()" class="btn-clear" style="margin: 0; padding: 10px 25px; background: #f0f0f0; color: #333; border: none;">Đóng</button>

            <div style="display: flex; gap: 10px;">
                <button onclick="printDetail()" class="btn-print" style="padding: 10px 25px; background: #455a64; color: white; border: none; border-radius: 4px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                    <span>🖨️</span> In phiếu
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewDetail(maNH) {
        document.getElementById('detailModal').style.display = 'block';
        document.getElementById('modalTitle').innerText = 'Chi tiết phiếu nhập #NH' + maNH;

        // Gọi Ajax lấy dữ liệu từ Controller
        fetch(`../../controller/AdminNhaphangController.php?action=get_detail&id=${maNH}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('modalContent').innerHTML = html;
            });
    }

    function closeModal() {
        document.getElementById('detailModal').style.display = 'none';
    }

    // Đóng khi click ra ngoài modal
    window.onclick = function(event) {
        let modal = document.getElementById('detailModal');
        if (event.target == modal) closeModal();
    }

    // admin/Views/Nhaphang/index.php
    function printDetail() {
        const printContents = document.getElementById('modalContent').innerHTML;
        const title = document.getElementById('modalTitle').innerText;

        const printWindow = window.open('', '', 'height=600,width=900');
        printWindow.document.write('<html><head><title>In phiếu nhập</title>');
        // Thêm CSS để bản in rõ ràng các thông tin mới
        printWindow.document.write('<style>body{font-family:Arial; padding:30px;} table{width:100%; border-collapse:collapse; margin-top:20px;} th,td{border:1px solid #ddd; padding:10px; text-align:left;} .header-info p{margin: 5px 0;}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2 style="text-align:center; color:#2E7D32; margin-bottom:10px;">PHIẾU NHẬP KHO</h2>');
        printWindow.document.write('<p style="text-align:center; margin-bottom:30px;">' + title + '</p>');
        printWindow.document.write('<div class="header-info">' + printContents + '</div>');
        printWindow.document.write('<div style="margin-top:60px; display:flex; justify-content:space-between; padding: 0 50px;"><div style="text-align:center;"><p>Người lập phiếu</p><br><br><b>' + document.querySelector(".TenNV-Hidden")?.value + '</b></div><div style="text-align:center;"><p>Thủ kho</p><br><br><b>....................</b></div></div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>