<?php
include "header.php";
include "connect.php";

$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

$resultList = [];
if (!empty($searchQuery)) {
    $stmt = $conn->prepare("SELECT * FROM sanpham WHERE ten_sanpham LIKE ? OR mota LIKE ?");
    $likeQuery = "%" . $searchQuery . "%";
    $stmt->bind_param("ss", $likeQuery, $likeQuery);

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $resultList[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<body>
    <main>
        <?php if (!empty($resultList)): ?>
            <ul>
                <?php foreach ($resultList as $product): ?>
                    <li>
                        <h2><?php echo htmlspecialchars($product['ten_sanpham']); ?></h2>
                        <p><?php echo htmlspecialchars($product['mota']); ?></p>
                        <p>Giá: <?php echo number_format($product['gia'], 0, ',', '.'); ?> VNĐ</p>
                        <a href="/CTSP.php?id=<?php echo $product['ma_sanpham']; ?>" style="color:black"> Chi tiết</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Không tìm thấy sản phẩm phù hợp.</p>
        <?php endif; ?>
    </main>
</body>
</html>
