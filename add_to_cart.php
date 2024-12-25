<?php
session_start();
include 'connect.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']); // Chuyển ID thành số nguyên để tránh lỗi

    if ($product_id <= 0) {
        echo "ID sản phẩm không hợp lệ.";
        exit;
    }

    // Truy vấn sản phẩm từ cơ sở dữ liệu
    $sql = "SELECT * FROM sanpham WHERE ma_sanpham = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id); // Bind ID sản phẩm
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Lấy thông tin mã màu và mã kích thước từ URL
        $color_id = isset($_GET['color']) ? $_GET['color'] : null;
        $size_id = isset($_GET['size']) ? $_GET['size'] : null;

        // Truy vấn lấy tên màu và mã màu từ mã màu
        $sql_color = "SELECT ma_mau, ten_mau FROM mausanpham WHERE ma_mau = ?";
        $stmt_color = $conn->prepare($sql_color);
        $stmt_color->bind_param("i", $color_id);
        $stmt_color->execute();
        $color_result = $stmt_color->get_result();
        $color = ($color_result->num_rows > 0) ? $color_result->fetch_assoc() : null;

        // Truy vấn lấy tên kích thước và mã kích thước từ mã kích thước
        $sql_size = "SELECT ma_size, ten_size FROM size WHERE ma_size = ?";
        $stmt_size = $conn->prepare($sql_size);
        $stmt_size->bind_param("i", $size_id);
        $stmt_size->execute();
        $size_result = $stmt_size->get_result();
        $size = ($size_result->num_rows > 0) ? $size_result->fetch_assoc() : null;

        // Tìm kiếm sản phẩm trong giỏ hàng
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            // Kiểm tra nếu sản phẩm đã có trong giỏ với cùng màu và kích thước
            if ($item['id'] == $product['ma_sanpham'] && $item['color'] == $color['ma_mau'] && $item['size'] == $size['ma_size']) {
                $item['quantity']++; // Tăng số lượng nếu đã có
                $found = true;
                break;
            }
        }

        // Nếu sản phẩm chưa có trong giỏ hàng, thêm vào giỏ hàng
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['ma_sanpham'],
                'name' => $product['ten_sanpham'],
                'price' => $product['gia'],
                'quantity' => 1,
                'color' => $color['ma_mau'],  // Lưu mã màu
                'size' => $size['ma_size']    // Lưu mã size
            ];
        }

        // Chuyển hướng lại trang trước hoặc về trang giỏ hàng
        $redirect_url = $_SERVER['HTTP_REFERER'] ?? 'cart.php';
        header("Location: $redirect_url");
        exit;
    } else {
        echo "Sản phẩm không tồn tại.";
    }

    $stmt->close();
} else {
    echo "Không có sản phẩm được chọn.";
}
$conn->close();
?>
