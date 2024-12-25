<?php include 'header.php'; ?>

<main class="center-content-dkf">
    <div class="dk">
        <h1>Thông tin đăng ký</h1>
        <form class="dkf" method="POST">
            <label for="fullname">Tài khoản:</label><br>
            <input type="text" placeholder="Nhập tên đăng nhập..." id="fullname" name="fullname" required style="width: 100%; padding: 10px; font-size: 1.1rem;"><br><br>

            <label for="ten_khachhang">Tên khách hàng:</label><br>
            <input type="text" placeholder="Nhập tên khách hàng..." id="ten_khachhang" name="ten_khachhang" required style="width: 100%; padding: 10px; font-size: 1.1rem;"><br><br>

            <label for="email">Email:</label><br>
            <input type="email" placeholder="Nhập email..." id="email" name="email" required style="width: 100%; padding: 10px; font-size: 1.1rem;"><br><br>

            <label for="sodienthoai">Số điện thoại:</label><br>
            <input type="text" placeholder="Nhập số điện thoại..." id="sodienthoai" name="sodienthoai" required style="width: 100%; padding: 10px; font-size: 1.1rem;"><br><br>

            <label for="diachi">Địa chỉ:</label><br>
            <input type="text" placeholder="Nhập địa chỉ..." id="diachi" name="diachi" required style="width: 100%; padding: 10px; font-size: 1.1rem;"><br><br>

            <label for="pass">Mật khẩu:</label><br>
            <input type="password" placeholder="Nhập mật khẩu..." id="pass" name="pass" required style="width: 100%; padding: 10px; font-size: 1.1rem;"><br><br>

            <label for="confirmpassword">Nhập lại mật khẩu:</label><br>
            <input type="password" placeholder="Nhập lại mật khẩu..." id="confirmpassword" name="confirmpassword" required style="width: 100%; padding: 10px; font-size: 1.1rem;"><br><br>

            <div class="btndk">
                <button type="submit" name="register" style="background-color: #2e7d32; color: white; border: none; padding: 15px 30px; font-size: 1.2rem; cursor: pointer; border-radius: 5px; margin-top: 20px;">Đăng ký</button>
                <a href="Login.php" class="btn-login" style="background-color: #2e7d32; color: white; border: none; padding: 15px 30px; font-size: 1.2rem; cursor: pointer; border-radius: 5px; margin-top: 20px;">Đăng nhập</a>
            </div>
        </form>
    </div>
</main>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    include 'connect.php';

    $fullname = $_POST['fullname'];
    $ten_khachhang = $_POST['ten_khachhang'];
    $email = $_POST['email'];
    $sodienthoai = $_POST['sodienthoai'];
    $diachi = $_POST['diachi'];
    $password = $_POST['pass'];
    $confirmPassword = $_POST['confirmpassword'];
    $message = "";

    if ($password !== $confirmPassword) {
        $message = "Mật khẩu không khớp. Vui lòng thử lại.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email không hợp lệ.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM khachhang WHERE ma_khachhang = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $fullname);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Tài khoản đã tồn tại. Vui lòng chọn tên tài khoản khác.";
        } else {
            $sql_insert = "INSERT INTO khachhang (ma_khachhang, matkhau, ten_khachhang, email, sodienthoai, diachi) 
                           VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssssss", $fullname, $hashedPassword, $ten_khachhang, $email, $sodienthoai, $diachi);

            if ($stmt_insert->execute()) {
                // Chuyển hướng đến trang đăng nhập khi đăng ký thành công
                header("Location: Login.php");
                exit();
            } else {
                $message = "Đã xảy ra lỗi. Vui lòng thử lại.";
            }
        }

        $stmt->close();
        $conn->close();
    }
    echo "<div style='text-align: center; color: red;'>$message</div>";
}
?>
<?php include 'footer.php'; ?>
