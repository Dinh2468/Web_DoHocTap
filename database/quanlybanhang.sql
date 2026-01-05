-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 05, 2026 at 04:44 AM
-- Server version: 9.2.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quanlybanhang`
--

-- --------------------------------------------------------

--
-- Table structure for table `chitietdh`
--

DROP TABLE IF EXISTS `chitietdh`;
CREATE TABLE IF NOT EXISTS `chitietdh` (
  `MaDH` int NOT NULL,
  `MaSP` int NOT NULL,
  `SoLuong` int DEFAULT NULL,
  `DonGia` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`MaDH`,`MaSP`),
  KEY `MaSP` (`MaSP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chitietdh`
--

INSERT INTO `chitietdh` (`MaDH`, `MaSP`, `SoLuong`, `DonGia`) VALUES
(1, 1, 6, 5000.00),
(7, 31, 1, 12000.00),
(8, 30, 1, 7000.00),
(9, 32, 1, 6000.00),
(10, 11, 1, 45000.00),
(11, 32, 1, 6000.00),
(14, 32, 1, 6000.00),
(15, 4, 1, 450000.00),
(16, 16, 1, 820000.00),
(17, 25, 10, 15000.00),
(18, 1, 20, 5000.00),
(19, 3, 10, 25000.00),
(20, 25, 1, 15000.00),
(21, 11, 1, 45000.00),
(22, 12, 1, 12000.00),
(23, 3, 1, 25000.00),
(24, 1, 1, 5000.00),
(25, 11, 1, 45000.00),
(26, 12, 1, 12000.00),
(27, 3, 1, 25000.00),
(28, 16, 1, 820000.00),
(29, 1, 1, 5000.00),
(30, 4, 1, 450000.00),
(31, 16, 1, 820000.00),
(32, 21, 1, 520000.00),
(33, 24, 1, 1200000.00),
(34, 23, 1, 650000.00),
(35, 30, 1, 7000.00),
(35, 31, 1, 12000.00),
(35, 32, 1, 6000.00),
(35, 44, 2, 28000.00),
(36, 4, 1, 450000.00),
(37, 11, 1, 45000.00),
(38, 4, 2, 450000.00),
(38, 11, 1, 45000.00);

-- --------------------------------------------------------

--
-- Table structure for table `chitietgh`
--

DROP TABLE IF EXISTS `chitietgh`;
CREATE TABLE IF NOT EXISTS `chitietgh` (
  `MaGH` int NOT NULL,
  `MaSP` int NOT NULL,
  `SoLuong` int DEFAULT NULL,
  `DonGia` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`MaGH`,`MaSP`),
  KEY `MaSP` (`MaSP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chitietgh`
--

INSERT INTO `chitietgh` (`MaGH`, `MaSP`, `SoLuong`, `DonGia`) VALUES
(18, 2, 1, 12000.00);

-- --------------------------------------------------------

--
-- Table structure for table `chitietnh`
--

DROP TABLE IF EXISTS `chitietnh`;
CREATE TABLE IF NOT EXISTS `chitietnh` (
  `MaNH` int NOT NULL,
  `MaSP` int NOT NULL,
  `SoLuong` int DEFAULT NULL,
  `DonGia` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`MaNH`,`MaSP`),
  KEY `MaSP` (`MaSP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chitietnh`
--

INSERT INTO `chitietnh` (`MaNH`, `MaSP`, `SoLuong`, `DonGia`) VALUES
(1, 1, 1000, 4000.00),
(1, 2, 80, 10000.00),
(2, 1, 1, 0.00),
(3, 13, 1, 124.00),
(4, 3, 17, 5999.00),
(4, 14, 188, 57.00),
(4, 15, 1, 6.00);

-- --------------------------------------------------------

--
-- Table structure for table `danhgia`
--

DROP TABLE IF EXISTS `danhgia`;
CREATE TABLE IF NOT EXISTS `danhgia` (
  `MaDG` int NOT NULL AUTO_INCREMENT,
  `MaKH` int DEFAULT NULL,
  `MaSP` int DEFAULT NULL,
  `MaDH` int NOT NULL,
  `SoSao` int DEFAULT NULL,
  `NoiDung` text,
  `NgayDG` date DEFAULT NULL,
  PRIMARY KEY (`MaDG`),
  KEY `MaKH` (`MaKH`),
  KEY `MaSP` (`MaSP`),
  KEY `fk_danhgia_donhang` (`MaDH`)
) ;

--
-- Dumping data for table `danhgia`
--

INSERT INTO `danhgia` (`MaDG`, `MaKH`, `MaSP`, `MaDH`, `SoSao`, `NoiDung`, `NgayDG`) VALUES
(1, 1, 4, 1, 5, 'Balo rất đẹp, con mình rất thích', '2025-12-21'),
(3, 2, 4, 1, 5, 'Con mình đeo không bị mỏi vai, rất đáng tiền.', '2025-12-26'),
(4, 1, 11, 1, 5, 'Mực viết cực kỳ mượt và nhanh khô, không bị lem.', '2025-12-27'),
(5, 2, 11, 1, 4, 'Bút cầm chắc tay, thiết kế đẹp.', '2025-12-28'),
(6, 2, 3, 1, 5, 'Thước nhựa dẻo rất bền, bé nhà mình bẻ không gãy.', '2025-12-29'),
(8, 1, 4, 1, 3, 'Sản phẩm dùng tạm được, tuy nhiên màu sắc hơi khác so với ảnh mẫu.', '2026-01-01'),
(9, 4, 21, 32, 5, 'máy tính tốt', '2026-01-05'),
(10, 1, 11, 25, 4, 'BÚT VIẾT OK\r\n', '2026-01-05'),
(11, 1, 11, 25, 5, 'BÚT VIẾT OK\r\n', '2026-01-05'),
(12, 1, 1, 1, 4, 'OK', '2026-01-05'),
(13, 1, 1, 1, 4, 'TỐT', '2026-01-05'),
(14, 1, 1, 1, 5, 'TỐT', '2026-01-05'),
(15, 1, 1, 1, 3, 'TỐT', '2026-01-05'),
(16, 1, 1, 1, 2, 'TỐT', '2026-01-05'),
(17, 1, 1, 1, 5, 'TỐT', '2026-01-05'),
(18, 1, 1, 1, 3, 'TỐT', '2026-01-05'),
(19, 1, 1, 1, 3, 'TỐT', '2026-01-05');

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

DROP TABLE IF EXISTS `donhang`;
CREATE TABLE IF NOT EXISTS `donhang` (
  `MaDH` int NOT NULL AUTO_INCREMENT,
  `MaKH` int DEFAULT NULL,
  `HoTenNguoiNhan` varchar(100) DEFAULT NULL,
  `SDTNguoiNhan` varchar(20) DEFAULT NULL,
  `NgayDat` date DEFAULT NULL,
  `TrangThai` varchar(50) DEFAULT NULL,
  `TongTien` decimal(10,2) DEFAULT NULL,
  `DiaChiGiaoHang` text,
  `GhiChu` text,
  PRIMARY KEY (`MaDH`),
  KEY `MaKH` (`MaKH`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`MaDH`, `MaKH`, `HoTenNguoiNhan`, `SDTNguoiNhan`, `NgayDat`, `TrangThai`, `TongTien`, `DiaChiGiaoHang`, `GhiChu`) VALUES
(1, 1, 'Nguyễn Văn A', '0912345678', '2025-12-20', 'Hoàn thành', 30000.00, '123 Nguyễn Huệ, Q1', NULL),
(7, 1, 'Nguyễn Văn A', '0912345678', '2026-01-02', 'Đang giao', 12000.00, 'Quận 1, TP.HCM', NULL),
(8, 1, 'Nguyễn Văn A', '0912345678', '2026-01-02', 'Chờ xử lý', 7000.00, 'Quận 1, TP.HCM', 'nhanh\r\n'),
(9, 1, 'Nguyễn Văn A', '0912345678', '2026-01-02', 'Hoàn thành', 6000.00, 'Quận 1, TP.HCM', ''),
(10, 1, 'Nguyễn Văn A', '0912345678', '2026-01-02', 'Đang giao', 45000.00, 'Quận 1, TP.HCM', ''),
(11, 1, 'Nguyễn Văn A', '0912345678', '2026-01-02', 'Hoàn thành', 6000.00, 'Quận 1, TP.HCM', ''),
(14, 1, 'Nguyễn Văn A', '0912345678', '2026-01-02', 'Đang giao', 6000.00, 'Quận 1, TP.HCM', ''),
(15, 1, 'Nguyễn Văn A', '0912345678', '2025-09-15', 'Hoàn thành', 450000.00, 'Quận 1, TP.HCM', 'Giao giờ hành chính'),
(16, 2, 'Lê Thị B', '0987654321', '2025-10-10', 'Hoàn thành', 820000.00, 'Quận 7, TP.HCM', ''),
(17, 4, 'Trần Văn C', '0905123456', '2025-11-20', 'Hoàn thành', 150000.00, 'Quận Bình Thạnh, TP.HCM', 'Đơn hàng tháng 11'),
(18, 5, 'Phan Thị D', '0938777888', '2025-12-05', 'Hoàn thành', 100000.00, 'Quận Thủ Đức, TP.HCM', 'Đơn hàng tháng 12'),
(19, 1, 'Nguyễn Văn A', '0912345678', '2025-12-25', 'Đang giao', 250000.00, 'Quận 1, TP.HCM', 'Quà tặng Giáng sinh'),
(20, 1, 'Nguyễn Văn A', '0912345678', '2025-12-29', 'Hoàn thành', 15000.00, 'Quận 1, TP.HCM', 'Giao hàng nhanh'),
(21, 4, 'Trần Văn C', '0905123456', '2025-12-30', 'Đã hủy', 45000.00, 'Quận Bình Thạnh, TP.HCM', 'Khách đổi ý'),
(22, 2, 'Lê Thị B', '0987654321', '2026-01-01', 'Đang giao', 12000.00, 'Quận 7, TP.HCM', ''),
(23, 5, 'Phan Thị D', '0938777888', '2026-01-03', 'Chờ xử lý', 25000.00, 'Quận Thủ Đức, TP.HCM', 'Giao sau 5h chiều'),
(24, 6, 'Hoàng Văn E', '0919222333', '2026-01-04', 'Chờ xử lý', 5000.00, 'Quận Gò Vấp, TP.HCM', ''),
(25, 1, 'Nguyễn Văn A', '0912345678', '2025-12-29', 'Hoàn thành', 45000.00, 'Quận 1, TP.HCM', 'Đã giao xong'),
(26, 2, 'Lê Thị B', '0987654321', '2025-12-30', 'Hoàn thành', 12000.00, 'Quận 7, TP.HCM', 'Khách đã nhận'),
(27, 4, 'Trần Văn C', '0905123456', '2025-12-01', 'Hoàn thành', 25000.00, 'Quận Bình Thạnh, TP.HCM', ''),
(28, 5, 'Phan Thị D', '0938777888', '2026-01-02', 'Hoàn thành', 820000.00, 'Quận Thủ Đức, TP.HCM', 'Giao hỏa tốc'),
(29, 6, 'Hoàng Văn E', '0919222333', '2026-01-03', 'Đã hủy', 5000.00, 'Quận Gò Vấp, TP.HCM', 'Hoàn tất'),
(30, 1, 'Nguyễn Văn A', '0912345678', '2025-12-31', 'Hoàn thành', 450000.00, 'Quận 1, TP.HCM', 'Đã giao cuối năm'),
(31, 2, 'Lê Thị B', '0987654321', '2026-01-01', 'Hoàn thành', 820000.00, 'Quận 7, TP.HCM', 'Đơn hàng đầu năm mới'),
(32, 4, 'Trần Văn C', '0905123456', '2026-01-02', 'Hoàn thành', 520000.00, 'Quận Bình Thạnh, TP.HCM', 'Khách đã nhận hàng'),
(33, 5, 'Phan Thị D', '0938777888', '2026-01-03', 'Hoàn thành', 1200000.00, 'Quận Thủ Đức, TP.HCM', 'Giao hỏa tốc'),
(34, 6, 'Hoàng Văn E', '0919222333', '2026-01-04', 'Hoàn thành', 650000.00, 'Quận Gò Vấp, TP.HCM', 'Hoàn tất thanh toán'),
(35, 1, 'Hoàng Anh', '0912345678', '2026-01-04', 'Hoàn thành', 81000.00, 'Quận 1, TP.HCM', ''),
(36, 4, 'Trần Văn C', '0905123456', '2026-01-04', 'Đã hủy', 450000.00, 'Quận Bình Thạnh, TP.HCM', ''),
(37, 4, 'Phi Hùng', '0905123456', '2026-01-04', 'Chờ xử lý', 45000.00, 'Quận Bình Thạnh, TP.HCM', ''),
(38, 4, 'Trần Văn C', '0905123456', '2026-01-05', 'Chờ xử lý', 945000.00, 'Quận Bình Thạnh, TP.HCM', ' ');

-- --------------------------------------------------------

--
-- Table structure for table `giohang`
--

DROP TABLE IF EXISTS `giohang`;
CREATE TABLE IF NOT EXISTS `giohang` (
  `MaGH` int NOT NULL AUTO_INCREMENT,
  `MaKH` int DEFAULT NULL,
  `NgayCapNhat` date DEFAULT NULL,
  `TongTien` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`MaGH`),
  KEY `MaKH` (`MaKH`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `giohang`
--

INSERT INTO `giohang` (`MaGH`, `MaKH`, `NgayCapNhat`, `TongTien`) VALUES
(2, NULL, '2025-12-22', 0.00),
(3, NULL, '2025-12-22', 0.00),
(4, NULL, '2025-12-22', 0.00),
(18, 4, '2026-01-05', 12000.00);

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

DROP TABLE IF EXISTS `khachhang`;
CREATE TABLE IF NOT EXISTS `khachhang` (
  `MaKH` int NOT NULL AUTO_INCREMENT,
  `HoTen` varchar(100) NOT NULL,
  `SDT` varchar(20) DEFAULT NULL,
  `DiaChi` text,
  `Email` varchar(100) DEFAULT NULL,
  `MaTK` int DEFAULT NULL,
  PRIMARY KEY (`MaKH`),
  KEY `MaTK` (`MaTK`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`MaKH`, `HoTen`, `SDT`, `DiaChi`, `Email`, `MaTK`) VALUES
(1, 'Nguyễn Văn A', '0912345678', 'Quận 1, TP.HCM', 'nguyenvana@gmail.com', 4),
(2, 'Lê Thị B', '0987654321', 'Quận 7, TP.HCM', 'lethib@gmail.com', 5),
(3, 'a', '0989990001', '123', 'a@a.com', 6),
(4, 'Trần Văn C', '0905123456', 'Quận Bình Thạnh, TP.HCM', 'tranvanc@gmail.com', 8),
(5, 'Phan Thị D', '0938777888', 'Quận Thủ Đức, TP.HCM', 'phanthid@gmail.com', 9),
(6, 'Hoàng Văn E', '0919222333', 'Quận Gò Vấp, TP.HCM', 'hoangvane@gmail.com', 10);

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

DROP TABLE IF EXISTS `khuyenmai`;
CREATE TABLE IF NOT EXISTS `khuyenmai` (
  `MaKM` int NOT NULL AUTO_INCREMENT,
  `TenKM` varchar(100) DEFAULT NULL,
  `NgayBatDau` date DEFAULT NULL,
  `NgayKetThuc` date DEFAULT NULL,
  `PhanTramGiam` int DEFAULT NULL,
  `DieuKienApDung` text,
  PRIMARY KEY (`MaKM`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khuyenmai`
