<?php include '../header.php'; ?>
<?php include '../connect.php'; ?>

<main>
    <br><br>
    <h2 style="font-size: 36px; text-align: center; color: #333;">Quản lý Tài Khoản Khách Hàng</h2>
    <h3 style="color: #555;">Danh Sách Tài Khoản Khách Hàng</h3>

    <?php
    // Lấy tất cả tài khoản khách hàng
    $sql = "SELECT * FROM khachhang";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table' style='width: 100%; margin: 20px auto; border-collapse: collapse;'>
                <thead>
                    <tr style='background-color: #f8f9fa;'>
                        <th style='padding: 15px; text-align: left;'>Mã Khách Hàng</th>
                        <th style='padding: 15px; text-align: left;'>Tên Khách Hàng</th>
                        <th style='padding: 15px; text-align: left;'>Email</th>
                        <th style='padding: 15px; text-align: left;'>Số Điện Thoại</th>
                        <th style='padding: 15px; text-align: center;'>Trạng Thái</th>
                        <th style='padding: 15px; text-align: center;'>Chức Năng</th>
                    </tr>
                </thead>
                <tbody>";

        // Hiển thị tất cả tài khoản khách hàng
        while ($row = $result->fetch_assoc()) {
            $sodienthoai = isset($row['sodienthoai']) ? htmlspecialchars($row['sodienthoai']) : 'N/A';
            $trangthai = isset($row['trangthai']) ? ($row['trangthai'] == 1 ? 'Kích hoạt': 'Đã kích hoạt' ): 'Đã kích hoạt';

            echo "<tr style='border-bottom: 1px solid #ddd;'>";
            echo "<td style='padding: 10px;'>" . htmlspecialchars($row['ma_khachhang']) . "</td>";
            echo "<td style='padding: 10px;'>" . htmlspecialchars($row['ten_khachhang']) . "</td>";
            echo "<td style='padding: 10px;'>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td style='padding: 10px;'>" . $sodienthoai . "</td>";
            echo "<td style='padding: 10px; text-align: center;'>" . $trangthai . "</td>";
            echo "<td style='padding: 10px; text-align: center;'> 
                    <a href='./DeleteTK.php?ma_khachhang=" . $row['ma_khachhang'] . "' style='color: #dc3545; font-weight: bold;'>Xóa</a>
                  </td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p style='text-align: center; color: #888;'>Hiện tại chưa có tài khoản khách hàng nào.</p>";
    }
    ?>
</main>


</body>
</html>
