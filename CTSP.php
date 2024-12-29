<?php
include 'header.php';
include 'connect.php';

// Lấy ID sản phẩm từ URL
$chitiet = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Truy vấn chi tiết sản phẩm
$sql = "SELECT * FROM sanpham WHERE ma_sanpham = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $chitiet);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();

    // Lấy các lựa chọn màu sắc cho sản phẩm
    $sql_colors = "SELECT * FROM mausanpham WHERE ma_sanpham = ?";
    $stmt_colors = $conn->prepare($sql_colors);
    $stmt_colors->bind_param("i", $chitiet);
    $stmt_colors->execute();
    $colors_result = $stmt_colors->get_result();

    // Lấy các lựa chọn kích thước cho sản phẩm
    $sql_sizes = "SELECT * FROM size WHERE ma_sanpham = ?";
    $stmt_sizes = $conn->prepare($sql_sizes);
    $stmt_sizes->bind_param("i", $chitiet);
    $stmt_sizes->execute();
    $sizes_result = $stmt_sizes->get_result();
} else {
    echo "<h2>Không tìm thấy sản phẩm!</h2>";
    include 'footer.php';
    exit();
}
?>

<main class="product-detail">
    <div class="container">
        <!-- Phần bên trái: Hình ảnh sản phẩm -->
        <div class="left">
            <img src="/Demo/img/<?php echo htmlspecialchars($product['hinh_anh']); ?>" 
                 alt="<?php echo htmlspecialchars($product['ten_sanpham']); ?>" 
                 class="main-image">
            
            <!-- Hình ảnh thu nhỏ -->
            <div class="thumbnail-images">
                <img src="/Demo/img/<?php echo htmlspecialchars($product['hinh_anh']); ?>" 
                     alt="<?php echo htmlspecialchars($product['ten_sanpham']); ?>" 
                     class="thumbnail" 
                     onclick="changeMainImage(this)">
                <!-- Thêm các hình ảnh khác nếu cần -->
            </div>
        </div>

        <!-- Phần bên phải: Thông tin sản phẩm -->
        <div class="right">
            <h1 class="product-title"><?php echo htmlspecialchars($product['ten_sanpham']); ?></h1>
            <p class="product-price">Giá: <span class="price"><?php echo number_format($product['gia'], 0, ',', '.'); ?> $</span></p>
            <p class="product-description"><?php echo nl2br(htmlspecialchars($product['mota'])); ?></p>

            <!-- Chọn màu sắc -->
            <div class="product-colors">
                <label for="color">Màu sắc:</label>
                <select id="color" name="color">
                    <?php while ($color = $colors_result->fetch_assoc()) { ?>
                        <option value="<?php echo htmlspecialchars($color['ma_mau']); ?>"><?php echo htmlspecialchars($color['ten_mau']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Chọn kích thước -->
            <div class="product-sizes">
                <label for="size">Kích thước:</label>
                <select id="size" name="size">
                    <?php while ($size = $sizes_result->fetch_assoc()) { ?>
                        <option value="<?php echo htmlspecialchars($size['ma_size']); ?>"><?php echo htmlspecialchars($size['ten_size']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Thêm vào giỏ hàng -->
            <button class="btn" onclick="addToCart(<?php echo $product['ma_sanpham']; ?>, '<?php echo addslashes($product['ten_sanpham']); ?>', <?php echo $product['gia']; ?>)">Thêm vào giỏ hàng</button>
            
            <a href="javascript:history.back()" class="back-btn">Quay lại trang chủ</a>
        </div>
    </div>
</main>

<script>
    // Thay đổi hình ảnh chính
    function changeMainImage(imgElement) {
        document.querySelector('.main-image').src = imgElement.src;
    }

    function addToCart(productId, productName, price) {
    // Lấy giá trị màu sắc và kích thước đã chọn
    var color = document.getElementById('color').value;
    var size = document.getElementById('size').value;
    // Thêm sản phẩm vào giỏ hàng và truyền màu và kích thước đã chọn
    window.location.href = `add_to_cart.php?id=${encodeURIComponent(productId)}&name=${encodeURIComponent(productName)}&price=${encodeURIComponent(price)}&color=${encodeURIComponent(color)}&size=${encodeURIComponent(size)}`;
}
</script>
