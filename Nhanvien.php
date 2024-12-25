<?php
// Kết nối cơ sở dữ liệu
include 'connect.php';
include 'header.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['ma_nhanvien'])) {
    echo "<script>alert('Bạn không có quyền truy cập. Vui lòng đăng nhập!'); window.location.href = 'login.php';</script>";
    exit();
}

// Xử lý sửa trạng thái hóa đơn
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $ma_donhang = $_POST['ma_donhang'];
    $trangthai = $_POST['trangthai'];

    $sql_update = "UPDATE donhang SET trangthai = ? WHERE ma_donhang = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("si", $trangthai, $ma_donhang);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật trạng thái thành công!'); window.location.href = '';</script>";
    } else {
        echo "<script>alert('Cập nhật trạng thái thất bại: " . $conn->error . "');</script>";
    }

    $stmt->close();
}
?>

<main>
    <h2 style="text-align: center;">Quản lý hóa đơn</h2>

    <!-- Danh Sách Hóa Đơn -->
    <table border="1" cellspacing="0" cellpadding="10" style="width: 80%; margin: 20px auto; border-collapse: collapse; text-align: center;">
        <tr>
            <th>Mã Đơn Hàng</th>
            <th>Mã Nhân Viên</th>
            <th>Mã Khách Hàng</th>
            <th>Tổng Tiền</th>
            <th>Trạng Thái</th>
            <th>Chi Tiết</th>
            <th>Cập Nhật</th>
        </tr>

        <?php
        // Lấy danh sách hóa đơn
        $sql = "SELECT * FROM donhang";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['ma_donhang'] . "</td>";
                echo "<td>" . $row['ma_nhanvien'] . "</td>";
                echo "<td>" . $row['ma_khachhang'] . "</td>";
                echo "<td>" . number_format($row['tong_tien'], 2) . "</td>";
                echo "<td>" . $row['trangthai'] . "</td>";
                echo "<td><a href='CTDH.php?ma_donhang=" . $row['ma_donhang'] . "' style='color: black; font-weight: bold;'>Xem Chi Tiết</a></td>";
                echo "<td>
                    <form method='POST' action='' style='margin: 0;'>
                        <input type='hidden' name='ma_donhang' value='" . $row['ma_donhang'] . "'>
                        <select name='trangthai'>
                            <option value='Đang xử lý' " . ($row['trangthai'] == 'Đang xử lý' ? 'selected' : '') . ">Đang xử lý</option>
                            <option value='Đã thanh toán' " . ($row['trangthai'] == 'Đã thanh toán' ? 'selected' : '') . ">Đã thanh toán</option>
                        </select>
                        <button type='submit' name='update_status'>Cập Nhật</button>
                    </form>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Không có hóa đơn nào.</td></tr>";
        }
        ?>
    </table>
</main>

<?php include 'footer.php'; ?>
