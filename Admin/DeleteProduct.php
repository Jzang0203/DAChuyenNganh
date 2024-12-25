<?php
include '../connect.php';
session_start(); 

if (!isset($_SESSION['ma_quantrivien'])) {
    echo "<script>alert('Vui lòng đăng nhập lại!'); window.location.href = '../Login.php';</script>";
    exit();
}

$ma_quantrivien = $_SESSION['ma_quantrivien'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ma_sanpham'])) {
    $ma_sanpham = $_POST['ma_sanpham'];

    // Lấy thông tin hình ảnh sản phẩm
    $sql = "SELECT hinh_anh FROM sanpham WHERE ma_sanpham = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ma_sanpham);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $hinh_anh = $row['hinh_anh'];
        $file_path = "../Demo/img/" . $hinh_anh;

        // Xóa file hình ảnh nếu tồn tại
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Xóa màu sắc liên quan đến sản phẩm
        $sql_mau = "DELETE FROM mausanpham WHERE ma_sanpham = ?";
        $stmt_mau = $conn->prepare($sql_mau);
        $stmt_mau->bind_param("i", $ma_sanpham);
        $stmt_mau->execute();

        // Xóa kích thước liên quan đến sản phẩm
        $sql_size = "DELETE FROM size WHERE ma_sanpham = ?";
        $stmt_size = $conn->prepare($sql_size);
        $stmt_size->bind_param("i", $ma_sanpham);
        $stmt_size->execute();

        // Xóa sản phẩm
        $sql_sanpham = "DELETE FROM sanpham WHERE ma_sanpham = ?";
        $stmt_sanpham = $conn->prepare($sql_sanpham);
        $stmt_sanpham->bind_param("i", $ma_sanpham);
        $stmt_sanpham->execute();

        echo "<script>alert('Sản phẩm đã được xóa thành công!'); window.location.href = 'DeleteProduct.php';</script>";
    } else {
        echo "<script>alert('Không tìm thấy sản phẩm để xóa!'); window.location.href = 'DeleteProduct.php';</script>";
    }
}

// Lấy danh sách sản phẩm để hiển thị
$sql = "SELECT * FROM sanpham";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa sản phẩm</title>
    <link rel="stylesheet" href="/Demo/css/qtv.css">
</head>
<body>
    <div class="container">
        <h1>Xóa sản phẩm</h1>
        <a href="admin.php">
            <button type="button" class="back-button">Quay lại</button>
        </a>
        <table border="1">
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Hình ảnh</th>
                <th>Hành động</th>
            </tr>

            <?php
            $stt = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $stt++ . "</td>";
                echo "<td>" . htmlspecialchars($row['ten_sanpham']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gia']) . "</td>";
                echo "<td><img src='../Demo/img/" . htmlspecialchars($row['hinh_anh']) . "' width='100' height='100'></td>";
                echo "<td>
                        <form method='POST' action='' style='display:inline'>
                            <input type='hidden' name='ma_sanpham' value='" . $row['ma_sanpham'] . "'>
                            <button type='submit' onclick='return confirm(\"Bạn có chắc chắn muốn xóa sản phẩm này?\")'>Xóa</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
        
    </div>
</body>
</html>
