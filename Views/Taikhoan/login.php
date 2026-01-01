<?php
// Views/Taikhoan/login.php
session_start();

require_once __DIR__ . '/../../classes/DB.class.php';
require_once __DIR__ . '/../../classes/Giohang.class.php';
require_once __DIR__ . '/../../classes/Chitiet_Giohang.class.php';
require_once __DIR__ . '/../../classes/Sanpham.class.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new Db();

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT tk.*, kh.MaKH, kh.HoTen FROM taikhoan tk 
          LEFT JOIN khachhang kh ON tk.MaTK = kh.MaTK 
          WHERE tk.TenDangNhap = ? AND tk.MatKhau = ?";

    $stmt = $db->query($query, [$username, $password]);
    $user = $stmt->fetch();

    if ($user) {

        $_SESSION['user_id'] = $user['MaKH'];
        $_SESSION['user_name'] = !empty($user['HoTen']) ? $user['HoTen'] : $user['TenDangNhap'];

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $ghModel = new Giohang();
            $ctghModel = new Chitiet_Giohang();
            $spModel = new Sanpham();
            $gioHang = $ghModel->lay_theo_khach_hang($user['MaKH']);
            if (!$gioHang) {
                $maGH = $ghModel->tao_moi($user['MaKH']);
            } else {
                $maGH = $gioHang['MaGH'];
            }

            foreach ($_SESSION['cart'] as $maSP => $sl) {
                $sp = $spModel->getById($maSP);
                if ($sp) {
                    $ctghModel->them_san_pham($maGH, $maSP, $sl, $sp['Gia']);
                }
            }
            // Tính lại tổng tiền 
            if (method_exists($ghModel, 'tinh_lai_tong_tien')) {
                $ghModel->tinh_lai_tong_tien($maGH);
            }
            unset($_SESSION['cart']);
        }
        if (isset($_GET['redirect']) && $_GET['redirect'] == 'giohang') {

            header("Location: ../giohang.php");
        } else {

            header("Location: ../../index.php");
        }
        exit();
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <style>
        body {
            background-color: #E8F5E9;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #FFFFFF;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        h2 {
            color: #2E7D32;
            margin-bottom: 30px;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .error-msg {
            color: #d32f2f;
            font-size: 13px;
            margin-bottom: 15px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Đăng Nhập</h2>
        <?php if ($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label>Tên tài khoản</label>
            <input type="text" name="username" required>
            <label>Mật khẩu</label>
            <input type="password" name="password" required>
            <button type="submit" class="btn-login">Đăng nhập</button>
        </form>
    </div>
</body>

</html>