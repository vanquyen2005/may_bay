<?php
session_start();
require_once '../functions/db_connection.php';  // chứa biến $db (PDO)
require_once '../functions/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (empty($username) || empty($password) || empty($confirm)) {
        $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin.";
        header("Location: ../views/register.php");
        exit;
    }

    if ($password !== $confirm) {
        $_SESSION['error'] = "Mật khẩu nhập lại không khớp.";
        header("Location: ../views/register.php");
        exit;
    }

    // Kiểm tra trùng username
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->fetch()) {
        $_SESSION['error'] = "Tên đăng nhập đã tồn tại!";
        header("Location: ../views/register.php");
        exit;
    }

    // Mã hóa mật khẩu
    $hashed = password_hash($password, PASSWORD_BCRYPT);

    // Lưu vào DB
    $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->execute([$username, $email, $hashed]);

    $_SESSION['success'] = "Đăng ký thành công! Mời bạn đăng nhập.";
    header("Location: ../views/login.php");
    exit;
} else {
    header("Location: ../views/register.php");
    exit;
}
