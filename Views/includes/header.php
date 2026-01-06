<?php
// Views/includes/header.php
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa Hàng Dụng Cụ Học Tập</title>
    <style>
        :root {
            --primary-color: #2E7D32;
            --accent-color: #4CAF50;
            --bg-color: #F9F9F9;
            --text-color: #333333;
            --white: #FFFFFF;
        }

        .header,
        .footer {
            background-color: #2E7D32;
            /* Xanh lá đậm thay vì xanh neon */
            color: #FFFFFF;
            /* Chữ màu trắng */
            padding: 20px 0;
        }

        .footer a {
            color: #E8F5E9;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Thanh menu bên dưới Header */
        .navbar {
            background-color: #FFFFFF;
            border-bottom: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        /* Nút Đăng nhập trên menu */
        .btn-login-nav {
            background-color: #4CAF50;
            /* Xanh lá tươi */
            color: white;
            border-radius: 20px;
            padding: 5px 15px;
            border: none;
        }

        h3.section-title {
            color: #2E7D32;
            text-transform: uppercase;
            font-weight: bold;
            margin-top: 40px;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        h3.section-title::after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background-color: #4CAF50;
            margin: 10px auto 0;
        }
    </style>
    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cửa Hàng Thể Thao - Trang Chủ</title>
        <style>
            :root {
                --primary-color: #2E7D32;
                --accent-color: #4CAF50;
                --bg-color: #F9F9F9;
                --text-color: #333333;
                --white: #FFFFFF;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: var(--bg-color);
                color: var(--text-color);
                line-height: 1.6;
            }

            a {
                text-decoration: none;
                color: inherit;
            }

            ul {
                list-style: none;
            }

            .container {
                width: 1200px;
                max-width: 100%;
                margin: 0 auto;
                padding: 0 15px;
            }

            /* HEADER */
            .top-header {
                background-color: var(--primary-color);
                color: var(--white);
                padding: 20px 0;
                text-align: center;
            }

            .top-header h1 {
                font-size: 28px;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            /* MENU */
            .navbar {
                background-color: var(--white);
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
                position: sticky;
                top: 0;
                z-index: 1000;
            }

            .nav-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                height: 60px;
            }

            .nav-links {
                display: flex;
                gap: 20px;
                font-weight: 600;
                font-size: 14px;
            }

            .nav-links li a:hover {
                color: var(--accent-color);
            }

            .search-login-area {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .search-box {
                padding: 5px 10px;
                border: 1px solid #ddd;
                border-radius: 20px;
                outline: none;
                width: 200px;
            }

            .btn-login {
                background-color: var(--accent-color);
                color: var(--white);
                padding: 6px 15px;
                border-radius: 20px;
                font-size: 14px;
                font-weight: bold;
                transition: background 0.3s;
            }

            .btn-login:hover {
                background-color: #388E3C;
            }

            /* BANNER */
            .banner {
                background-color: #E8F5E9;
                padding: 40px 0;
                margin-bottom: 30px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                overflow: hidden;
            }

            .banner-content {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
            }

            .banner-img-side {
                width: 250px;
                height: auto;
            }

            .banner-text {
                text-align: center;
                flex: 1;
            }

            .banner-text h2 {
                font-size: 36px;
                color: #2E7D32;
                margin-bottom: 10px;
                text-transform: uppercase;
            }

            .banner-text p {
                font-size: 18px;
                color: #555;
                margin-bottom: 20px;
            }

            /* SẢN PHẨM */
            .section-title {
                text-align: center;
                text-transform: uppercase;
                color: var(--primary-color);
                margin-bottom: 30px;
                position: relative;
            }

            .section-title::after {
                content: '';
                display: block;
                width: 50px;
                height: 3px;
                background-color: var(--accent-color);
                margin: 10px auto;
            }

            .product-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 20px;
                margin-bottom: 50px;
            }

            .product-card {
                background-color: var(--white);
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                transition: transform 0.3s;
                text-align: center;
                padding-bottom: 15px;
            }

            .product-card:hover {
                transform: translateY(-5px);
            }

            .product-img {
                width: 100%;
                height: 200px;
                background-color: #ddd;
                margin-bottom: 10px;
            }

            .product-name {
                font-weight: bold;
                margin-bottom: 5px;
                font-size: 15px;
            }

            .product-price {
                color: #d32f2f;
                font-weight: bold;
            }

            .btn-view-all {
                display: block;
                width: 150px;
                margin: 0 auto 50px;
                padding: 10px;
                text-align: center;
                background-color: #ddd;
                color: #333;
                border-radius: 5px;
                font-weight: 600;
            }

            .btn-view-all:hover {
                background-color: #ccc;
            }

            .btn-buy-now {
                background-color: #4CAF50;
                color: white;
                border: none;
                padding: 8px 20px;
                border-radius: 20px;
                font-weight: bold;
                cursor: pointer;
                margin-top: 10px;
                width: 80%;
                transition: 0.3s;
            }

            .btn-buy-now {
                background-color: #4CAF50;
                color: white;
                border: none;
                padding: 10px 25px;
                border-radius: 25px;
                font-weight: bold;
                font-size: 14px;
                cursor: pointer;
                margin-top: 15px;
                width: auto;
                min-width: 100px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .btn-buy-now:hover {
                background-color: #2E7D32;
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            }

            /* TIN TỨC */
            .news-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                margin-bottom: 50px;
            }

            .news-card img {
                width: 100%;
                height: 150px;
                object-fit: cover;
                background-color: #ddd;
                border-radius: 5px;
            }

            .news-title {
                margin-top: 10px;
                font-weight: bold;
                font-size: 14px;
            }

            /* THƯƠNG HIỆU */
            .brands-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 50px;
                gap: 10px;
            }

            .brand-box {
                background-color: var(--accent-color);
                color: white;
                flex: 1;
                padding: 15px;
                text-align: center;
                border-radius: 4px;
                font-size: 12px;
            }

            /* Index */
            .section-wrapper {
                margin: 30px auto;
                padding: 20px;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }

            .section-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                margin-bottom: 20px;
                border-bottom: 2px solid #f0f0f0;
                padding-bottom: 10px;
            }

            .product-slider {
                display: flex;
                overflow-x: hidden;
                scroll-behavior: smooth;
                gap: 15px;
            }

            .slider-item {
                min-width: calc(25% - 12px);
                position: relative;
            }

            .nav-arrow {
                position: absolute;
                width: 35px;
                height: 35px;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 50%;
                cursor: pointer;
                z-index: 10;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            .prev {
                left: 20px;

            }

            .next {
                right: 10px;
            }

            .discount-badge {
                position: absolute;
                top: 10px;
                right: 10px;
                background: #d32f2f;
                color: #fff;
                font-size: 11px;
                padding: 5px;
                border-radius: 50%;
                z-index: 5;
            }

            /* FOOTER */
            footer {
                background-color: var(--primary-color);
                color: var(--white);
                padding: 40px 0;
                font-size: 14px;
            }

            .footer-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 30px;
            }

            .footer-col h4 {
                margin-bottom: 15px;
                text-transform: uppercase;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                padding-bottom: 10px;
                display: inline-block;
            }

            .footer-col ul li {
                margin-bottom: 8px;
            }

            .footer-col ul li a:hover {
                text-decoration: underline;
            }

            /* thông báo */
            #toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
            }

            .toast {
                background-color: #2E7D32;
                color: white;
                padding: 16px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                gap: 10px;
                font-weight: bold;
                animation: slideIn 0.3s ease-out;
            }

            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }

                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            /* Tên đăng nhập */
            .user-info-nav span {
                font-size: 14px;
                background: #E8F5E9;
                padding: 5px 12px;
                border-radius: 15px;
                border: 1px solid #4CAF50;
            }

            .user-dropdown {
                position: relative;
                display: inline-block;
            }

            .user-name-btn {
                background: #E8F5E9;
                padding: 5px 15px;
                border-radius: 20px;
                border: 1px solid #4CAF50;
                color: #2E7D32;
                font-weight: bold;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            /* Menu ẩn */
            .dropdown-content {
                display: none;
                position: absolute;
                right: 0;
                background-color: #ffffff;
                min-width: 160px;
                box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                z-index: 1000;
                margin-top: 10px;
                overflow: hidden;
            }

            .dropdown-content a {
                color: #333;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
                font-size: 14px;
                border-bottom: 1px solid #eee;
                transition: background 0.2s;
            }

            .dropdown-content a:hover {
                background-color: #f1f1f1;
                color: #2E7D32;
            }

            .show {
                display: block;
                animation: fadeIn 0.2s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .cart-badge {
                position: absolute;
                top: -5px;
                right: -8px;
                background-color: #d32f2f;
                color: white;
                font-size: 10px;
                font-weight: bold;
                padding: 2px 4px;
                border-radius: 50%;
                min-width: 16px;
                height: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 1px solid white;
                z-index: 100;
            }
        </style>
    </head>

<body>
    <header class="top-header">
        <div class="container">
            <h1>THIÊN ĐƯỜNG DỤNG CỤ HỌC TẬP</h1>
        </div>
    </header>
    <nav class="navbar">
        <div class="container nav-content">
            <ul class="nav-links">
                <li><a href="/Web_DoHocTap/index.php">Trang chủ</a></li>
                <li><a href="/Web_DoHocTap/Views/Sanpham/sanpham.php">Sản phẩm</a></li>
                <li><a href="#">Khuyến mãi</a></li>
                <li><a href="#gioithieu">Giới thiệu</a></li>
                <li><a href="#">Tin tức</a></li>
            </ul>
            <div class="search-login-area">
                <form action="/Web_DoHocTap/index.php" method="GET">
                    <input type="text" name="search" class="search-box" placeholder="Tìm kiếm bút, vở...">
                </form>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-dropdown">
                        <div class="user-name-btn" onclick="toggleDropdown()">
                            Chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?> <span style="font-size: 10px; margin-left: 5px;"></span>
                        </div>
                        <div id="myDropdown" class="dropdown-content">
                            <a href="/Web_DoHocTap/Views/Donhang/lichsu_donhang.php">📦 Đơn hàng của tôi</a>
                            <a href="/Web_DoHocTap/Views/Taikhoan/settings.php">⚙️ Cài đặt tài khoản</a>
                            <a href="/Web_DoHocTap/Views/Taikhoan/logout.php" style="color: #d32f2f;">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/Web_DoHocTap/Views/Taikhoan/login.php" class="btn-login">Đăng nhập</a>
                <?php endif; ?>
                <a href="/Web_DoHocTap/Views/giohang.php" style="text-decoration: none; position: relative; display: inline-flex; align-items: center; font-size: 22px;">🛒
                    <?php
                    $count = 0;
                    if (isset($_SESSION['user_id'])) {
                        require_once __DIR__ . '/../../classes/Giohang.class.php';
                        require_once __DIR__ . '/../../classes/Chitiet_Giohang.class.php';
                        $ghModel = new Giohang();
                        $ctghModel = new Chitiet_Giohang();
                        $gioHang = $ghModel->lay_theo_khach_hang($_SESSION['user_id']);
                        if ($gioHang) {
                            $items = $ctghModel->lay_danh_sach_trong_gio($gioHang['MaGH']);
                            foreach ($items as $item) {
                                $count += $item['SoLuong'];
                            }
                        }
                    } else {
                        if (isset($_SESSION['cart'])) {
                            $count = array_sum($_SESSION['cart']);
                        }
                    }
                    if ($count > 0) {
                        echo "<span class='cart-badge'>$count</span>";
                    }
                    ?>
                </a>
            </div>
        </div>
    </nav>
    <script>
        function toggleDropdown() {
            document.getElementById("myDropdown").classList.toggle("show");
        }
        window.onclick = function(event) {
            if (!event.target.matches('.user-name-btn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>