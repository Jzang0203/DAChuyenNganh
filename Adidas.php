<?php include 'header.php'; ?>
<?php include 'connect.php'; ?>

<?php
$sql = "SELECT * FROM sanpham";
$result = $conn->query($sql);

// Kiểm tra và hiển thị sản phẩm theo nhóm
$brands = ['DURAMO', 'ULTRA', 'ADIZERO', 'SUPERNOVA'];
foreach ($brands as $brand) {
    echo "<h1>$brand</h1> <br>";
    echo "<div class='product'>";
    echo "<div class='product-slider' id='slider-$brand'>"; // Slider sản phẩm
    while ($row = $result->fetch_assoc()) {
        if (strpos($row['ten_sanpham'], $brand) !== false) {
            echo "<div class='sp'>";
            echo "<a href='CTSP.php?id=" . htmlspecialchars($row['ma_sanpham']) . "'>";
            echo "<img src='/Demo/img/" . htmlspecialchars($row['hinh_anh']) . "' alt='" . htmlspecialchars($row['ten_sanpham']) . "'>";
            echo "</a>";
            echo "<p>" . htmlspecialchars($row['ten_sanpham']) . "</p>";
            echo "<p>Giá: \$" . number_format($row['gia'], 2) . "</p>";
            echo '<button class="btn" onclick="addToCart(\'' . addslashes($row['ma_sanpham']) . '\', \'' . addslashes($row['ten_sanpham']) . '\', ' . $row['gia'] . ')">Thêm vào giỏ hàng</button>';
            echo "</div>";
        }
    }
    echo "</div><br>";
    echo "</div><br>";
    // Reset lại kết quả sau mỗi nhóm sản phẩm
    $result = $conn->query($sql);
}
$conn->close();
?>
<?php include 'footer.php'; ?>