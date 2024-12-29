<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JzangSneaker Shop</title>
    <link rel="stylesheet" href="/Demo/css/style.css">
</head>
<body>
    <header>
        <h1>Jzang Sneaker</h1>
        <div class="search-container">
            <input type="text" id="search-bar" placeholder="Search...">
            <button id="search-button">Search</button>
            <button><a href="/cart.php">
                <img src="/Demo/img/Banner/shoppingcart.png" width="12" height="12">
                
                <!-- Hiển thị số lượng giỏ hàng -->
                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                    <span class="cart-count" style="color:black"><?php echo count($_SESSION['cart']); ?></span>
                <?php endif; ?>
            </a></button>
        </div>
        <nav class="headerNAV">
            <ul>
                <?php if (isset($_SESSION['ma_quantrivien'])): ?>
                    <li><a href="/Admin/admin.php">Quản Lý Giày</a></li>
                    <li><a href="/Admin/QLTK.php">Quản Lý Khách Hàng</a></li>
                <?php elseif (isset($_SESSION['ma_nhanvien'])): ?>
                    <li><a href="/Nhanvien.php">Quản lý đơn hàng</a></li>
                <?php else: ?>
                    <li><a href="/index.php">Home</a></li>
                <?php endif; ?>

                <li>
                    <a href="#">Danh Mục Sản Phẩm</a>
                    <ul class="dropdown">
                        <li><a href="/Adidas.php">Adidas</a></li>
                        <li><a href="/Nike.php">Nike</a></li>
                        <li><a href="/Puma.php">Puma</a></li>
                    </ul>
                </li>
                <li><a href="/About.php">About</a></li>
                <li><a href="#" id="contact-link">Contact</a></li>
                <li><a href="/ThanhToan.php">Pay</a></li>

                <?php if (isset($_SESSION['ma_khachhang'])): ?>
                    <!-- Nếu là khách hàng -->
                    <li><a href="/info_kh.php"><?php echo $_SESSION['ten_khachhang']; ?></a></li>
                    <li><a href="/logout.php">Đăng xuất</a></li>
                <?php elseif (isset($_SESSION['ma_quantrivien'])): ?>
                    <!-- Nếu là quản trị viên -->
                    <li><?php echo 'Quản trị viên'; ?></li>
                    <li><a href="/logout.php">Đăng xuất</a></li>
                <?php elseif (isset($_SESSION['ma_nhanvien'])): ?>
                    <!-- Nếu là nhân viên -->
                    <li><?php echo'Nhân viên'; ?></li>
                    <li><a href="/logout.php">Đăng xuất</a></li>
                <?php else: ?>
                    <!-- Nếu chưa đăng nhập -->
                    <li><a href="/Login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <script>
        document.getElementById('search-button').addEventListener('click', function() {
            var searchQuery = document.getElementById('search-bar').value;
            if (searchQuery) {
                window.location.href = "/search.php?q=" + encodeURIComponent(searchQuery);
            }
        });
    </script>
</body>
</html>
