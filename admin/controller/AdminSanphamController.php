<?php
// admin/controller/AdminSanphamController.php
session_start();
require_once __DIR__ . '/../../classes/DB.class.php';

// Kiểm tra quyền truy cập nhanh
if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] !== 'Quản trị viên' && $_SESSION['user_role'] !== 'Nhân viên')) {
    exit("Truy cập bị từ chối");
}

$db = new Db();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tenSP = $_POST['tenSP'];
            $maLoai = $_POST['maLoai'];
            $gia = !empty($_POST['gia']) ? (float)$_POST['gia'] : 0;
            $soLuong = !empty($_POST['soLuong']) ? (int)$_POST['soLuong'] : 0;
            $moTa = $_POST['moTa'] ?? '';
            if ($soLuong < 0) {
                $soLuong = 0; // Đảm bảo không bao giờ là số âm khi vào DB
            }
            // Xử lý Upload Ảnh
            $hinhAnh = 'default.jpg'; // Giá trị mặc định nếu không up ảnh
            if (isset($_FILES['hinhAnh']) && $_FILES['hinhAnh']['error'] == 0) {
                $targetDir = "../../assets/images/Sanpham/";
                $fileName = time() . "_" . basename($_FILES["hinhAnh"]["name"]);
                if (move_uploaded_file($_FILES["hinhAnh"]["tmp_name"], $targetDir . $fileName)) {
                    $hinhAnh = $fileName;
                }
            }

            try {
                $sql = "INSERT INTO sanpham (TenSP, MoTa, Gia, SoLuongTon, HinhAnh, MaLoai) 
                    VALUES (?, ?, ?, ?, ?, ?)";
                $db->query($sql, [$tenSP, $moTa, $gia, $soLuong, $hinhAnh, $maLoai]);

                header("Location: ../Views/Sanpham/index.php?msg=success");
                exit();
            } catch (Exception $e) {
                echo "Lỗi khi thêm sản phẩm: " . $e->getMessage();
            }
        }
        break;
    case 'edit':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $maSP = $_POST['maSP'];
            $tenSP = trim($_POST['tenSP']);
            $maLoai = $_POST['maLoai'];
            $gia = !empty($_POST['gia']) ? (float)$_POST['gia'] : 0;
            $soLuong = !empty($_POST['soLuong']) ? (int)$_POST['soLuong'] : 0;
            $moTa = $_POST['moTa'];
            if ($soLuong < 0) {
                $soLuong = 0; // Đảm bảo không bao giờ là số âm khi vào DB
            }
            // Xử lý ảnh: Nếu có ảnh mới thì dùng ảnh mới, không thì giữ ảnh cũ
            $hinhAnh = $_POST['hinhAnhCu'];
            if (isset($_FILES['hinhAnh']) && $_FILES['hinhAnh']['error'] == 0) {
                $targetDir = "../../assets/images/Sanpham/";
                $fileName = time() . "_" . basename($_FILES["hinhAnh"]["name"]);
                if (move_uploaded_file($_FILES["hinhAnh"]["tmp_name"], $targetDir . $fileName)) {
                    $hinhAnh = $fileName;
                }
            }

            try {
                $sql = "UPDATE sanpham SET TenSP=?, MoTa=?, Gia=?, SoLuongTon=?, HinhAnh=?, MaLoai=? WHERE MaSP=?";
                $db->query($sql, [$tenSP, $moTa, $gia, $soLuong, $hinhAnh, $maLoai, $maSP]);
                header("Location: ../Views/Sanpham/index.php?msg=updated");
                exit();
            } catch (Exception $e) {
                echo "Lỗi khi cập nhật: " . $e->getMessage();
            }
        }
        break;
    case 'delete':
        $id = $_GET['id'] ?? '';
        if (!empty($id)) {
            try {
                // Xóa sản phẩm
                $db->query("DELETE FROM sanpham WHERE MaSP = ?", [$id]);
                header("Location: ../Views/Sanpham/index.php?msg=deleted");
            } catch (Exception $e) {
                echo "Lỗi khi xóa: Sản phẩm này có thể đang tồn tại trong đơn hàng!";
            }
        }
        break;
}
