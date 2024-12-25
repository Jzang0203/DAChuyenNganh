<?php
include '../connect.php';
session_start(); 

if (!isset($_SESSION['ma_quantrivien'])) {
    echo "<script>alert('Vui lòng đăng nhập lại!'); window.location.href = '../Login.php';</script>";
    exit();
}

$ma_quantrivien = $_SESSION['ma_quantrivien'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nhận thông tin từ biểu mẫu
    $ten_sanpham = $_POST['ten_sanpham'];
    $gia = $_POST['gia'];
    $ma_loaisanpham = $_POST['ma_loaisanpham'];
    $mau = $_POST['mau'];
    $size = $_POST['size'];
    $mo_ta = $_POST['mota'];
    $so_luong_ton = $_POST['soluongton'];

    $sql_loai = "SELECT ten_loaisanpham FROM loaisanpham WHERE ma_loaisanpham = ?";
    $stmt = $conn->prepare($sql_loai);
    $stmt->bind_param("i", $ma_loaisanpham);
    $stmt->execute();
    $result_loai = $stmt->get_result();
    $row_loai = $result_loai->fetch_assoc();
    $ten_loaisanpham = $row_loai['ten_loaisanpham'];

    // Xác định thư mục 
    $target_dir = "../Demo/img/" . $ten_loaisanpham . "/";

    if (!file_exists($target_dir)) {
    die("Thư mục loại sản phẩm không tồn tại. Vui lòng kiểm tra lại cấu trúc thư mục.");
    }

    $hinh_anh = basename($_FILES["hinh_anh"]["name"]);
    $target_file = $target_dir . $hinh_anh;

    // Di chuyển file tải lên vào thư mục loại sản phẩm
    if (!move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_file)) {
    die("Lỗi khi tải lên ảnh.");
    }

    $image_path = $ten_loaisanpham . "/" . $hinh_anh;

    // Thêm sản phẩm vào bảng `sanpham`
    $sql = "INSERT INTO sanpham (ten_sanpham, mota, gia, soluongton, hinh_anh, ma_loaisanpham, ma_quantrivien) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisis", $ten_sanpham, $mo_ta, $gia, $so_luong_ton, $image_path, $ma_loaisanpham, $ma_quantrivien);
    $stmt->execute();

    $ma_sanpham = $conn->insert_id;

    // Thêm màu sắc
    foreach ($mau as $m) {
        $sql_mau = "INSERT INTO mausanpham (ma_sanpham, ten_mau) VALUES (?, ?)";
        $stmt_mau = $conn->prepare($sql_mau);
        $stmt_mau->bind_param("is", $ma_sanpham, $m);
        $stmt_mau->execute();
    }

    // Thêm kích thước
    foreach ($size as $s) {
        $sql_size = "INSERT INTO size (ma_sanpham, ten_size) VALUES (?, ?)";
        $stmt_size = $conn->prepare($sql_size);
        $stmt_size->bind_param("is", $ma_sanpham, $s);
        $stmt_size->execute();
    }
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="/Demo/css/qtv.css">
</head>
<body>
    <div class="container">  
        <h1>Thêm sản phẩm mới</h1>
        <form method="POST" enctype="multipart/form-data">
    <label for="ten_sanpham">Tên sản phẩm:</label>
    <input type="text" name="ten_sanpham" id="ten_sanpham" required><br>

    <label for="gia">Giá:</label>
    <input type="number" name="gia" id="gia" required><br>

    <label for="hinh_anh">Hình ảnh:</label>
    <input type="file" name="hinh_anh" id="hinh_anh" required><br>

    <label for="ma_loaisanpham">Loại sản phẩm:</label>
    <select name="ma_loaisanpham" id="ma_loaisanpham">
        <?php
        $sql = "SELECT * FROM loaisanpham";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['ma_loaisanpham'] . "'>" . htmlspecialchars($row['ten_loaisanpham']) . "</option>";
        }
        ?>
    </select><br>

    <label for="mau">Màu sắc (Chọn nhiều):</label>
    <select name="mau[]" id="mau" multiple>
        <?php
        $sql = "SELECT DISTINCT ten_mau FROM mausanpham";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['ten_mau'] . "'>" . htmlspecialchars($row['ten_mau']) . "</option>";
        }
        ?>
    </select><br>

    <label for="size">Kích thước (Chọn nhiều):</label>
    <select name="size[]" id="size" multiple>
        <?php
        $sql = "SELECT DISTINCT ten_size FROM size";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['ten_size'] . "'>" . htmlspecialchars($row['ten_size']) . "</option>";
        }
        ?>
    </select><br>

    <label for="mota">Mô tả sản phẩm:</label><br>
    <textarea name="mota" id="mota" rows="4" cols="50" required></textarea><br>

    <label for="soluongton">Số lượng tồn:</label>
    <input type="number" name="soluongton" id="soluongton" required><br>

    <button type="submit">Thêm sản phẩm</button>
    <a href="admin.php">
        <button type="button" class="back-button">Quay lại</button>
    </a>
</form>

    </div>
</body>
</html>
