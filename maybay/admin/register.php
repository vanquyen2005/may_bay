<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white shadow-lg rounded-2xl w-full max-w-md p-8">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Đăng ký tài khoản</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="../handle/register_process.php">
            <div class="mb-4">
                <label class="block mb-2 text-gray-700">Tên đăng nhập</label>
                <input type="text" name="username" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-gray-700">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-gray-700">Mật khẩu</label>
                <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-6">
                <label class="block mb-2 text-gray-700">Nhập lại mật khẩu</label>
                <input type="password" name="confirm" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Đăng ký
            </button>
        </form>

        <p class="text-center mt-6 text-gray-600">
            Đã có tài khoản? 
            <a href="login.php" class="text-blue-600 hover:underline">Đăng nhập</a>
        </p>
    </div>
</body>
</html>
