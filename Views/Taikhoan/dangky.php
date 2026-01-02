<?php
// Views/Taikhoan/dangky.php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new Db();

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Mật khẩu nhập lại không khớp!";
    } else {
        // Kiểm tra tên đăng nhập tồn tại
        $check = $db->query("SELECT * FROM taikhoan WHERE TenDangNhap = ?", [$username])->fetch();
        if ($check) {
            $error = "Tên tài khoản này đã được sử dụng!";
        } else {
            try {
                // 1. Thêm vào bảng taikhoan (Sử dụng cột VaiTro)
                $sqlTK = "INSERT INTO taikhoan (TenDangNhap, MatKhau, Email, VaiTro) VALUES (?, ?, ?, 'Khách hàng')";
                $db->query($sqlTK, [$username, $password, $email]);
                $maTK = $db->lastInsertId();

                // 2. Thêm vào bảng khachhang
                $sqlKH = "INSERT INTO khachhang (MaTK, Email, HoTen) VALUES (?, ?, ?)";
                $db->query($sqlKH, [$maTK, $email, $username]);

                $success = "Đăng ký thành công! Đang chuyển hướng...";
                header("refresh:2;url=login.php");
            } catch (Exception $e) {
                $error = "Lỗi hệ thống: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng Ký Tài Khoản</title>
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

        .register-container {
            background-color: #FFFFFF;
            padding: 30px 40px;

            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            width: 380px;
            text-align: center;
            box-sizing: border-box;
        }

        h2 {
            color: #2E7D32;
            margin-bottom: 25px;
            font-size: 24px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px 20px;

            border: 1px solid #C8E6C9;
            border-radius: 25px;

            box-sizing: border-box;
            background-color: #F1F8E9;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.2);
        }

        /* Kiểu chung cho các nút dạng viên thuốc (pill) */
        /* Kiểu dáng nút thu gọn bằng với ô nhập liệu */
        .btn {
            width: 100%;
            /* Đảm bảo nút chiếm hết không gian bên trong container đã có padding */
            max-width: 100%;
            /* Giới hạn không vượt quá ô nhập liệu */
            padding: 12px;
            /* Giảm độ dày để nút thanh thoát hơn, bằng với padding của input */
            border: none;
            border-radius: 25px;
            /* Bo tròn đồng bộ với các ô nhập liệu */
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 12px;
            transition: all 0.3s ease;
            display: block;
            text-decoration: none;
            box-sizing: border-box;
            /* Quan trọng để width tính cả padding */
            text-align: center;
        }

        /* Nút Đăng ký màu xanh đậm */
        .btn-register {
            background-color: #4CAF50;
            color: white;
        }

        .btn-register:hover {
            background-color: #388E3C;
            transform: translateY(-1px);
        }

        /* Nút Đăng nhập màu xanh nhạt hơn một chút */
        .btn-login {
            background-color: #66BB6A;
            color: white;
        }

        .btn-login:hover {
            background-color: #43A047;
            transform: translateY(-1px);
        }

        .error-msg {
            color: #d32f2f;
            background: #FFEBEE;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .success-msg {
            color: #2E7D32;
            background: #E8F5E9;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Đăng ký</h2>
        <?php if ($error) echo "<div class='error-msg'>$error</div>"; ?>
        <?php if ($success) echo "<div class='success-msg'>$success</div>"; ?>

        <form method="POST">
            <div class="form-group">
                <label>Tên tài khoản</label>
                <input type="text" name="username" placeholder="Nhập tên tài khoản" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Nhập email" required>
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>
            <div class="form-group">
                <label>Nhập lại mật khẩu</label>
                <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
            </div>

            <button type="submit" class="btn btn-register">Đăng ký</button>
            <a href="login.php" class="btn btn-login">Đăng nhập</a>
        </form>
    </div>
</body>

</html>