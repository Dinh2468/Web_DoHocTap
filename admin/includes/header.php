<?php
// admin/includes/header.php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';
$current_page = $_SERVER['REQUEST_URI'];
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

        /* Bổ sung vào cuối thẻ <style> của admin/includes/header.php */

        /* Thu nhỏ ảnh sản phẩm trong danh sách */
        .product-thumb {
            width: 80px;
            height: 80px;
            object-fit: contain;
            /* Giữ nguyên tỉ lệ ảnh không bị bóp méo */
            border: 1px solid #eee;
            border-radius: 5px;
            background: #fff;
        }

        /* Thanh công cụ tìm kiếm và bộ lọc */
        .toolbar {
            background: var(--white);
            padding: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .search-input,
        .filter-select {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
        }

        /* Nút thêm mới sản phẩm */
        .btn-create {
            background: var(--accent-color);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-create:hover {
            background: #388E3C;
        }

        /* Các nút hành động Sửa/Xóa */
        .action-group {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 16px;
        }

        .btn-edit {
            background: #E3F2FD;
            color: #1976D2;
        }

        .btn-delete {
            background: #FFEBEE;
            color: #C62828;
        }

        .form-container {
            background: var(--white);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Sắp xếp các ô nhập liệu trên cùng 1 hàng */
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Nhãn tiêu đề ô nhập */
        .form-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
            font-size: 14px;
        }

        /* Ô nhập liệu và Select */
        .form-control {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            font-size: 15px;
            transition: border-color 0.3s;
            background-color: #fcfcfc;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        /* Nút Lưu sản phẩm đồng bộ với nút Thêm mới */
        .btn-save {
            background-color: var(--accent-color);
            color: white;
            padding: 12px 35px;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
            display: inline-block;
        }

        .btn-save:hover {
            background-color: #388E3C;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }



        /* Tối ưu thanh công cụ lọc */
        .toolbar form {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Đồng bộ nút Lọc */
        .btn-filter {
            background-color: var(--accent-color);
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-filter:hover {
            background-color: #388E3C;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Đồng bộ nút Xóa lọc */
        .btn-clear {
            background-color: #f0f0f0;
            color: #555;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            font-size: 14px;
            transition: 0.3s;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-clear:hover {
            background-color: #e0e0e0;
            color: #333;
            border-color: #ccc;
        }

        /* admin/includes/header.php */

        .btn-print {
            background-color: #455a64;
            /* Màu xám đậm chuyên nghiệp */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            /* Bo tròn đồng bộ với các nút khác */
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .btn-print:hover {
            background-color: #263238;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }

        @media print {

            /* Ẩn tất cả các thành phần không cần thiết khi in */
            .btn-print,
            .btn-save,
            .sidebar,
            .main-header,
            .btn-save,
            .btn-clear,
            form label,
            select,
            form button,
            .main-content-inner>header a {
                display: none !important;
            }

            /* Điều chỉnh lại layout chính để chiếm toàn bộ trang in */
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }

            .main-content-inner {
                background: white !important;
                padding: 0 !important;
            }

            /* Hiển thị rõ các khung thông tin */
            .table-container {
                box-shadow: none !important;
                border: 1px solid #eee !important;
                margin-bottom: 20px !important;
            }

            .stats-grid {
                display: block !important;
                /* Xếp chồng thông tin khách và đơn hàng để dễ đọc hơn khi in */
            }

            h2,
            h3 {
                color: black !important;
            }

            /* Đảm bảo bảng in rõ nét */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            th,
            td {
                border-bottom: 1px solid #ddd !important;
            }
        }

        /* Khách hàng */
        /* CSS cho nút gạt trạng thái */
        .switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: var(--accent-color);
        }

        input:checked+.slider:before {
            transform: translateX(14px);
        }

        /* Avatar hình tròn */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #E8F5E9;
            color: var(--primary-color);
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }

        .btn-view-profile {
            background-color: transparent;
            color: #555;
            border: 1px solid #ddd;
            padding: 6px 14px;
            border-radius: 6px;
            /* Bo góc nhẹ */
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-view-profile:hover {
            background-color: #f8f9fa;
            border-color: var(--primary-color);
            color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            width: 800px;
            display: flex;
            /* Chia đôi modal: trái info, phải lịch sử */
            border-radius: 12px;
            overflow: hidden;
            animation: fadeIn 0.3s ease;
        }

        .profile-side {
            flex: 1;
            background: #f8f9fa;
            padding: 30px;
            border-right: 1px solid #eee;
        }

        .history-side {
            flex: 2;
            padding: 30px;
        }

        .large-avatar {
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            color: white;
            font-size: 32px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 15px;
        }

        .stat-row {
            margin-bottom: 12px;
            font-size: 14px;
        }

        .stat-row span {
            color: #777;
            display: block;
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Admin</h2>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="/Web_DoHocTap/admin/index.php"
                    class="<?php echo (strpos($current_page, 'admin/index.php') !== false) ? 'active' : ''; ?>">
                    Tổng quan
                </a>
            </li>
            <li>
                <a href="/Web_DoHocTap/admin/Views/Sanpham/index.php"
                    class="<?php echo (strpos($current_page, 'Views/Sanpham') !== false) ? 'active' : ''; ?>">
                    Quản lý sản phẩm
                </a>
            </li>
            <li>
                <a href="/Web_DoHocTap/admin/Views/Donhang/index.php"
                    class="<?php echo (strpos($current_page, 'Views/Donhang') !== false) ? 'active' : ''; ?>">
                    Quản lý đơn hàng
                </a>
            </li>
            <li>
                <a href="/Web_DoHocTap/admin/Views/Khachhang/index.php"
                    class="<?php echo (strpos($current_page, 'Views/Khachhang') !== false) ? 'active' : ''; ?>">
                    Khách hàng
                </a>
            </li>
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
        <hr><br>