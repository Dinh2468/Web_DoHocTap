<?php
// admin/controller/AdminUserController.php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';

$db = new Db();
$action = $_GET['action'] ?? '';



if ($action == 'toggle_status') {
    $maTK_to_lock = $_GET['id'] ?? '';
    $status = $_GET['status'] ?? '';

    // Lấy ID của người đang đăng nhập từ Session
    $current_admin_id = $_SESSION['user_id'] ?? '';

    if (!empty($maTK_to_lock)) {
        // Kiểm tra nếu ID muốn khóa trùng với ID đang đăng nhập
        if ($maTK_to_lock == $current_admin_id && $status == 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Bạn không thể khóa tài khoản của chính mình khi đang đăng nhập!'
            ]);
            exit();
        }

        try {
            $sql = "UPDATE taikhoan SET TrangThai = ? WHERE MaTK = ?";
            $db->query($sql, [$status, $maTK_to_lock]);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    exit();
}

// Thêm đoạn này vào file AdminUserController.php
if ($action == 'get_details') {
    $id = $_GET['id'] ?? '';
    // 1. Lấy thông tin khách hàng
    $customer = $db->query("SELECT kh.*, tk.TenDangNhap, tk.TrangThai 
                            FROM khachhang kh 
                            JOIN taikhoan tk ON kh.MaTK = tk.MaTK 
                            WHERE kh.MaKH = ?", [$id])->fetch();

    // 2. Lấy lịch sử đơn hàng
    $orders = $db->query("SELECT MaDH, NgayDat, TongTien, TrangThai 
                          FROM donhang WHERE MaKH = ? ORDER BY NgayDat DESC", [$id])->fetchAll();

    echo json_encode([
        'customer' => $customer,
        'orders' => $orders
    ]);
    exit();
}

if ($action == 'edit_employee') {
    $maNV = $_POST['maNV'];
    $maTK = $_POST['maTK'];
    $hoTen = trim($_POST['hoTen']);
    $gioiTinh = $_POST['gioiTinh'];
    $sdt = $_POST['sdt'];
    $ngaySinh = $_POST['ngaySinh'];
    $diaChi = $_POST['diaChi'];
    $vaiTro = $_POST['vaiTro'];

    try {
        // 1. Cập nhật bảng nhanvien
        $sqlNV = "UPDATE nhanvien SET HoTen = ?, GioiTinh = ?, NgaySinh = ?, SDT = ?, DiaChi = ? WHERE MaNV = ?";
        $db->query($sqlNV, [$hoTen, $gioiTinh, $ngaySinh, $sdt, $diaChi, $maNV]);

        // 2. Cập nhật bảng taikhoan (Vai trò)
        $sqlTK = "UPDATE taikhoan SET VaiTro = ? WHERE MaTK = ?";
        $db->query($sqlTK, [$vaiTro, $maTK]);

        header("Location: ../Views/Nhanvien/index.php?msg=updated");
        exit();
    } catch (Exception $e) {
        echo "Lỗi: " . $e->getMessage();
    }
}

if ($action == 'add_employee') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    $vaiTro = $_POST['vaiTro'];

    $hoTen = trim($_POST['hoTen']);
    $gioiTinh = $_POST['gioiTinh'];
    $sdt = $_POST['sdt'];
    $ngaySinh = $_POST['ngaySinh'];
    $diaChi = $_POST['diaChi'];

    try {
        // 1. Kiểm tra tên đăng nhập đã tồn tại chưa
        $check = $db->query("SELECT * FROM taikhoan WHERE TenDangNhap = ?", [$username])->fetch();
        if ($check) {
            die("Lỗi: Tên đăng nhập đã tồn tại!");
        }

        // 2. Thêm vào bảng taikhoan trước
        $sqlTK = "INSERT INTO taikhoan (TenDangNhap, MatKhau, Email, VaiTro, TrangThai) VALUES (?, ?, ?, ?, 1)";
        $db->query($sqlTK, [$username, $password, $email, $vaiTro]);

        // Lấy MaTK vừa tự động tăng
        $maTK_moi = $db->query("SELECT LAST_INSERT_ID() as id")->fetch()['id'];

        // 3. Thêm vào bảng nhanvien sử dụng MaTK vừa lấy
        $sqlNV = "INSERT INTO nhanvien (HoTen, GioiTinh, NgaySinh, SDT, DiaChi, MaTK) VALUES (?, ?, ?, ?, ?, ?)";
        $db->query($sqlNV, [$hoTen, $gioiTinh, $ngaySinh, $sdt, $diaChi, $maTK_moi]);

        header("Location: ../Views/Nhanvien/index.php?msg=add");
        exit();
    } catch (Exception $e) {
        echo "Lỗi hệ thống: " . $e->getMessage();
    }
}
