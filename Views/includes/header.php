<?php
// vị trí Views/includes/header.php
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
            /* Link trong footer màu trắng ngà */
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Thanh menu bên dưới Header */
        .navbar {
            background-color: #FFFFFF;
            border-bottom: 1px solid #ddd;
            /* Đường gạch chân nhẹ */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            /* Bóng mờ nhẹ */
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

        /* Tiêu đề section: "Sản phẩm mới", "Sản phẩm nổi bật" */
        h3.section-title {
            color: #2E7D32;
            /* Đồng bộ màu với Header */
            text-transform: uppercase;
            font-weight: bold;
            margin-top: 40px;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        /* Tạo dấu gạch chân nhỏ dưới tiêu đề cho đẹp */
        h3.section-title::after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background-color: #4CAF50;
            margin: 10px auto 0;
            /* Căn giữa gạch chân */
        }
    </style>
    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cửa Hàng Thể Thao - Trang Chủ</title>
        <style>
            /* --- 1. CÀI ĐẶT CHUNG & BIẾN MÀU SẮC --- */
            :root {
                --primary-color: #2E7D32;
                /* Xanh lá đậm (Header/Footer) */
                --accent-color: #4CAF50;
                /* Xanh lá tươi (Nút bấm/Điểm nhấn) */
                --bg-color: #F9F9F9;
                /* Màu nền trang (Xám rất nhạt) */
                --text-color: #333333;
                /* Màu chữ chính */
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

            /* Class tiện ích để căn giữa nội dung */
            .container {
                width: 1200px;
                max-width: 100%;
                margin: 0 auto;
                padding: 0 15px;
            }

            /* --- 2. HEADER (PHẦN ĐẦU TRANG) --- */
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

            /* --- 3. MENU ĐIỀU HƯỚNG (NAVBAR) --- */
            .navbar {
                background-color: var(--white);
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
                position: sticky;
                /* Dính menu lên đầu khi cuộn */
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

            /* --- 4. BANNER --- */
            .banner {
                background-color: #E8F5E9;
                /* Màu xanh nhạt của poster */
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



            /* --- 5. KHU VỰC SẢN PHẨM (CHUNG) --- */
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
                /* Màu xanh lá tươi giống Poster */
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
                /* Xanh lá tươi đồng bộ Poster */
                color: white;
                border: none;
                padding: 10px 25px;
                border-radius: 25px;
                /* Bo tròn hoàn toàn giống nút Mua Ngay */
                font-weight: bold;
                font-size: 14px;
                cursor: pointer;
                margin-top: 15px;
                width: auto;
                /* Chiều rộng tự động theo chữ */
                min-width: 100px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                /* Đổ bóng nhẹ cho nổi bật */
                transition: all 0.3s ease;
                text-transform: uppercase;
                /* Chữ in hoa giống Poster */
                letter-spacing: 1px;
            }

            .btn-buy-now:hover {
                background-color: #2E7D32;
                /* Đậm hơn khi di chuột giống Header */
                transform: translateY(-2px);
                /* Nhích nhẹ lên khi hover */
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            }

            /* --- 6. TIN TỨC --- */
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

            /* --- 7. THƯƠNG HIỆU --- */
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

            /* --- 8. FOOTER (CHÂN TRANG) --- */
            footer {
                background-color: var(--primary-color);
                color: var(--white);
                padding: 40px 0;
                font-size: 14px;
            }

            .footer-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                /* 4 cột thông tin */
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

            /* Thiết lập vị trí và giao diện cho thông báo ở góc */
            #toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
            }

            .toast {
                background-color: #2E7D32;
                /* Màu xanh lá đậm đồng bộ với Header */
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
                <li><a href="Views/Sanpham/sanpham.php">Sản phẩm</a></li>
                <li><a href="#">Khuyến mãi</a></li>
                <li><a href="#gioithieu">Giới thiệu</a></li>
                <li><a href="#">Tin tức</a></li>
            </ul>

            <div class="search-login-area">
                <form action="/Web_DoHocTap/index.php" method="GET">
                    <input type="text" name="search" class="search-box" placeholder="Tìm kiếm bút, vở...">
                </form>
                <a href="#" class="btn-login">Đăng nhập</a>
                <a href="/Web_DoHocTap/Views/giohang.php" style="text-decoration: none;">🛒</a>
            </div>
        </div>
    </nav>