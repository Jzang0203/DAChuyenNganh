<?php
// Kết nối đến cơ sở dữ liệu
include 'Connect.php';

// Xử lý khi người dùng submit form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $ten_san_pham = $_POST['ten_san_pham'];
    $loai = $_POST['loai']; // ID của loại sản phẩm
    $gia = $_POST['gia'];
    $so_luong = $_POST['so_luong'];
    $mau_sac = $_POST['mau_sac']; // Mảng màu sắc
    $size = $_POST['size']; // Mảng size
    $hinh_anh = $_FILES['hinh_anh']['name'];
    $target_dir = "img/";
    $target_file = $target_dir . basename($hinh_anh);

    // Upload file hình ảnh
    if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_file)) {
        // Thêm sản phẩm vào bảng sanpham
        $sql = "INSERT INTO sanpham (ten_sanpham, gia, soluongton, hinh_anh, ma_loaisanpham) 
                VALUES ('$ten_san_pham', '$gia', '$so_luong', '$target_file', '$loai')";
        
        if (mysqli_query($conn, $sql)) {
            $ma_sanpham = mysqli_insert_id($conn); // Lấy ID của sản phẩm mới

            // Thêm nhiều màu sắc vào bảng mausanpham
            foreach ($mau_sac as $mau) {
                $sql_mau = "INSERT INTO mausanpham (ma_sanpham, ten_mau) VALUES ('$ma_sanpham', '$mau')";
                mysqli_query($conn, $sql_mau);
            }

            // Thêm nhiều size vào bảng size
            foreach ($size as $s) {
                $sql_size = "INSERT INTO size (ma_sanpham, ten_size) VALUES ('$ma_sanpham', '$s')";
                mysqli_query($conn, $sql_size);
            }

            echo "Thêm sản phẩm thành công!";
        } else {
            echo "Lỗi: " . mysqli_error($conn);
        }
    } else {
        echo "Lỗi khi tải lên hình ảnh.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm</title>
</head>
<body>
    <h1>Thêm sản phẩm mới</h1>
    <form action="addsp.php" method="POST" enctype="multipart/form-data">
        <label for="ten_san_pham">Tên sản phẩm:</label>
        <input type="text" id="ten_san_pham" name="ten_san_pham" required><br><br>

        <label for="loai">Loại sản phẩm:</label>
        <select id="loai" name="loai" required>
            <?php
            // Lấy danh sách loại sản phẩm từ cơ sở dữ liệu
            $result = mysqli_query($conn, "SELECT * FROM loaisanpham");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['ma_loaisanpham'] . "'>" . $row['ten_loaisanpham'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="gia">Giá:</label>
        <input type="number" id="gia" name="gia" required><br><br>

        <label for="so_luong">Số lượng:</label>
        <input type="number" id="so_luong" name="so_luong" required><br><br>

        <label for="mau_sac">Màu sắc:</label>
        <select id="mau_sac" name="mau_sac[]" multiple required>
            <?php
            // Lấy danh sách màu sắc từ cơ sở dữ liệu
            $result_mau = mysqli_query($conn, "SELECT DISTINCT ten_mau FROM mausanpham");
            while ($row = mysqli_fetch_assoc($result_mau)) {
                echo "<option value='" . $row['ten_mau'] . "'>" . $row['ten_mau'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="size">Size:</label>
        <select id="size" name="size[]" multiple required>
            <?php
            // Lấy danh sách size từ cơ sở dữ liệu
            $result_size = mysqli_query($conn, "SELECT DISTINCT ten_size FROM size");
            while ($row = mysqli_fetch_assoc($result_size)) {
                echo "<option value='" . $row['ten_size'] . "'>" . $row['ten_size'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="hinh_anh">Hình ảnh:</label>
        <input type="file" id="hinh_anh" name="hinh_anh" required><br><br>

        <button type="submit">Thêm sản phẩm</button>
    </form>
</body>
</html>
