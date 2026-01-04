<?php
// admin/controller/AdminNhaphangController.php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';

$db = new Db();
$action = $_GET['action'] ?? '';

if ($action == 'create') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $maNCC = $_POST['maNCC'];
        $ngayNhap = $_POST['ngayNhap'];
        $maNV = $_SESSION['user_id'];

        $products = $_POST['products'];
        $quantities = $_POST['quantities'];
        $prices = $_POST['prices'];

        $tongTien = 0;
        foreach ($prices as $key => $p) {
            $tongTien += (float)$p * (int)$quantities[$key];
        }

        try {
            // 1. Lưu vào bảng nhaphang
            $db->query(
                "INSERT INTO nhaphang (MaNV, MaNCC, NgayNhap, TongTien) VALUES (?, ?, ?, ?)",
                [$maNV, $maNCC, $ngayNhap, $tongTien]
            );
            $maNH = $db->query("SELECT LAST_INSERT_ID() as id")->fetch()['id'];

            foreach ($products as $key => $maSP) {
                $qty = (int)$quantities[$key];
                $price = (float)$prices[$key];

                // 2. Lưu chi tiết nhập hàng
                $db->query(
                    "INSERT INTO chitietnh (MaNH, MaSP, SoLuong, DonGia) VALUES (?, ?, ?, ?)",
                    [$maNH, $maSP, $qty, $price]
                );

                // 3. TỰ ĐỘNG CẬP NHẬT TỒN KHO
                $db->query("UPDATE sanpham SET SoLuongTon = SoLuongTon + ? WHERE MaSP = ?", [$qty, $maSP]);
            }

            header("Location: ../Views/Nhaphang/index.php?msg=success");
            exit();
        } catch (Exception $e) {
            die("Lỗi nhập kho: " . $e->getMessage());
        }
    }
}
if ($action == 'get_detail') {
    $maNH = $_GET['id'] ?? '';
    if (!empty($maNH)) {
        // 1. Lấy thông tin chung (Thêm DiaChi và Email vào SELECT)
        $sqlInfo = "SELECT nh.*, ncc.TenNCC, ncc.SDT, ncc.Email, ncc.DiaChi, nv.HoTen as TenNV 
                    FROM nhaphang nh
                    JOIN nhacungcap ncc ON nh.MaNCC = ncc.MaNCC
                    JOIN nhanvien nv ON nh.MaNV = nv.MaNV
                    WHERE nh.MaNH = ?";
        $info = $db->query($sqlInfo, [$maNH])->fetch();

        // 2. Lấy chi tiết sản phẩm (Giữ nguyên)
        $details = $db->query("SELECT ct.*, sp.TenSP, sp.HinhAnh 
                               FROM chitietnh ct 
                               JOIN sanpham sp ON ct.MaSP = sp.MaSP 
                               WHERE ct.MaNH = ?", [$maNH])->fetchAll();
        if ($info && $details) {
            // Giao diện Header mới tích hợp Địa chỉ và Email
            echo '<div style="display: flex; justify-content: space-between; margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px; font-size: 13px; line-height: 1.6; border: 1px solid #eee;">
                    <div style="flex: 1;">
                        <p><strong>Nhà cung cấp:</strong> ' . htmlspecialchars($info['TenNCC']) . '</p>
                        <p><strong>Địa chỉ:</strong> ' . htmlspecialchars($info['DiaChi']) . '</p>
                        <p><strong>SĐT:</strong> ' . $info['SDT'] . ' | <strong>Email:</strong> ' . $info['Email'] . '</p>
                    </div>
                    <div style="text-align: right; flex: 0 0 200px;">
                        <p><strong>Ngày lập:</strong> ' . date('d/m/Y', strtotime($info['NgayNhap'])) . '</p>
                        <p><strong>Nhân viên:</strong> ' . $info['TenNV'] . '</p>
                    </div>
                  </div>';

            // Hiển thị bảng sản phẩm (giữ nguyên logic cũ của bạn)
            echo '<table class="table" style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa; text-align: left; border-bottom: 2px solid #eee;">
                            <th style="padding: 10px;">Ảnh</th>
                            <th style="padding: 10px;">Sản phẩm</th>
                            <th style="padding: 10px;">Số lượng</th>
                            <th style="padding: 10px;">Giá nhập</th>
                            <th style="padding: 10px;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>';
            $grandTotal = 0;
            foreach ($details as $row) {
                $subtotal = $row['SoLuong'] * $row['DonGia'];
                $grandTotal += $subtotal;
                $hinhAnh = !empty($row['HinhAnh']) ? $row['HinhAnh'] : 'default.jpg';
                echo "<tr>
                        <td><img src='../../assets/images/Sanpham/{$hinhAnh}' width='45' style='border-radius:4px;'></td>
                        <td>{$row['TenSP']}</td>
                        <td>{$row['SoLuong']}</td>
                        <td>" . number_format($row['DonGia']) . "đ</td>
                        <td style='font-weight:bold;'>" . number_format($subtotal) . "đ</td>
                      </tr>";
            }
            echo "</tbody>
                  <tfoot>
                    <tr>
                        <td colspan='4' style='text-align:right; font-weight:bold; padding-top:15px;'>TỔNG TIỀN PHIẾU NHẬP:</td>
                        <td style='font-weight:bold; color:red; font-size:1.2em; padding-top:15px;'>" . number_format($info['TongTien']) . "đ</td>
                    </tr>
                  </tfoot>
                </table>";
        }
    }
    exit();
}
