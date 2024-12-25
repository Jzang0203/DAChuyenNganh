<?php
include 'header.php';
include 'connect.php';

// Kiểm tra giỏ hàng có dữ liệu hay không
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];  // Khởi tạo giỏ hàng nếu chưa có
}

// Lấy dữ liệu màu sắc và kích thước từ cơ sở dữ liệu
$sql_color = "SELECT * FROM mausanpham";
$result_color = $conn->query($sql_color);

$sql_size = "SELECT * FROM size";
$result_size = $conn->query($sql_size);

// Xử lý cập nhật màu sắc và kích thước
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['index'])) {
        $index = $_POST['index'];

        // Cập nhật mã màu
        if (isset($_POST['color']) && $_POST['color'] !== '') {
            $_SESSION['cart'][$index]['color'] = $_POST['color'];  // Lưu ma_mau
        }

        // Cập nhật mã kích thước
        if (isset($_POST['size']) && $_POST['size'] !== '') {
            $_SESSION['cart'][$index]['size'] = $_POST['size'];  // Lưu ma_size
        }

        // Cập nhật số lượng
        if (isset($_POST['quantity'])) {
            $quantity = (int)$_POST['quantity'];
            $_SESSION['cart'][$index]['quantity'] = max(1, $quantity);  
        }

        header('Location: cart.php');
        exit;
    }
}

// Xử lý xóa sản phẩm
if (isset($_GET['id'])) {
    $index = $_GET['id'];
    unset($_SESSION['cart'][$index]);
    header('Location: cart.php');
    exit;
}

?>
<link rel="stylesheet" href="/Demo/css/cardcss.css">
<main>
    <h1>Giỏ hàng</h1>
    <hr>

    <?php
    // Kiểm tra giỏ hàng có dữ liệu hay không
    if (!empty($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $totalPrice = 0;

        echo "<table class='cart-table'>";
        echo "<thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Màu sắc</th>
                    <th>Kích thước</th>
                    <th>Tổng</th>
                    <th>Thao tác</th>
                </tr>
              </thead>";
        echo "<tbody>";

        // Hiển thị sản phẩm trong giỏ hàng
        foreach ($cart as $index => $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $totalPrice += $itemTotal;

            echo "<tr>";
            echo "<td>" . htmlspecialchars($item['name']) . "</td>";

            // Hiển thị phần chọn số lượng
            echo "<td>
                    <form method='post' action='cart.php' class='cart-form'>
                        <input type='number' name='quantity' value='" . $item['quantity'] . "' min='1' class='quantity-input'>
                        <input type='hidden' name='index' value='$index'>
                        <button type='submit' class='btn-update'>Cập nhật</button>
                    </form>
                </td>";

            // Hiển thị màu sắc và kích thước
            echo "<td>";
            if (empty($item['color'])) {
                echo "<form method='post' action='cart.php'>
                        <select name='color'>
                            <option value=''>Chọn màu</option>";
                            while ($row_color = $result_color->fetch_assoc()) {
                                if ($row_color['ma_sanpham'] == $item['id']) {
                                    echo "<option value='" . $row_color['ma_mau'] . "'>" . $row_color['ten_mau'] . "</option>";  // Lưu ma_mau vào giỏ hàng
                                }
                            }
                echo "</select>
                        <input type='hidden' name='index' value='$index'>
                        <button type='submit' class='btn-update'>Cập nhật màu</button>
                      </form>";
            } else {
                // Tìm tên màu từ cơ sở dữ liệu để hiển thị
                $stmt_color_name = $conn->prepare("SELECT ten_mau FROM mausanpham WHERE ma_mau = ?");
                $stmt_color_name->bind_param('s', $item['color']);
                $stmt_color_name->execute();
                $result_color_name = $stmt_color_name->get_result();
                $color_name = $result_color_name->fetch_assoc()['ten_mau'];
                echo htmlspecialchars($color_name);  // Hiển thị tên màu
            }
            echo "</td>";

            // Hiển thị kích thước
            echo "<td>";
            if (empty($item['size'])) {
                echo "<form method='post' action='cart.php'>
                        <select name='size'>
                            <option value=''>Chọn kích thước</option>";
                            while ($row_size = $result_size->fetch_assoc()) {
                                if ($row_size['ma_sanpham'] == $item['id']) {
                                    echo "<option value='" . $row_size['ma_size'] . "'>" . $row_size['ten_size'] . "</option>";  // Lưu ma_size vào giỏ hàng
                                }
                            }
                echo "</select>
                        <input type='hidden' name='index' value='$index'>
                        <button type='submit' class='btn-update'>Cập nhật kích thước</button>
                      </form>";
            } else {
                // Tìm tên kích thước từ cơ sở dữ liệu để hiển thị
                $stmt_size_name = $conn->prepare("SELECT ten_size FROM size WHERE ma_size = ?");
                $stmt_size_name->bind_param('i', $item['size']);
                $stmt_size_name->execute();
                $result_size_name = $stmt_size_name->get_result();
                $size_name = $result_size_name->fetch_assoc()['ten_size'];
                echo htmlspecialchars($size_name);  // Hiển thị tên kích thước
            }
            echo "</td>";

            // Hiển thị tổng giá trị (giá sản phẩm * số lượng)
            echo "<td>" . number_format($itemTotal, 0, ',', '.') . " $</td>";

            // Thao tác xóa sản phẩm
            echo "<td><a href='cart.php?id=$index' class='btn-remove'>Xóa</a></td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";

        // Hiển thị tổng giá trị giỏ hàng
        echo "<div class='cart-summary'>
                <h3>Tổng giá trị giỏ hàng: " . number_format($totalPrice, 0, ',', '.') . " $</h3>
                <a href='ThanhToan.php' class='btn-checkout'>Thanh toán</a>
              </div>";
    } else {
        echo '<h1 style="text-align: center;">Giỏ hàng của bạn đang trống.</h1><hr>';
    }
    ?>
</main>