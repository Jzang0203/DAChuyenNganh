<?php
// Kết nối cơ sở dữ liệu
include 'connect.php';
include 'header.php';

if (!isset($_SESSION['ma_nhanvien'])) {
    echo "<script>alert('Bạn không có quyền truy cập. Vui lòng đăng nhập!'); window.location.href = 'login.php';</script>";
    exit();
}

if (!isset($_GET['ma_donhang'])) {
    echo "<script>alert('Không tìm thấy mã đơn hàng.'); window.location.href = 'Nhanvien.php';</script>";
    exit();
}
$ma_donhang = $_GET['ma_donhang'];

$sql_order = "SELECT dh.*, kh.ten_khachhang, dh.DiaChi, dh.SoDienThoai, gd.ngay_thanhtoan
              FROM donhang dh
              JOIN khachhang kh ON dh.ma_khachhang = kh.ma_khachhang
              JOIN giaodichthanhtoan gd ON dh.ma_donhang = gd.ma_donhang
              WHERE dh.ma_donhang = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("i", $ma_donhang);
$stmt_order->execute();
$result_order = $stmt_order->get_result();

if ($result_order->num_rows === 0) {
    echo "<script>alert('Đơn hàng không tồn tại.'); window.location.href = 'Nhanvien.php';</script>";
    exit();
}
$order = $result_order->fetch_assoc();

$sql_items = "SELECT cthd.*, sp.ten_sanpham, sp.gia
              FROM chitietdonhang cthd
              JOIN sanpham sp ON cthd.ma_sanpham = sp.ma_sanpham
              WHERE cthd.ma_donhang = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $ma_donhang);
$stmt_items->execute();
$result_items = $stmt_items->get_result();
?>

<main style="width: 80%; margin: 20px auto;">
    <h2 style="text-align: center;">Chi Tiết Đơn Hàng</h2>

    <!-- Thông Tin Đơn Hàng -->
    <div style="margin-bottom: 20px;">
        <h3>Thông Tin Đơn Hàng</h3>
        <p><strong>Mã Đơn Hàng:</strong> <?php echo $order['ma_donhang']; ?></p>
        <p><strong>Ngày Đặt:</strong>     <?php echo isset($order['ngay_thanhtoan']) ? $order['ngay_thanhtoan'] : 'Chưa thanh toán'; ?></p>
        <p><strong>Trạng Thái:</strong> <?php echo $order['trangthai']; ?></p>
    </div>

    <!-- Thông Tin Khách Hàng -->
    <div style="margin-bottom: 20px;">
        <h3>Thông Tin Khách Hàng</h3>
        <p><strong>Tên Khách Hàng:</strong> <?php echo $order['ten_khachhang']; ?></p>
        <p><strong>Địa Chỉ:</strong> <?php echo $order['DiaChi']; ?></p>
        <p><strong>Số Điện Thoại:</strong> <?php echo $order['SoDienThoai']; ?></p>
    </div>

    <!-- Danh Sách Sản Phẩm -->
    <div>
        <h3>Danh Sách Sản Phẩm</h3>
        <table border="1" cellspacing="0" cellpadding="10" style="width: 100%; border-collapse: collapse; text-align: center;">
            <tr>
                <th>Mã Sản Phẩm</th>
                <th>Tên Sản Phẩm</th>
                <th>Giá</th>
                <th>Số Lượng</th>
                <th>Thành Tiền</th>
            </tr>
            <?php
            $tong_tien = 0;
            while ($item = $result_items->fetch_assoc()) {
                $thanh_tien = $item['so_luong'] * $item['gia'];
                $tong_tien += $thanh_tien;

                echo "<tr>";
                echo "<td>" . $item['ma_sanpham'] . "</td>";
                echo "<td>" . $item['ten_sanpham'] . "</td>";
                echo "<td>" . number_format($item['gia'], 2) . "</td>";
                echo "<td>" . $item['so_luong'] . "</td>";
                echo "<td>" . number_format($thanh_tien, 2) . "</td>";
                echo "</tr>";
            }
            ?>
            <tr>
                <td colspan="4"><strong>Tổng Cộng:</strong></td>
                <td><strong><?php echo number_format($tong_tien, 2); ?></strong></td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <a href="Nhanvien.php" style="text-decoration: none; padding: 10px 20px; background: #007bff; color: #fff; border-radius: 5px;">Quay Lại</a>
    </div>
</main>

<?php
$stmt_order->close();
$stmt_items->close();
$conn->close();
include 'footer.php';
?>
