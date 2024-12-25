<?php include 'header.php';?>
    <main>
        <section class="about-section">
            <div class="about-content">
                <h2 style="font-size: 60px;">About Us</h2>
                <p style="font-size: 20px;">- Chào mừng đến với JzangSneaker, nguồn cung cấp mọi thứ về giày thể thao đến với mọi người. Chúng tôi tận tâm cung cấp cho bạn những đôi giày tốt nhất, chú trọng vào chất lượng, kiểu dáng và sự thoải mái.</p>
                <p style="font-size: 20px;">- Được thành lập vào đầu năm 2024 bởi những người đam mê sneaker, đến nay JzangSneaker đã trải qua 1 quãng thời gian đáng kể từ những ngày đầu thành lập. Chúng tôi hy vọng bạn thích sản phẩm của chúng tôi nhiều như chúng tôi thích cung cấp chúng cho bạn. Nếu bạn có bất kỳ câu hỏi hoặc ý kiến, xin vui lòng liên hệ với chúng tôi ở phần Contact.</p>
                <p style="font-size: 20px;">- Tuy chỉ mới được thành lập và còn thiếu sót nhiều kinh nghiệm, chúng tôi vẫn sẽ đảm bảo đem đến cho quý khách hàng những trải nghiệm mua sắm thoải mái, an tâm khi những sản phẩm luôn có chính sách bảo hành và ưu đãi.</p>
                <p style="font-size: 20px;">- Trân trọng,<br>Đội ngũ JzangSneaker luôn sẵn sàng phục vụ quý khách.</p>
            </div>
            <div class="about-image">
                <div class="top-images" >
                    <div>
                        <td><img src="/Demo/img/Banner/Nike.png" class="top-images" width="500" height="500"></td>
                        <td><img src="/Demo/img/Banner/city65.png" class="top-images"width="400" height="500"></td>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
   
    <script>
        const footer = document.getElementById('footer');// Lấy thẻ footer bằng id
        const contactLink = document.getElementById('contact-link');
        contactLink.addEventListener('click', function(event) {
            event.preventDefault();
            footer.scrollIntoView({ behavior: 'smooth' }); // Cuộn xuống phần footer || 'smooth' để có hiệu ứng cuộn mượt
        });
    </script>
</body>
</html>