--

INSERT INTO `khuyenmai` (`MaKM`, `TenKM`, `NgayBatDau`, `NgayKetThuc`, `PhanTramGiam`, `DieuKienApDung`) VALUES
(1, 'Giảm giá cuối năm', '2025-12-01', '2025-12-31', 10, 'Đơn hàng trên 200k'),
(2, 'Khuyến mãi Tân Niên 2026', '2026-01-01', '2026-01-15', 15, 'Áp dụng cho đơn hàng trên 500k'),
(3, 'Mừng Lễ Tình Nhân', '2026-01-02', '2026-02-15', 20, 'Đơn hàng Bút ký trên 500k'),
(4, 'Flash Sale Cuối Tuần', '2026-01-10', '2026-01-11', 5, 'Không giới hạn giá trị đơn hàng');

-- --------------------------------------------------------

--
-- Table structure for table `loaisp`
--

DROP TABLE IF EXISTS `loaisp`;
CREATE TABLE IF NOT EXISTS `loaisp` (
  `MaLoai` int NOT NULL AUTO_INCREMENT,
  `TenLoai` varchar(100) NOT NULL,
  `MoTa` text,
  PRIMARY KEY (`MaLoai`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `loaisp`
--

INSERT INTO `loaisp` (`MaLoai`, `TenLoai`, `MoTa`) VALUES
(1, 'Bút - Viết', 'Các loại bút bi, bút chì, bút ký'),
(2, 'Vở - Giấy', 'Vở học sinh, giấy kiểm tra, sổ tay'),
(3, 'Dụng cụ đo', 'Thước, compa, eke'),
(4, 'Cặp - Balo', 'Balo học sinh, túi xách'),
(5, 'Máy tính bỏ túi', 'siêu sịn\r\n'),
(6, 'Đèn bàn học', 'đèn siu sáng\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `nhacungcap`
--

DROP TABLE IF EXISTS `nhacungcap`;
CREATE TABLE IF NOT EXISTS `nhacungcap` (
  `MaNCC` int NOT NULL AUTO_INCREMENT,
  `TenNCC` varchar(100) NOT NULL,
  `DiaChi` text,
  `SDT` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`MaNCC`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nhacungcap`
--

INSERT INTO `nhacungcap` (`MaNCC`, `TenNCC`, `DiaChi`, `SDT`, `Email`) VALUES
(1, 'NCC Văn Phòng Phẩm Sài Gòn', 'Quận 5, TP.HCM', '028123456', 'contact@vppsg.com'),
(2, 'Tổng kho Deli miền Nam', 'Tân Bình, TP.HCM', '028666777', 'info@delivn.com'),
(3, 'Công ty Cổ phần Bến Nghé', 'KCN Tân Tạo, Bình Tân, TP.HCM', '028375058', 'info@bennghe.com.vn'),
(4, 'Công ty Văn phòng phẩm Hồng Hà', '25 Lý Thường Kiệt, Hoàn Kiếm, Hà Nội', '024385621', 'sales@hongha.com.vn'),
(5, 'Tổng kho Phân phối Casio Bitex', 'Quận 5, TP.HCM', '028396999', 'support@bitex.com.vn'),
(6, 'Công ty TNHH Văn phòng phẩm Campus', 'Quận 7, TP.HCM', '028541234', 'contact@campus-vn.com'),
(7, 'Nhà phân phối Bút ký Parker Việt Nam', 'Quận 1, TP.HCM', '028629123', 'admin@parkervn.com');

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

DROP TABLE IF EXISTS `nhanvien`;
CREATE TABLE IF NOT EXISTS `nhanvien` (
  `MaNV` int NOT NULL AUTO_INCREMENT,
  `HoTen` varchar(100) NOT NULL,
  `GioiTinh` varchar(10) DEFAULT NULL,
  `NgaySinh` date DEFAULT NULL,
  `SDT` varchar(20) DEFAULT NULL,
  `DiaChi` text,
  `MaTK` int DEFAULT NULL,
  PRIMARY KEY (`MaNV`),
  KEY `MaTK` (`MaTK`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`MaNV`, `HoTen`, `GioiTinh`, `NgaySinh`, `SDT`, `DiaChi`, `MaTK`) VALUES
(1, 'Trương Ngọc Đỉnh', 'Nam', '2004-01-01', '0901112223', 'TP.HCM', 1),
(2, 'Nguyễn Thị Nhân Viên', 'Nữ', '1998-05-20', '0908889990', 'Long An', 2),
(3, 'Trần Văn Kho', 'Nam', '1995-10-10', '0907776665', 'Bình Dương', 3),
(4, 'Hoài Thu', 'Nữ', '1999-12-20', '0982848619', 'Quận 6, TP.HCM', 7),
(5, 'Nguyễn Văn Kế', 'Nam', '1995-03-15', '0901234455', 'Quận 3, TP.HCM', 11),
(6, 'Phạm Thị Giao', 'Nữ', '1997-08-20', '0905667788', 'Quận Tân Bình, TP.HCM', 12);

-- --------------------------------------------------------

--
-- Table structure for table `nhaphang`
--

DROP TABLE IF EXISTS `nhaphang`;
CREATE TABLE IF NOT EXISTS `nhaphang` (
  `MaNH` int NOT NULL AUTO_INCREMENT,
  `MaNV` int DEFAULT NULL,
  `MaNCC` int DEFAULT NULL,
  `NgayNhap` date DEFAULT NULL,
  `TongTien` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`MaNH`),
  KEY `MaNV` (`MaNV`),
  KEY `MaNCC` (`MaNCC`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nhaphang`
--

INSERT INTO `nhaphang` (`MaNH`, `MaNV`, `MaNCC`, `NgayNhap`, `TongTien`) VALUES
(1, 3, 1, '2025-12-15', 5000000.00),
(2, 1, 1, '2026-01-04', 0.00),
(3, 1, 1, '2026-01-04', 124.00),
(4, 1, 1, '2026-01-04', 112705.00);

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

DROP TABLE IF EXISTS `sanpham`;
CREATE TABLE IF NOT EXISTS `sanpham` (
  `MaSP` int NOT NULL AUTO_INCREMENT,
  `TenSP` varchar(100) NOT NULL,
  `MoTa` text,
  `Gia` decimal(15,2) DEFAULT NULL,
  `SoLuongTon` int UNSIGNED DEFAULT '0',
  `HinhAnh` varchar(255) DEFAULT NULL,
  `MaLoai` int DEFAULT NULL,
  `MaNCC` int DEFAULT NULL,
  `MaTH` int DEFAULT NULL,
  PRIMARY KEY (`MaSP`),
  KEY `MaLoai` (`MaLoai`),
  KEY `MaNCC` (`MaNCC`),
  KEY `MaTH` (`MaTH`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`MaSP`, `TenSP`, `MoTa`, `Gia`, `SoLuongTon`, `HinhAnh`, `MaLoai`, `MaNCC`, `MaTH`) VALUES
(1, 'Bút bi TL-027', 'Bút bi xanh Thiên Long', 5000.00, 1002, 'but_bi_xanh_thien_long.jpg', 1, 1, 1),
(2, 'Vở 96 trang Thiên Long', 'Vở kẻ ngang chống lóa', 12000.00, 500, 'vo_96_trang_thien_long.jpg', 2, 1, 1),
(3, 'Bộ thước Deli 4 món', 'Thước nhựa cao cấp', 25000.00, 217, 'bo_thuoc_4_mon.jpg', 3, 2, 2),
(4, 'Balo học sinh chống gù', 'Balo thiết kế Nhật Bản', 450000.00, 50, 'balo_chong_gu.jpg', 4, 2, 3),
(5, 'Bút chì 2B Thiên Long G-08', 'Thân gỗ cao cấp, chì mềm dễ viết', 5000.00, 2000, 'but_chi_2b.jpg', 1, 1, 1),
(6, 'Bút dạ quang Deli', 'Mực tươi sáng, không làm lem chữ', 15000.00, 300, 'but_da_quang.jpg', 1, 2, 2),
(7, 'Compa Deli E2003', 'Chất liệu kim loại chắc chắn, có kèm chì ngòi', 35000.00, 150, 'compa_deli.jpg', 3, 2, 2),
(8, 'Giấy kiểm tra A4 Thiên Long', 'Giấy định lượng 70gsm, trắng tự nhiên', 10000.00, 1000, 'giay_kiem_tra.jpg', 2, 1, 1),
(9, 'Thước nhôm 30cm Deli', 'Vạch chia số rõ ràng, không bong tróc', 18000.00, 400, 'thuoc_nhom.jpg', 3, 2, 2),
(10, 'Sổ tay lò xo A5 Deli', 'Bìa cứng cáp, giấy chống lóa', 22000.00, 250, 'so_tay_lo_xo.jpg', 2, 2, 2),
(11, 'Bút Gel Pentel EnerGel 0.5', 'Mực gel siêu mịn, khô nhanh trong 1 giây', 45000.00, 100, 'but_gel_pentel.jpg', 1, 2, 3),
(12, 'Bút xóa Thiên Long CP-02', 'Độ che phủ cao, thân bút dễ bóp', 12000.00, 600, 'but_xoa.jpg', 1, 1, 1),
(13, 'Túi đựng bút Deli Canvas', 'Chất liệu vải bền đẹp, ngăn chứa rộng', 28000.00, 121, 'tui_but.jpg', 4, 2, 2),
(14, 'Giấy note 5 màu Deli', 'Độ dính tốt, màu sắc bắt mắt', 8000.00, 988, 'giay_note.jpg', 2, 2, 2),
(15, 'Balo chống gù Tiger Family', 'Dòng siêu nhẹ cho học sinh tiểu học', 650000.00, 31, 'balo_tiger.jpg', 4, 2, 11),
(16, 'Máy tính Casio FX-880BTG', 'Dòng máy tính khoa học cao cấp nhất hiện nay', 820000.00, 40, 'casio_880btg.jpg', 5, 1, 7),
(17, 'Đèn bàn học chống cận Deli', 'Công nghệ LED bảo vệ mắt, 5 mức sáng', 320000.00, 20, 'den_deli_chongcan.jpg', 6, 2, 2),
(19, 'Bút ký cao cấp Pentel Libretto', 'Thân kim loại bạc, mực gel mịn', 550000.00, 15, 'pentel_libretto.jpg', 1, 2, 3),
(21, 'Máy tính Vinacal 680EX Plus', 'Tốc độ xử lý cực nhanh, nhiều tính năng mới', 520000.00, 35, 'vinacal_680ex.jpg', 5, 1, 10),
(22, 'Máy tính Casio FX-580VN X', 'Máy tính khoa học thông minh cho học sinh cấp 3', 350000.00, 50, 'casio_580vnx.jpg', 5, 1, 7),
(23, 'Đèn bàn học LED cảm ứng', 'Điều chỉnh 3 chế độ sáng, chống mỏi mắt', 650000.00, 15, 'den_led_hoc.jpg', 6, 2, 2),
(24, 'Bộ sưu tập Bút ký cao cấp Parker', 'Thân kim loại sang trọng, hộp quà tặng cao cấp', 1200000.00, 10, 'pentel_sterling.jpg', 1, 2, 8),
(25, 'Vở Campus Adventure 80 trang', 'Vở kẻ ngang chất lượng cao, chống lem mực', 15000.00, 200, 'vo_campus_adventure.jpg', 2, 1, 4),
(26, 'Sổ tay Campus Gift', 'Thiết kế bìa đẹp, giấy mịn', 35000.00, 100, 'so_campus_gift.jpg', 2, 1, 4),
(27, 'Bút Parker Frontier Matte Black', 'Bút dạ bi cao cấp, vỏ thép sơn tĩnh điện', 850000.00, 10, 'parker_frontier.jpg', 1, 2, 8),
(28, 'Bút Parker Jotter XL Richmond', 'Dòng bút bi biểu tượng, thiết kế sang trọng', 620000.00, 25, 'parker_jotter_xl.jpg', 1, 2, 8),
(29, 'Gôm tẩy trắng Thiên Long E-09', 'Gôm tẩy siêu sạch, không để lại bụi vụn khi sử dụng', 3000.00, 500, 'gom_tay_tl.jpg', 1, 1, 1),
(30, 'Hồ dán khô Deli 8g', 'Độ dính tốt, dạng thỏi tiện lợi, không gây nhăn giấy', 7000.00, 299, 'ho_dan_deli.jpg', 2, 2, 2),
(31, 'Ngòi chì 2B Pentel Hi-Polymer', 'Ngòi chì siêu bền, viết êm tay, độ đậm chuẩn 2B', 12000.00, 399, 'ngoi_chi_pentel.jpg', 1, 2, 3),
(32, 'Chuốt bút chì Deli E0574', 'Lưỡi dao thép sắc bén, có ngăn chứa phoi chì tiện lợi', 6000.00, 447, 'chuot_chi_deli.jpg', 1, 2, 2),
(44, 'Kéo văn phòng Deli 170mm', 'Lưỡi thép không gỉ sắc bén, tay cầm bọc nhựa êm ái', 28000.00, 0, 'keo_deli_170.jpg', 3, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sp_km`
--

DROP TABLE IF EXISTS `sp_km`;
CREATE TABLE IF NOT EXISTS `sp_km` (
  `MaSP` int NOT NULL,
  `MaKM` int NOT NULL,
  PRIMARY KEY (`MaSP`,`MaKM`),
  KEY `MaKM` (`MaKM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sp_km`
--

INSERT INTO `sp_km` (`MaSP`, `MaKM`) VALUES
(4, 1),
(16, 2),
(24, 3),
(1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

DROP TABLE IF EXISTS `taikhoan`;
CREATE TABLE IF NOT EXISTS `taikhoan` (
  `MaTK` int NOT NULL AUTO_INCREMENT,
  `TenDangNhap` varchar(50) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `VaiTro` varchar(30) DEFAULT NULL,
  `TrangThai` tinyint(1) DEFAULT '1' COMMENT '1: Hoạt động, 0: Khóa',
  PRIMARY KEY (`MaTK`),
  UNIQUE KEY `TenDangNhap` (`TenDangNhap`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`MaTK`, `TenDangNhap`, `MatKhau`, `Email`, `VaiTro`, `TrangThai`) VALUES
(1, 'admin', '123', 'admin@web.com', 'Quản trị viên', 1),
(2, 'nv_banhang', 'nv123', 'nv1@web.com', 'Nhân viên', 1),
(3, 'nv_kho', 'nv123', 'nv2@web.com', 'Nhân viên', 1),
(4, 'khachhang_a', 'kh123', 'nguyenvana@gmail.com', 'Khách hàng', 1),
(5, 'khachhang_b', 'kh123', 'lethib@gmail.com', 'Khách hàng', 1),
(6, 'a', '123456', 'a@a.com', 'Khách hàng', 1),
(7, 'admin2', '123456', 'admin2@email.com', 'Quản trị viên', 1),
(8, 'khachhang_c', 'kh123', 'tranvanc@gmail.com', 'Khách hàng', 1),
(9, 'khachhang_d', 'kh123', 'phanthid@gmail.com', 'Khách hàng', 1),
(10, 'khachhang_e', 'kh123', 'hoangvane@gmail.com', 'Khách hàng', 1),
(11, 'nv_keo', '123', 'keo@web.com', 'Nhân viên', 1),
(12, 'nv_giaohang', '123', 'shipper@web.com', 'Nhân viên', 1);

-- --------------------------------------------------------

--
-- Table structure for table `thuonghieu`
--

DROP TABLE IF EXISTS `thuonghieu`;
CREATE TABLE IF NOT EXISTS `thuonghieu` (
  `MaTH` int NOT NULL AUTO_INCREMENT,
  `TenTH` varchar(100) NOT NULL,
  `QuocGia` varchar(100) DEFAULT NULL,
  `MoTa` text,
  PRIMARY KEY (`MaTH`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `thuonghieu`
--

INSERT INTO `thuonghieu` (`MaTH`, `TenTH`, `QuocGia`, `MoTa`) VALUES
(1, 'Thiên Long', 'Việt Nam', 'Thương hiệu hàng đầu VN'),
(2, 'Deli', 'Trung Quốc', 'Văn phòng phẩm quốc tế'),
(3, 'Pentel', 'Nhật Bản', 'Sản phẩm chất lượng cao'),
(4, 'Campus', 'Nhật Bản', 'Vở kẻ ngang và dụng cụ học sinh cao cấp'),
(7, 'Casio', 'Nhật Bản', 'Máy tính bỏ túi và thiết bị điện tử'),
(8, 'Parker', 'Mỹ', 'Bút ký cao cấp và văn phòng phẩm hạng sang'),
(10, 'Vinacal', 'Việt Nam', 'Máy tính học sinh và văn phòng phẩm'),
(11, 'Tiger Family', 'Đức', 'Cặp chống gù và đồ dùng học sinh');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chitietdh`
--
ALTER TABLE `chitietdh`
  ADD CONSTRAINT `chitietdh_ibfk_1` FOREIGN KEY (`MaDH`) REFERENCES `donhang` (`MaDH`),
  ADD CONSTRAINT `chitietdh_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `chitietgh`
--
ALTER TABLE `chitietgh`
  ADD CONSTRAINT `chitietgh_ibfk_1` FOREIGN KEY (`MaGH`) REFERENCES `giohang` (`MaGH`),
  ADD CONSTRAINT `chitietgh_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `chitietnh`
--
ALTER TABLE `chitietnh`
  ADD CONSTRAINT `chitietnh_ibfk_1` FOREIGN KEY (`MaNH`) REFERENCES `nhaphang` (`MaNH`),
  ADD CONSTRAINT `chitietnh_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `danhgia_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `danhgia_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`),
  ADD CONSTRAINT `fk_danhgia_donhang` FOREIGN KEY (`MaDH`) REFERENCES `donhang` (`MaDH`) ON DELETE CASCADE;

--
-- Constraints for table `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`);

--
-- Constraints for table `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`);

--
-- Constraints for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD CONSTRAINT `khachhang_ibfk_1` FOREIGN KEY (`MaTK`) REFERENCES `taikhoan` (`MaTK`);

--
-- Constraints for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`MaTK`) REFERENCES `taikhoan` (`MaTK`);

--
-- Constraints for table `nhaphang`
--
ALTER TABLE `nhaphang`
  ADD CONSTRAINT `nhaphang_ibfk_1` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`),
  ADD CONSTRAINT `nhaphang_ibfk_2` FOREIGN KEY (`MaNCC`) REFERENCES `nhacungcap` (`MaNCC`);

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`MaLoai`) REFERENCES `loaisp` (`MaLoai`),
  ADD CONSTRAINT `sanpham_ibfk_2` FOREIGN KEY (`MaNCC`) REFERENCES `nhacungcap` (`MaNCC`),
  ADD CONSTRAINT `sanpham_ibfk_3` FOREIGN KEY (`MaTH`) REFERENCES `thuonghieu` (`MaTH`);

--
-- Constraints for table `sp_km`
--
ALTER TABLE `sp_km`
  ADD CONSTRAINT `sp_km_ibfk_1` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`),
  ADD CONSTRAINT `sp_km_ibfk_2` FOREIGN KEY (`MaKM`) REFERENCES `khuyenmai` (`MaKM`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
