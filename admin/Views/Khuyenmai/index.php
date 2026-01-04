<?php
// admin/Views/Khuyenmai/index.php
include_once '../../includes/header.php';
if ($_SESSION['user_role'] !== 'Quản trị viên') {
    header("Location: /Web_DoHocTap/admin/index.php");
    exit();
}
$db = new Db();
$promotions = $db->query("SELECT * FROM khuyenmai ORDER BY NgayKetThuc DESC")->fetchAll();
?>

<div class="main-content-inner">
    <header class="main-header">
        <h2>Quản lý Khuyến mãi</h2>
        <a href="add.php" class="btn-filter" style="background: var(--primary-color); text-decoration: none;">+ Tạo khuyến mãi mới</a>
    </header>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tên chương trình</th>
                    <th>Giảm (%)</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($promotions as $km):
                    $isExpired = strtotime($km['NgayKetThuc']) < time();
                ?>
                    <tr>
                        <td><strong><?php echo $km['TenKM']; ?></strong></td>
                        <td><span class="status green"><?php echo $km['PhanTramGiam']; ?>%</span></td>
                        <td><?php echo date('d/m/Y', strtotime($km['NgayBatDau'])); ?> - <?php echo date('d/m/Y', strtotime($km['NgayKetThuc'])); ?></td>
                        <td>
                            <?php echo $isExpired ? '<span style="color:red;">Hết hạn</span>' : '<span style="color:green;">Đang chạy</span>'; ?>
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="edit.php?id=<?php echo $km['MaKM']; ?>" class="btn-action btn-edit" style="border:none;">Sửa</a>
                                <a href="../../controller/AdminKhuyenmaiController.php?action=delete&id=<?php echo $km['MaKM']; ?>"
                                    class="btn-action btn-delete" style="border:none;" onclick="return confirm('Xóa khuyến mãi này?')">Xóa</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>