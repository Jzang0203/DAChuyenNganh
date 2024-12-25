<?php
include 'header.php';
include 'connect.php';

// Kiểm tra xem khách hàng đã đăng nhập hay chưa
if (!isset($_SESSION['ma_khachhang'])) {
    echo "<script>alert('Vui lòng đăng nhập để chỉnh sửa thông tin.'); window.location.href='login.php';</script>";
    exit();
}

// Lấy thông tin khách hàng từ database
$ma_khachhang = $_SESSION['ma_khachhang'];
$sql = "SELECT * FROM KhachHang WHERE ma_khachhang = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ma_khachhang);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<script>alert('Không tìm thấy thông tin khách hàng.'); window.location.href='info.php';</script>";
    exit();
}

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_khachhang = $_POST['ten_khachhang'];
    $email = $_POST['email'];
    $sodienthoai = $_POST['sodienthoai'];
    $diachi = $_POST['diachi'];

    // Cập nhật thông tin khách hàng trong database
    $update_sql = "UPDATE KhachHang SET ten_khachhang = ?, email = ?, sodienthoai = ?, diachi = ? WHERE ma_khachhang = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssss", $ten_khachhang, $email, $sodienthoai, $diachi, $ma_khachhang);

    if ($update_stmt->execute()) {
        echo "<script>alert('Cập nhật thông tin thành công!'); window.location.href='info_kh.php';</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra, vui lòng thử lại sau.');</script>";
    }
}
?>

<link rel="stylesheet" href="/Demo/css/infocss.css">
<div class="main-container" style="max-width: 600px; margin: 50px auto; text-align: center;">
    <h2>Chỉnh sửa thông tin khách hàng</h2>
    <form method="POST" action="" style="text-align: left;">
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="ten_khachhang" style="font-size: 1.2rem; display: block; margin-bottom: 8px;">Họ và tên:</label>
            <input type="text" id="ten_khachhang" name="ten_khachhang" value="<?php echo htmlspecialchars($user['ten_khachhang']); ?>" required style="width: 100%; padding: 10px; font-size: 1.1rem;">
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="email" style="font-size: 1.2rem; display: block; margin-bottom: 8px;">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required style="width: 100%; padding: 10px; font-size: 1.1rem;">
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="sodienthoai" style="font-size: 1.2rem; display: block; margin-bottom: 8px;">Số điện thoại:</label>
            <input type="text" id="sodienthoai" name="sodienthoai" value="<?php echo htmlspecialchars($user['sodienthoai']); ?>" required style="width: 100%; padding: 10px; font-size: 1.1rem;">
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="diachi" style="font-size: 1.2rem; display: block; margin-bottom: 8px;">Địa chỉ:</label>
            <input type="text" id="diachi" name="diachi" value="<?php echo htmlspecialchars($user['diachi']); ?>" required style="width: 100%; padding: 10px; font-size: 1.1rem;">
        </div>
        <button type="submit" class="button-save" style="background-color: #2e7d32; color: white; border: none; padding: 15px 30px; font-size: 1.2rem; cursor: pointer; border-radius: 5px; margin-top: 20px;">Lưu thông tin</button>
        <button type="button" class="button-cancel" onclick="window.location.href='info.php'" style="background-color: #d32f2f; color: white; border: none; padding: 15px 30px; font-size: 1.2rem; cursor: pointer; border-radius: 5px; margin-top: 20px;">Hủy</button>
    </form>
</div>

<?php include 'footer.php'; ?>
