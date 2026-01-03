<?php
// admin/Views/Sanpham/index.php
include_once '../../includes/header.php';
$db = new Db();

// 1. Lấy danh sách danh mục để hiện vào ô chọn (select)
$categories = $db->query("SELECT * FROM loaisp")->fetchAll();

// 2. Tiếp nhận dữ liệu tìm kiếm từ URL
$search = $_GET['search'] ?? '';
$cat_id = $_GET['category'] ?? '';

// 3. Xây dựng câu lệnh SQL có điều kiện lọc
$sql = "SELECT sp.*, l.TenLoai FROM sanpham sp 
        JOIN loaisp l ON sp.MaLoai = l.MaLoai 
        WHERE 1=1"; // Điều kiện mặc định để dễ nối chuỗi

$params = [];

if (!empty($search)) {
    $sql .= " AND (sp.TenSP LIKE ? OR sp.MaSP = ?)";
    $params[] = "%$search%";
    $params[] = $search;
}

if (!empty($cat_id)) {
    $sql .= " AND sp.MaLoai = ?";
    $params[] = $cat_id;
}

$sql .= " ORDER BY sp.MaSP DESC";
$products = $db->query($sql, $params)->fetchAll();
?>


<div class="main-content-inner">
    <header class="main-header" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
        <h2>Danh sách sản phẩm</h2>
        <div style="font-size: 14px;">Số lượng: <strong><?php echo count($products); ?></strong></div>
    </header>

    <div class="toolbar">
        <form action="" method="GET" class="search-group" style="display: flex; gap: 10px;">
            <input type="text" name="search" class="search-input"
                placeholder="Tìm tên sản phẩm..." value="<?php echo htmlspecialchars($search); ?>">

            <select name="category" class="filter-select" onchange="this.form.submit()">
                <option value="">Tất cả danh mục</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['MaLoai']; ?>"
                        <?php echo ($cat_id == $cat['MaLoai']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['TenLoai']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" style="display: none;">Tìm</button>
        </form>

        <a href="add.php" class="btn-create" style="text-decoration: none;">+ Thêm mới</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã SP</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá bán</th>
                    <th>Tồn kho</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $row): ?>
                    <tr>
                        <td><?php echo $row['MaSP']; ?></td>
                        <td>
                            <?php
                            $imgName = $row['HinhAnh'];
                            // Đường dẫn vật lý để PHP kiểm tra file có nằm trên ổ cứng không
                            $physicalPath = "../../../assets/images/Sanpham/" . $imgName;

                            // Đường dẫn URL để trình duyệt hiển thị (Khớp với tên thư mục có dấu cách)
                            $urlPath = "/Web_DoHocTap/assets/images/Sanpham/" . $imgName;
                            $errorUrl = "/Web_DoHocTap/assets/images/error.jpg";

                            if (empty($imgName) || !file_exists($physicalPath)) {
                                $displayImg = $errorUrl;
                            } else {
                                $displayImg = $urlPath;
                            }
                            ?>
                            <img src="<?php echo $displayImg; ?>" class="product-thumb" alt="SP"
                                onerror="this.src='<?php echo $errorUrl; ?>'">
                        </td>
                        <td><strong><?php echo htmlspecialchars($row['TenSP']); ?></strong></td>
                        <td><?php echo $row['TenLoai']; ?></td>
                        <td style="color: var(--danger-color); font-weight: bold;">
                            <?php echo number_format($row['Gia'], 0, ',', '.'); ?>đ
                        </td>
                        <td><?php echo $row['SoLuongTon']; ?></td>
                        <td>
                            <div class="action-group">
                                <a href="edit.php?id=<?php echo $row['MaSP']; ?>" class="btn-action btn-edit" title="Sửa">✎</a>
                                <a href="../../Controller/AdminSanphamController.php?action=delete&id=<?php echo $row['MaSP']; ?>"
                                    class="btn-action btn-delete" title="Xóa"
                                    onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">🗑</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>