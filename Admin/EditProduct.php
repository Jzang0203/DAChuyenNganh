<?php
include '../connect.php';
session_start();

if (!isset($_SESSION['ma_quantrivien'])) {
    echo "<script>alert('Vui lòng đăng nhập lại!'); window.location.href = '../Login.php';</script>";
    exit();
}

$ma_quantrivien = $_SESSION['ma_quantrivien'];

// Kiểm tra nếu có ID sản phẩm được truyền vào
if (!isset($_GET['ma_sanpham'])) {
    echo "<script>alert('Sản phẩm không tồn tại!'); window.location.href = 'admin.php';</script>";
    exit();
}

$ma_sanpham = $_GET['ma_sanpham'];

// Lấy thông tin sản phẩm
$sql = "SELECT * FROM sanpham WHERE ma_sanpham = ? AND ma_quantrivien = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $ma_sanpham, $ma_quantrivien);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<script>alert('Sản phẩm không tồn tại!'); window.location.href = 'admin.php';</script>";
    exit();
}

$sql_mau = "SELECT ten_mau FROM mausanpham WHERE ma_sanpham = ?";
$stmt_mau = $conn->prepare($sql_mau);
$stmt_mau->bind_param("i", $ma_sanpham);
$stmt_mau->execute();
$result_mau = $stmt_mau->get_result();

$sql_size = "SELECT ten_size FROM size WHERE ma_sanpham = ?";
$stmt_size = $conn->prepare($sql_size);
$stmt_size->bind_param("i", $ma_sanpham);
$stmt_size->execute();
$result_size = $stmt_size->get_result();

// Xử lý cập nhật sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nhận thông tin từ biểu mẫu
    $ten_sanpham = $_POST['ten_sanpham'];
    $gia = $_POST['gia'];
    $ma_loaisanpham = $_POST['ma_loaisanpham'];
    $mau = $_POST['mau'];
    $size = $_POST['size'];
    $mo_ta = $_POST['mota'];
    $so_luong_ton = $_POST['soluongton'];

    // Cập nhật thông tin sản phẩm
    $sql_update = "UPDATE sanpham SET ten_sanpham = ?, mota = ?, gia = ?, soluongton = ?, ma_loaisanpham = ? WHERE ma_sanpham = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssdisi", $ten_sanpham, $mo_ta, $gia, $so_luong_ton, $ma_loaisanpham, $ma_sanpham);
    $stmt_update->execute();

    // Cập nhật màu sắc
    $sql_delete_mau = "DELETE FROM mausanpham WHERE ma_sanpham = ?";
    $stmt_delete_mau = $conn->prepare($sql_delete_mau);
    $stmt_delete_mau->bind_param("i", $ma_sanpham);
    $stmt_delete_mau->execute();

    foreach ($mau as $m) {
        $sql_mau = "INSERT INTO mausanpham (ma_sanpham, ten_mau) VALUES (?, ?)";
        $stmt_mau = $conn->prepare($sql_mau);
        $stmt_mau->bind_param("is", $ma_sanpham, $m);
        $stmt_mau->execute();
    }

    // Cập nhật kích thước
    $sql_delete_size = "DELETE FROM size WHERE ma_sanpham = ?";
    $stmt_delete_size = $conn->prepare($sql_delete_size);
    $stmt_delete_size->bind_param("i", $ma_sanpham);
    $stmt_delete_size->execute();

    foreach ($size as $s) {
        $sql_size = "INSERT INTO size (ma_sanpham, ten_size) VALUES (?, ?)";
        $stmt_size = $conn->prepare($sql_size);
        $stmt_size->bind_param("is", $ma_sanpham, $s);
        $stmt_size->execute();
    }

    echo "<script>alert('Cập nhật sản phẩm thành công!'); window.location.href = 'admin.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa sản phẩm</title>
    <link rel="stylesheet" href="/Demo/css/qtv.css">
</head>
<body>
    <div class="container">
        <h1>Chỉnh sửa sản phẩm</h1>
        <form method="POST">
            <label for="ten_sanpham">Tên sản phẩm:</label>
            <input type="text" name="ten_sanpham" id="ten_sanpham" value="<?php echo htmlspecialchars($product['ten_sanpham']); ?>" required><br>

            <label for="gia">Giá:</label>
            <input type="number" name="gia" id="gia" value="<?php echo htmlspecialchars($product['gia']); ?>" required><br>

            <label for="ma_loaisanpham">Loại sản phẩm:</label>
            <select name="ma_loaisanpham" id="ma_loaisanpham">
                <?php
                $sql_loai = "SELECT * FROM loaisanpham";
                $result_loai = $conn->query($sql_loai);
                while ($row = $result_loai->fetch_assoc()) {
                    $selected = ($row['ma_loaisanpham'] == $product['ma_loaisanpham']) ? "selected" : "";
                    echo "<option value='" . $row['ma_loaisanpham'] . "' $selected>" . htmlspecialchars($row['ten_loaisanpham']) . "</option>";
                }
                ?>
            </select><br>

            <label for="mau">Màu sắc (Chọn nhiều):</label>
            <select name="mau[]" id="mau" multiple>
                <?php
                while ($row_mau = $result_mau->fetch_assoc()) {
                    echo "<option value='" . $row_mau['ten_mau'] . "' selected>" . htmlspecialchars($row_mau['ten_mau']) . "</option>";
                }
                ?>
            </select><br>

            <label for="size">Kích thước (Chọn nhiều):</label>
            <select name="size[]" id="size" multiple>
                <?php
                while ($row_size = $result_size->fetch_assoc()) {
                    echo "<option value='" . $row_size['ten_size'] . "' selected>" . htmlspecialchars($row_size['ten_size']) . "</option>";
                }
                ?>
            </select><br>

            <label for="mota">Mô tả sản phẩm:</label><br>
            <textarea name="mota" id="mota" rows="4" cols="50" required><?php echo htmlspecialchars($product['mota']); ?></textarea><br>

            <label for="soluongton">Số lượng tồn:</label>
            <input type="number" name="soluongton" id="soluongton" value="<?php echo htmlspecialchars($product['soluongton']); ?>" required><br>

            <button type="submit">Cập nhật sản phẩm</button>
            <a href="admin.php">
                <button type="button" class="back-button">Quay lại</button>
            </a>
        </form>
    </div>
</body>
</html>
