<?php
if (!isset($page_title)) $page_title = "Trang chủ";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS của bạn -->
    <link rel="stylesheet" href="/maybay/css/style.css">
</head>

<body class="bg-gray-50 text-gray-900">

<!-- NAVBAR -->
<nav class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="/maybay/giaodien/user.php" class="text-2xl font-bold text-blue-600">
            ✈ Vemaybay
        </a>

        <div class="flex gap-6 items-center">
            <a href="/maybay/giaodien/user.php" class="hover:text-blue-600">Trang chủ</a>
            <a href="/maybay/giaodien/search.php" class="hover:text-blue-600">Tìm vé</a>
            <a href="/maybay/giaodien/booking.php" class="hover:text-blue-600">Vé Của Tôi</a>
            <a href="/maybay/giaodien/profile.php" class="hover:text-blue-600">Thông Tin Cá Nhân</a>
   

            <?php if (isset($_SESSION['username'])): ?>
                <span class="text-gray-700">Xin chào, <?php echo $_SESSION['username']; ?></span>
                <a href="/maybay/handle/logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Đăng xuất
                </a>
            <?php else: ?>
                <a href="/maybay/index.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Đăng nhập
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

