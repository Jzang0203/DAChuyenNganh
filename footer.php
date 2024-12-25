<footer class="footer" id="footer">
            <div class="left">
                <div class="info">
                    <h5>Thông tin liên hệ || JzangSneaker</h5>
                    <ul class="list-menu">
                        <li><i class="fs"></i><span>Địa chỉ: 322 đường Đào Sư Tích</span></li>
                        <li><i class="fs"></i><span>Điện thoại: </span><a href="tel:0388073446">0388073446</a></li>
                        <li><i class="fs"></i><span>Điện thoại: </span><a href="tel:0373604728">0373604728</a></li>
                        <li><i class="fs"></i><span>Email: </span><a href="mailto:dh52105342@student.stu.edu.vn">dh52105342@student.stu.edu.vn</a></li>
                        <li><i class="fs"></i><span>Email: </span><a href="mailto:dh52105057@student.stu.edu.vn">dh52105057@student.stu.edu.vn</a></li>
                    </ul>
                </div>
            
                <div class="right">
                    <h5>Nhận Mã Khuyến Mãi</h5>
                    <div class="ig">
                        <input type="text" style="width: 40%;"placeholder="Nhập email của bạn">
                        <span class="igt">Đăng ký</span>
                    </div>
                </div>
            </div>
            <hr>
            <div class="roww">
                <div>
                    <div>@JzangSneaker | Designed by Quan & Trinh</div>
                </div>
            </div>
    </footer>
   
    <script>
    //Contact
        const footer = document.getElementById('footer');
        const contactLink = document.getElementById('contact-link');
        
        contactLink.addEventListener('click', function(event) {//Click vào liên kết 'Contact'
        event.preventDefault(); // Ngăn chặn hành vi mặc định của liên kết
        // Cuộn xuống phần footer
        footer.scrollIntoView({ behavior: 'smooth' }); // 'smooth' để có hiệu ứng cuộn mượt
        });
        function addToCart(productId, productName, price) {
        event.preventDefault();
        window.location.href = `add_to_cart.php?id=${encodeURIComponent(productId)}&name=${encodeURIComponent(productName)}&price=${encodeURIComponent(price)}`;
}

    </script>