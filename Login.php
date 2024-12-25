<?php include 'header.php'; ?>
<link rel="stylesheet" href="/Demo/css/FormLogin.css">
<body>
    <main class="login-container">
        <div class="login-form">
            <h1>Thông tin đăng nhập</h1>
            <form method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="ma_taikhoan">Tài khoản:</label>
                    <input type="text" placeholder="Nhập mã tài khoản..." id="ma_taikhoan" name="ma_taikhoan" required>
                </div>

                <div class="form-group">
                    <label for="matkhau">Mật khẩu:</label>
                    <input type="password" placeholder="Nhập mật khẩu..." id="matkhau" name="matkhau" required>
                </div>

                <div class="button-group">
                    <button type="submit" name="login" class="btn-login">Đăng nhập</button>
                    <a href="/SignUp.php" class="btn-register">
                        <button type="button">Đăng ký</button>
                    </a>
                </div>
            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>

    <?php
    if (isset($_POST['login'])) {
        include 'connect.php'; // Kết nối với cơ sở dữ liệu

        $ma_taikhoan = $_POST['ma_taikhoan'];
        $password = $_POST['matkhau'];
        $found = false; // Biến đánh dấu tìm thấy tài khoản

        // Kiểm tra đăng nhập với khách hàng
        $sql_khachhang = "SELECT * FROM khachhang WHERE ma_khachhang = ?";
        $stmt_khachhang = $conn->prepare($sql_khachhang);
        $stmt_khachhang->bind_param("s", $ma_taikhoan);
        $stmt_khachhang->execute();
        $result_khachhang = $stmt_khachhang->get_result();

        if ($result_khachhang->num_rows > 0) {
            $found = true; // Đánh dấu đã tìm thấy tài khoản
            $user = $result_khachhang->fetch_assoc();
            if (password_verify($password, $user['matkhau'])) {
                session_start();
                $_SESSION['ma_khachhang'] = $user['ma_khachhang'];
                $_SESSION['ten_khachhang'] = $user['ten_khachhang'];
                echo "<script>alert('Đăng nhập thành công!'); window.location.href = '/index.php';</script>";
                exit();
            } else {
                echo "<script>alert('Mật khẩu không đúng. Vui lòng thử lại!');</script>";
            }
        }

        // Kiểm tra đăng nhập với quản trị viên
        if (!$found) { // Chỉ kiểm tra nếu chưa tìm thấy tài khoản
            $sql_quantrivien = "SELECT * FROM quantrivien WHERE ma_quantrivien = ?";
            $stmt_quantrivien = $conn->prepare($sql_quantrivien);
            $stmt_quantrivien->bind_param("s", $ma_taikhoan);
            $stmt_quantrivien->execute();
            $result_quantrivien = $stmt_quantrivien->get_result();

            if ($result_quantrivien->num_rows > 0) {
                $found = true; // Đánh dấu đã tìm thấy tài khoản
                $admin = $result_quantrivien->fetch_assoc();
                if (password_verify($password, $admin['matkhau'])) {
                    session_start();
                    $_SESSION['ma_quantrivien'] = $admin['ma_quantrivien'];
                    $_SESSION['ho_ten'] = $admin['ho_ten'];
                    echo "<script>alert('Đăng nhập thành công!'); window.location.href = 'Admin/admin.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Mật khẩu không đúng. Vui lòng thử lại!');</script>";
                }
            }
        }

        if (!$found) {
            $sql_nhanvien = "SELECT * FROM nhanvien WHERE ma_nhanvien = ?";
            $stmt_nhanvien = $conn->prepare($sql_nhanvien);
            $stmt_nhanvien->bind_param("s", $ma_taikhoan);
            $stmt_nhanvien->execute();
            $result_nhanvien = $stmt_nhanvien->get_result();

            if ($result_nhanvien->num_rows > 0) {
                $found = true;
                $staff = $result_nhanvien->fetch_assoc();
                if (password_verify($password, $staff['matkhau'])) {
                    session_start();
                    $_SESSION['ma_nhanvien'] = $staff['ma_nhanvien'];
                    $_SESSION['ten_nhanvien'] = $staff['ten_nhanvien'];
                    echo "<script>alert('Đăng nhập thành công!'); window.location.href = 'Nhanvien.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Mật khẩu không đúng. Vui lòng thử lại!');</script>";
                }
            }
        }

        if (!$found) {
            echo "<script>alert('Tài khoản không tồn tại. Vui lòng thử lại!');</script>";
        }
        $stmt_khachhang->close();
        if (isset($stmt_quantrivien)) $stmt_quantrivien->close();
        $conn->close();
    }
    ?>
</body>
</html>
