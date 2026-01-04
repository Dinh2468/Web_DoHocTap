<?php
// admin/Views/Danhgia/index.php
include_once '../../includes/header.php';
if ($_SESSION['user_role'] !== 'Quản trị viên') {
    header("Location: /Web_DoHocTap/admin/index.php");
    exit();
}
$db = new Db();

$sql = "SELECT dg.*, kh.HoTen, sp.TenSP, sp.HinhAnh 
        FROM danhgia dg
        JOIN khachhang kh ON dg.MaKH = kh.MaKH
        JOIN sanpham sp ON dg.MaSP = sp.MaSP
        ORDER BY dg.NgayDG DESC";
$reviews = $db->query($sql)->fetchAll();
?>

<div class="main-content-inner">
    <header class="main-header">
        <h2>Quản lý Đánh giá khách hàng</h2>
    </header>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Khách hàng</th>
                    <th>Số sao</th>
                    <th>Nội dung</th>
                    <th>Ngày đánh giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $dg): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="/Web_DoHocTap/assets/images/Sanpham/<?php echo $dg['HinhAnh']; ?>" width="40" style="border-radius: 4px;">
                                <span style="font-size: 13px;"><?php echo $dg['TenSP']; ?></span>
                            </div>
                        </td>
                        <td><strong><?php echo $dg['HoTen']; ?></strong></td>
                        <td>
                            <div style="color: #FFB300;">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $dg['SoSao'] ? '★' : '☆';
                                }
                                ?>
                            </div>
                        </td>
                        <td style="max-width: 300px; font-size: 14px; color: #555;">
                            <?php echo $dg['NoiDung']; ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($dg['NgayDG'])); ?></td>
                        <td>
                            <div class="action-group">
                                <a href="../../controller/AdminDanhgiaController.php?action=delete&id=<?php echo $dg['MaDG']; ?>"
                                    class="btn-action btn-delete" style="border:none;"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">Xóa</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>