<?php
// admin/includes/header.php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';

// Kiểm tra: Nếu chưa đăng nhập HOẶC (không phải Admin VÀ không phải Nhân viên)
if (
    !isset($_SESSION['user_role']) ||
    ($_SESSION['user_role'] !== 'Quản trị viên' && $_SESSION['user_role'] !== 'Nhân viên')
) {

    header("Location: /Web_DoHocTap/Views/Taikhoan/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin - Thiên Đường Dụng Cụ Học Tập</title>
    <style>
        /* Sử dụng lại bộ CSS từ file admin.html của bạn */
        :root {
            --primary-color: #2E7D32;
            --accent-color: #4CAF50;
            --bg-color: #F0F2F5;
            --white: #FFFFFF;
            --text-color: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: var(--bg-color);
        }

        .sidebar {
            width: 260px;
            background-color: var(--primary-color);
            color: var(--white);
            position: fixed;
            height: 100%;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-menu li a {
            display: block;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: var(--accent-color);
            color: #fff;
            border-left: 5px solid #fff;
            padding-left: 20px;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            overflow-y: auto;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        /* Thêm các class stats và table từ file admin.html gốc của bạn vào đây */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-container {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            text-align: left;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .status.green {
            background: #E8F5E9;
            color: #2E7D32;
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Admin</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="/Web_DoHocTap/admin/index.php" class="active">Tổng quan</a></li>
            <li><a href="/Web_DoHocTap/admin/Views/Sanpham/index.php">Quản lý sản phẩm</a></li>
            <li><a href="/Web_DoHocTap/admin/Views/Donhang/index.php">Quản lý đơn hàng</a></li>
            <li><a href="/Web_DoHocTap/admin/Views/Taikhoan/index.php">Khách hàng</a></li>
        </ul>
    </aside>
    <main class="main-content">
        <header class="main-header">
            <div>
                <h1>Tổng quan</h1>
                <p style="color: #777; font-size: 14px;">Chào mừng trở lại, <?php echo $_SESSION['user_name']; ?>!</p>
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <span><?php echo $_SESSION['user_name']; ?></span>
                <a href="/Web_DoHocTap/Views/Taikhoan/logout.php" style="color: #d32f2f; text-decoration: none;">Đăng xuất</a>
            </div>
        </header>