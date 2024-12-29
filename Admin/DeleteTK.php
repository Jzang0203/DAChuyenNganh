<?php
include '../connect.php';

if (isset($_GET['ma_khachhang'])) {
    $ma_khachhang = $_GET['ma_khachhang'];

    $sql = "DELETE FROM khachhang WHERE ma_khachhang = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $ma_khachhang);
        if ($stmt->execute()) {
            header("Location: QLTK.php");
            exit();
        } else {
            echo "Có lỗi khi xóa tài khoản!";
        }
    } else {
        echo "Không thể chuẩn bị câu lệnh SQL!";
    }
} else {
    header("Location: QLTK.php");
    exit();
}

$conn->close();
?>
