-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 01, 2026 at 08:26 AM
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
(1, 1, 6, 5000.00);

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
(1, 1, 1, 5000.00),
(1, 2, 2, 12000.00);

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
(1, 2, 80, 10000.00);

-- --------------------------------------------------------

--
-- Table structure for table `danhgia`
--

DROP TABLE IF EXISTS `danhgia`;
CREATE TABLE IF NOT EXISTS `danhgia` (
  `MaDG` int NOT NULL AUTO_INCREMENT,
  `MaKH` int DEFAULT NULL,
  `MaSP` int DEFAULT NULL,
  `SoSao` int DEFAULT NULL,
  `NoiDung` text,
  `NgayDG` date DEFAULT NULL,
  PRIMARY KEY (`MaDG`),
  KEY `MaKH` (`MaKH`),
  KEY `MaSP` (`MaSP`)
) ;

--
-- Dumping data for table `danhgia`
--

INSERT INTO `danhgia` (`MaDG`, `MaKH`, `MaSP`, `SoSao`, `NoiDung`, `NgayDG`) VALUES
(1, 1, 4, 5, 'Balo rất đẹp, con mình rất thích', '2025-12-21');

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
  PRIMARY KEY (`MaDH`),
  KEY `MaKH` (`MaKH`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`MaDH`, `MaKH`, `HoTenNguoiNhan`, `SDTNguoiNhan`, `NgayDat`, `TrangThai`, `TongTien`, `DiaChiGiaoHang`) VALUES
(1, 1, 'Nguyễn Văn A', '0912345678', '2025-12-20', 'Đã giao', 30000.00, '123 Nguyễn Huệ, Q1');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `giohang`
--

INSERT INTO `giohang` (`MaGH`, `MaKH`, `NgayCapNhat`, `TongTien`) VALUES
(1, 1, '2025-12-30', 29000.00),
(2, NULL, '2025-12-22', 0.00),
(3, NULL, '2025-12-22', 0.00),
(4, NULL, '2025-12-22', 0.00);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`MaKH`, `HoTen`, `SDT`, `DiaChi`, `Email`, `MaTK`) VALUES
(1, 'Nguyễn Văn A', '0912345678', 'Quận 1, TP.HCM', 'nguyenvana@gmail.com', 4),
(2, 'Lê Thị B', '0987654321', 'Quận 7, TP.HCM', 'lethib@gmail.com', 5);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khuyenmai`
--

INSERT INTO `khuyenmai` (`MaKM`, `TenKM`, `NgayBatDau`, `NgayKetThuc`, `PhanTramGiam`, `DieuKienApDung`) VALUES
(1, 'Giảm giá cuối năm', '2025-12-01', '2025-12-31', 10, 'Đơn hàng trên 200k');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `loaisp`
--

INSERT INTO `loaisp` (`MaLoai`, `TenLoai`, `MoTa`) VALUES
(1, 'Bút - Viết', 'Các loại bút bi, bút chì, bút ký'),
(2, 'Vở - Giấy', 'Vở học sinh, giấy kiểm tra, sổ tay'),
(3, 'Dụng cụ đo', 'Thước, compa, eke'),
(4, 'Cặp - Balo', 'Balo học sinh, túi xách');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nhacungcap`
--

INSERT INTO `nhacungcap` (`MaNCC`, `TenNCC`, `DiaChi`, `SDT`, `Email`) VALUES
(1, 'NCC Văn Phòng Phẩm Sài Gòn', 'Quận 5, TP.HCM', '028123456', 'contact@vppsg.com'),
(2, 'Tổng kho Deli miền Nam', 'Tân Bình, TP.HCM', '028666777', 'info@delivn.com');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`MaNV`, `HoTen`, `GioiTinh`, `NgaySinh`, `SDT`, `DiaChi`, `MaTK`) VALUES
(1, 'Trương Ngọc Đỉnh', 'Nam', '2004-01-01', '0901112223', 'TP.HCM', 1),
(2, 'Nguyễn Thị Nhân Viên', 'Nữ', '1998-05-20', '0908889990', 'Long An', 2),
(3, 'Trần Văn Kho', 'Nam', '1995-10-10', '0907776665', 'Bình Dương', 3);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nhaphang`
--

INSERT INTO `nhaphang` (`MaNH`, `MaNV`, `MaNCC`, `NgayNhap`, `TongTien`) VALUES
(1, 3, 1, '2025-12-15', 5000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

DROP TABLE IF EXISTS `sanpham`;
CREATE TABLE IF NOT EXISTS `sanpham` (
  `MaSP` int NOT NULL AUTO_INCREMENT,
  `TenSP` varchar(100) NOT NULL,
  `MoTa` text,
  `Gia` decimal(10,2) DEFAULT NULL,
  `SoLuongTon` int DEFAULT '0',
  `HinhAnh` varchar(255) DEFAULT NULL,
  `MaLoai` int DEFAULT NULL,
  `MaNCC` int DEFAULT NULL,
  `MaTH` int DEFAULT NULL,
  PRIMARY KEY (`MaSP`),
  KEY `MaLoai` (`MaLoai`),
  KEY `MaNCC` (`MaNCC`),
  KEY `MaTH` (`MaTH`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`MaSP`, `TenSP`, `MoTa`, `Gia`, `SoLuongTon`, `HinhAnh`, `MaLoai`, `MaNCC`, `MaTH`) VALUES
(1, 'Bút bi TL-027', 'Bút bi xanh Thiên Long', 5000.00, 1000, 'but_bi_xanh_thien_long.jpg', 1, 1, 1),
(2, 'Vở 96 trang Thiên Long', 'Vở kẻ ngang chống lóa', 12000.00, 500, 'vo_96_trang_thien_long.jpg', 2, 1, 1),
(3, 'Bộ thước Deli 4 món', 'Thước nhựa cao cấp', 25000.00, 200, 'bo_thuoc_4_mon.jpg', 3, 2, 2),
(4, 'Balo học sinh chống gù', 'Balo thiết kế Nhật Bản', 450000.00, 50, 'balo_chong_gu.jpg', 4, 2, 3);

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
(4, 1);

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
  PRIMARY KEY (`MaTK`),
  UNIQUE KEY `TenDangNhap` (`TenDangNhap`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`MaTK`, `TenDangNhap`, `MatKhau`, `Email`, `VaiTro`) VALUES
(1, 'admin01', 'password123', 'admin@web.com', 'Quản trị viên'),
(2, 'nv_banhang', 'nv123', 'nv1@web.com', 'Nhân viên'),
(3, 'nv_kho', 'nv123', 'nv2@web.com', 'Nhân viên'),
(4, 'khachhang_a', 'kh123', 'nguyenvana@gmail.com', 'Khách hàng'),
(5, 'khachhang_b', 'kh123', 'lethib@gmail.com', 'Khách hàng');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `thuonghieu`
--

INSERT INTO `thuonghieu` (`MaTH`, `TenTH`, `QuocGia`, `MoTa`) VALUES
(1, 'Thiên Long', 'Việt Nam', 'Thương hiệu hàng đầu VN'),
(2, 'Deli', 'Trung Quốc', 'Văn phòng phẩm quốc tế'),
(3, 'Pentel', 'Nhật Bản', 'Sản phẩm chất lượng cao');

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
  ADD CONSTRAINT `danhgia_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

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
