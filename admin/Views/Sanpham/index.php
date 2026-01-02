<?php
include_once '../../includes/header.php';
$db = new Db();

// Lấy danh sách sản phẩm kèm tên loại
$sql = "SELECT sp.*, l.TenLoai FROM sanpham sp 
        JOIN loaisp l ON sp.MaLoai = l.MaLoai 
        ORDER BY sp.MaSP DESC";
$products = $db->query($sql)->fetchAll();
?>

<div class="main-content-inner">
    <header class="main-header">
        <h2>Danh sách sản phẩm</h2>
        <div style="font-size: 14px;">Số lượng: <strong><?php echo count($products); ?></strong></div>
    </header>

    <div class="toolbar">
        <div class="search-group">
            <input type="text" class="search-input" placeholder="Tìm tên sản phẩm...">
            <select class="filter-select">
                <option value="">Tất cả danh mục</option>
            </select>
        </div>
        <a href="add.php" class="btn-create" style="text-decoration: none;">+ Thêm mới</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
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
                            <img src="/Web_DoHocTap/assets/images/Sanpham/<?php echo $row['HinhAnh']; ?>"
                                class="product-thumb" alt="SP" onerror="this.src='/Web_DoHocTap/assets/images/no-image.png'">
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