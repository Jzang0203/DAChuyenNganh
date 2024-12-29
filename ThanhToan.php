<?php
include 'header.php';
include 'connect.php'; // Bao gồm kết nối cơ sở dữ liệu

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['ma_khachhang'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Giỏ hàng trống!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ho_ten = htmlspecialchars($_POST['fullname']);
    $DiaChi = htmlspecialchars($_POST['address']);
    $SoDienThoai = htmlspecialchars($_POST['phone']);
    $ghi_chu = isset($_POST['Node']) ? htmlspecialchars($_POST['Node']) : '';
    $hinh_thuc_thanhtoan = $_POST['payment_method'];
    $ma_khachhang = $_SESSION['ma_khachhang']; // Lấy mã khách hàng từ session
    $tong_tien = 0;

    foreach ($_SESSION['cart'] as $san_pham) {
        $tong_tien += $san_pham['quantity'] * $san_pham['price'];
    }

    $stmt = $conn->prepare("SELECT ma_nhanvien FROM nhanvien ORDER BY RAND() LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $ma_nhanvien = $result->fetch_assoc()['ma_nhanvien'];

    $conn->begin_transaction();

    try {
        // Thêm đơn hàng vào bảng donhang
        $stmt = $conn->prepare("INSERT INTO donhang (ma_nhanvien, ma_khachhang,SoDienThoai,DiaChi,tong_tien, trangthai) VALUES (?, ?, ?, ?, ?, 'Đang xử lý')");
        $stmt->bind_param('ssisi', $ma_nhanvien, $ma_khachhang,$SoDienThoai,$DiaChi,$tong_tien);
        $stmt->execute();
        $ma_donhang = $stmt->insert_id; // Lấy mã đơn hàng vừa thêm

        foreach ($_SESSION['cart'] as $san_pham) {
            $stmt = $conn->prepare("INSERT INTO chitietdonhang (ma_donhang, ma_sanpham, ma_size, ma_mau, so_luong, gia) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('iiiiii', $ma_donhang, $san_pham['id'], $san_pham['size'], $san_pham['color'], $san_pham['quantity'], $san_pham['price']);
            $stmt->execute();
        }


        $stmt = $conn->prepare("INSERT INTO giaodichthanhtoan (ma_donhang, hinhthuc_thanhtoan, ngay_thanhtoan, trangthai) VALUES (?, ?, NOW(), 'Đang xử lý')");
        $stmt->bind_param('is', $ma_donhang, $hinh_thuc_thanhtoan);
        $stmt->execute();

        $conn->commit();

        unset($_SESSION['cart']);

        header('Location: index.php');
        exit;

    } catch (Exception $e) {
        // Nếu có lỗi, rollback giao dịch
        $conn->rollback();
        echo "Có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại.";
    }
}
?>

<link rel="stylesheet" href="Demo/css/TT.css">
<body>
<div class="pay">
    <h1 class="title">Thông tin thanh toán</h1>
    <h2 class="subtitle">Sản phẩm trong giỏ hàng:</h2>
    <ul class="cart-list">
        <?php foreach ($_SESSION['cart'] as $san_pham): ?>
            <li class="cart-item">
                <strong><?php echo htmlspecialchars($san_pham['name']); ?></strong> - <?php echo $san_pham['quantity']; ?> x <?php echo number_format($san_pham['price'], 0, ',', '.'); ?> $
                <br><span>Màu sắc: <?php echo htmlspecialchars($san_pham['color']); ?>, Kích thước: <?php echo htmlspecialchars($san_pham['size']); ?></span>
            </li>
        <?php endforeach; ?>
    </ul>

    <form class="checkout" method="POST" action="">
        <div class="form-group">
            <label for="fullname">Họ và tên:</label>
            <input type="text" placeholder="Nhập tên của bạn..." id="fullname" name="fullname" required>
        </div>
        <div class="form-group">
            <label for="address">Địa chỉ:</label>
            <input type="text" placeholder="Nhập địa chỉ..." id="address" name="address" required>
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại:</label>
            <input type="text" placeholder="Nhập số điện thoại..." id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="Node">Ghi chú:</label>
            <input type="text" placeholder="Nhập ghi chú..." id="Node" name="Node">
        </div>
        <div class="form-group">
            <label>Hình thức thanh toán:</label>
            <div>
                <input type="radio" id="cash" name="payment_method" value="tiền mặt" required>
                <label for="cash">Thanh toán khi nhận hàng (COD)</label>
            </div>
            <div>
                <input type="radio" id="bank" name="payment_method" value="chuyển khoản">
                <label for="bank">Chuyển khoản ngân hàng</label>
            </div>
            <div>
                <input type="radio" id="momo" name="payment_method" value="chuyển khoản qua momo">
                <label for="momo">Ví điện tử (Momo, ZaloPay...)</label>
            </div>
        </div>
        <button type="submit" class="btn-submit">Xác nhận thanh toán</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutForm = document.querySelector('.checkout');
        checkoutForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn chặn gửi dữ liệu mặc định của form

            const hoTen = document.querySelector('#fullname').value;
            const diaChi = document.querySelector('#address').value;
            const soDienThoai = document.querySelector('#phone').value;
            const ghiChu = document.querySelector('#Node').value;
            const hinhThucThanhToan = document.querySelector('input[name="payment_method"]:checked');

            // Kiểm tra nếu các trường bắt buộc đã được điền đầy đủ và phương thức thanh toán đã được chọn
            if (hoTen && diaChi && soDienThoai && hinhThucThanhToan) {
                alert('Thanh toán thành công!');
                checkoutForm.submit(); // Nếu bạn muốn gửi form thực tế
            } else {
                alert('Vui lòng điền đầy đủ thông tin thanh toán và chọn hình thức thanh toán.');
            }
        });
    });
</script>
</body>
</html>
