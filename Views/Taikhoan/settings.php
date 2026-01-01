<?php
session_start();
require_once '../../classes/DB.class.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$db = new Db();
$maKH = $_SESSION['user_id'];
$message = "";

// Xử lý khi người dùng nhấn lưu thông tin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoTen = $_POST['hoTen'];
    $sdt = $_POST['sdt'];
    $diaChi = $_POST['diaChi'];

    $sql = "UPDATE khachhang SET HoTen = ?, SDT = ?, DiaChi = ? WHERE MaKH = ?";
    $db->query($sql, [$hoTen, $sdt, $diaChi, $maKH]);

    // Cập nhật lại tên hiển thị trên Header ngay lập tức
    $_SESSION['user_name'] = $hoTen;
    $message = "Cập nhật thông tin thành công!";
}

// Lấy thông tin hiện tại từ database
$user = $db->query("SELECT * FROM khachhang WHERE MaKH = ?", [$maKH])->fetch();

include_once '../includes/header.php';
?>

<div class="container" style="margin-top: 50px; margin-bottom: 50px; display: flex; justify-content: center;">
    <div style="width: 100%; max-width: 600px; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
        <h2 style="color: var(--primary-color); text-align: center; margin-bottom: 30px;">
            Cài đặt tài khoản
        </h2>

        <?php if ($message): ?>
            <div style="background: #E8F5E9; color: #2E7D32; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="settingsForm">
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Họ và tên:</label>
                <p id="view_hoTen" style="font-size: 16px; color: #333; margin: 0;"><?php echo htmlspecialchars($user['HoTen']); ?></p>
                <input type="text" name="hoTen" id="edit_hoTen" value="<?php echo htmlspecialchars($user['HoTen']); ?>"
                    style="display: none; width: 100%; padding: 10px; border: 1px solid #4caf50; border-radius: 5px;" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Số điện thoại:</label>
                <p id="view_sdt" style="font-size: 16px; color: #333; margin: 0;"><?php echo htmlspecialchars($user['SDT'] ?? 'Chưa cập nhật'); ?></p>
                <input type="text" name="sdt" id="edit_sdt" value="<?php echo htmlspecialchars($user['SDT']); ?>"
                    style="display: none; width: 100%; padding: 10px; border: 1px solid #4caf50; border-radius: 5px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Email đăng ký:</label>
                <p style="font-size: 16px; color: #999; margin: 0;"><?php echo htmlspecialchars($user['Email']); ?></p>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Địa chỉ giao hàng:</label>
                <p id="view_diaChi" style="font-size: 16px; color: #333; margin: 0;"><?php echo htmlspecialchars($user['DiaChi'] ?? 'Chưa cập nhật'); ?></p>
                <textarea name="diaChi" id="edit_diaChi" rows="3"
                    style="display: none; width: 100%; padding: 10px; border: 1px solid #4caf50; border-radius: 5px;"><?php echo htmlspecialchars($user['DiaChi']); ?></textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" id="btn_edit" onclick="toggleEditMode(true)" class="btn-buy-now"
                    style="flex: 1; background: #4caf50; border: none; padding: 12px; color: white; border-radius: 5px; cursor: pointer;">
                    Chỉnh sửa thông tin
                </button>

                <button type="submit" id="btn_save" style="display: none; flex: 1; background: #2e7d32; border: none; padding: 12px; color: white; border-radius: 5px; cursor: pointer; font-weight: bold;">
                    LƯU THAY ĐỔI
                </button>

                <button type="button" id="btn_cancel" onclick="toggleEditMode(false)"
                    style="display: none; flex: 1; background: #e0e0e0; border: none; padding: 12px; color: #333; border-radius: 5px; cursor: pointer;">
                    HỦY
                </button>
            </div>
        </form>

    </div>
</div>
<script>
    function toggleEditMode(isEditing) {
        // Các ID cần ẩn/hiện
        const fields = ['hoTen', 'sdt', 'diaChi'];

        fields.forEach(field => {
            document.getElementById('view_' + field).style.display = isEditing ? 'none' : 'block';
            document.getElementById('edit_' + field).style.display = isEditing ? 'block' : 'none';
        });

        // Điều khiển các nút
        document.getElementById('btn_edit').style.display = isEditing ? 'none' : 'block';
        document.getElementById('btn_save').style.display = isEditing ? 'block' : 'none';
        document.getElementById('btn_cancel').style.display = isEditing ? 'block' : 'none';
    }
</script>

<?php include_once '../includes/footer.php'; ?>