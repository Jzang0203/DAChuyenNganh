<?php include '../header.php'; ?>
<?php include '../connect.php'; ?>

<main>
    <br><br>
    <h2 style="font-size: 36px; text-align: center; color: #333;">Quản lý sản phẩm</h2> <!-- Tăng kích thước chữ -->
    <div class="manage-options" style="text-align: center; margin-bottom: 30px;">
        <ul style="list-style-type: none; padding: 0; display: inline-block;">
            <td><a href="./AddProduct.php" style="color: #fff; background-color: #28a745; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; font-size: 18px;">Thêm Sản Phẩm</a></td>
            <td><a href="./DeleteProduct.php" style="color: #fff; background-color: #dc3545; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; font-size: 18px;">Xóa Sản Phẩm</a></td>
        </ul>
    </div>
    <hr>

    <h3 style="text-align: center; color: #555;">Danh Sách Sản Phẩm</h3>

    <?php
    // Lấy tất cả sản phẩm
    $sql = "SELECT * FROM sanpham";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table' style='width: 100%; margin: 20px auto; border-collapse: collapse;'>
                <thead>
                    <tr style='background-color: #f8f9fa;'>
                        <th style='padding: 15px; text-align: left;'>Mã Sản Phẩm</th>
                        <th style='padding: 15px; text-align: left;'>Tên Sản Phẩm</th>
                        <th style='padding: 15px; text-align: left;'>Giá</th>
                        <th style='padding: 15px; text-align: left;'>Hình Ảnh</th>
                        <th style='padding: 15px; text-align: center;'>Chức Năng</th>
                    </tr>
                </thead>
                <tbody>";

        // Hiển thị tất cả sản phẩm
        while ($row = $result->fetch_assoc()) {
            echo "<tr style='border-bottom: 1px solid #ddd;'>";
            echo "<td style='padding: 10px;'>" . htmlspecialchars($row['ma_sanpham']) . "</td>";
            echo "<td style='padding: 10px;'>" . htmlspecialchars($row['ten_sanpham']) . "</td>";
            echo "<td style='padding: 10px;'>" . number_format($row['gia'], 2) . " VND</td>";
            echo "<td style='padding: 10px;'><img src='/Demo/img/" . htmlspecialchars($row['hinh_anh']) . "' width='100' height='100' alt='Ảnh sản phẩm'></td>";
            echo "<td style='padding: 10px; text-align: center;'>
                    <a href='./EditProduct.php?ma_sanpham=" . $row['ma_sanpham'] . "' style='color: #007bff; font-weight: bold;'>Sửa</a> 
                  </td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p style='text-align: center; color: #888;'>Hiện tại chưa có sản phẩm nào.</p>";
    }
    ?>

    <div class="global-icon-right-zalo">
        <a href="https://zalo.me/0388073445" target="_blank">
            <img src="https://www.tncstore.vn/static/assets/default/images/icon_zalo_2023.png" width="50px" height="50px" alt="icon-zalo">
        </a>
    </div>
    <div class="global-icon-right-facebook">
        <a href="https://www.facebook.com/puma.vietnam.official/?brand_redir=56470448215&locale=vi_VN" target="_blank">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b9/2023_Facebook_icon.svg" width="50px" height="50px" alt="icon-zalo">
        </a>
    </div>
</main>

<?php include '../footer.php'; ?>
</body>
</html>
