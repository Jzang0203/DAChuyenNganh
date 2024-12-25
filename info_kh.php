<?php
include 'header.php';
include 'connect.php'; // Kết nối cơ sở dữ liệu

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
    echo "Không tìm thấy thông tin khách hàng.";
    exit();
}
?>

<link rel="stylesheet" href="/Demo/css/infocss.css">
<div class="main-container">
    <h2>Thông tin khách hàng</h2>
    <div class="customer-info">
        <p><strong>Họ và tên:</strong> <?php echo $user['ten_khachhang']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p><strong>Số điện thoại:</strong> <?php echo $user['sodienthoai']; ?></p>
        <p><strong>Địa chỉ:</strong> <?php echo $user['diachi']; ?></p>
    </div>
    <button class="button-info" onclick="window.location.href='UPinfo_kh.php'">Chỉnh sửa thông tin</button>

    <h2>Danh sách đơn hàng</h2>

    <?php
    // Truy vấn lấy thông tin các đơn hàng của khách hàng
    $sql = "SELECT * FROM donhang WHERE ma_khachhang = ? ORDER BY ma_donhang DESC"; // Lấy đơn hàng mới nhất trước
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ma_khachhang);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($order = $result->fetch_assoc()) {
            $order_id = $order['ma_donhang'];
            $order_status = $order['trangthai'];
            $total_amount = $order['tong_tien'];

            // Lấy ngày thanh toán từ bảng giaodichthanhtoan (nếu có)
            $sql_payment = "SELECT ngay_thanhtoan FROM giaodichthanhtoan WHERE ma_donhang = ?";
            $stmt_payment = $conn->prepare($sql_payment);
            $stmt_payment->bind_param("i", $order_id);
            $stmt_payment->execute();
            $result_payment = $stmt_payment->get_result();

            $order_date = null;
            if ($result_payment->num_rows > 0) {
                $payment = $result_payment->fetch_assoc();
                $order_date = $payment['ngay_thanhtoan']; // Lấy ngày thanh toán
            }

            echo "<div class='order-item'>";
            echo "<p><strong>Đơn hàng #" . $order_id . "</strong></p>";
            echo "<p><strong>Ngày đặt hàng:</strong> " . ($order_date ? $order_date : 'Chưa có ngày thanh toán') . "</p>";
            echo "<p><strong>Tổng tiền:</strong> " . number_format($total_amount, 0, ',', '.') . " $</p>";
            echo "<p><strong>Trạng thái:</strong> " . $order_status . "</p>";

            // Truy vấn chi tiết đơn hàng
            $sql_details = "SELECT chitietdonhang.*, sanpham.ten_sanpham, size.ten_size, mausanpham.ten_mau 
                FROM chitietdonhang
                JOIN sanpham ON chitietdonhang.ma_sanpham = sanpham.ma_sanpham
                JOIN size ON chitietdonhang.ma_size = size.ma_size
                JOIN mausanpham ON chitietdonhang.ma_mau = mausanpham.ma_mau
                WHERE chitietdonhang.ma_donhang = ?";
            $stmt_details = $conn->prepare($sql_details);
            $stmt_details->bind_param("i", $order_id);
            $stmt_details->execute();
            $result_details = $stmt_details->get_result();

// In ra truy vấn SQL và kết quả trả về
            if ($result_details->num_rows > 0) {
                echo "<h4>Chi tiết sản phẩm:</h4><ul>";
                while ($item = $result_details->fetch_assoc()) {
                $product_name = $item['ten_sanpham'];
                $size_name = $item['ten_size'];
                $color_name = $item['ten_mau'];
                $quantity = $item['so_luong'];
                $price = $item['gia'];

        echo "<li>Sản phẩm: $product_name, Số lượng: $quantity, Màu sắc: $color_name, Kích thước: $size_name, Giá: " . number_format($price, 0, ',', '.') . " $</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Không có sản phẩm trong đơn hàng.</p>";
}
            echo "</div>";
        }
    } else {
        echo "<p>Không có đơn hàng nào.</p>";
    }
    ?>
</div>

<?php include 'footer.php'; ?>
