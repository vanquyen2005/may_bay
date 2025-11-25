    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                            <i class="fas fa-plane text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-bold">SkyBooking</h3>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Đặt vé máy bay nhanh chóng, dễ dàng với giá tốt nhất thị trường.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Về chúng tôi</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Giới thiệu</a></li>
                        <li><a href="#" class="hover:text-white transition">Tin tức</a></li>
                        <li><a href="#" class="hover:text-white transition">Tuyển dụng</a></li>
                        <li><a href="#" class="hover:text-white transition">Liên hệ</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Hỗ trợ</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Câu hỏi thường gặp</a></li>
                        <li><a href="#" class="hover:text-white transition">Điều khoản sử dụng</a></li>
                        <li><a href="#" class="hover:text-white transition">Chính sách bảo mật</a></li>
                        <li><a href="#" class="hover:text-white transition">Hướng dẫn thanh toán</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Liên hệ</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>
                            <i class="fas fa-phone mr-2"></i>
                            Hotline: <span class="text-white font-semibold">0948073137</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope mr-2"></i>
                            Email: vovanquyen@skybooking.vn
                        </li>
                    </ul>
                    <div class="mt-4 flex gap-3">
                        <a href="https://www.facebook.com/van.quyen.566790/" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition">
                            <i class="fab fa-facebook-f text-sm" ></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; 2025 SkyBooking. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Auto hide flash messages after 5 seconds
        setTimeout(() => {
            const flashMessage = document.getElementById('flashMessage');
            if (flashMessage) {
                flashMessage.style.transition = 'opacity 0.5s';
                flashMessage.style.opacity = '0';
                setTimeout(() => flashMessage.remove(), 500);
            }
        }, 5000);
        
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
    
    <?php if (isset($extra_js)) echo $extra_js; ?>
</body>
</html>

