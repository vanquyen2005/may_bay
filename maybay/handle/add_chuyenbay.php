<?php
session_start();
require_once('../functions/db_connection.php');
require_once('../functions/auth.php');

checkLogin('../index.php');
$currentUser = getCurrentUser();

$conn = getDbConnection();

// === Xử lý thêm chuyến bay ===
if (isset($_POST['add'])) {
    $ma = $_POST['ma_chuyenbay'];
    $ten = $_POST['ten_chuyenbay'];
    $di = $_POST['diem_di'];
    $den = $_POST['diem_den'];
    $ngay = $_POST['ngay_di'];
    $gio = $_POST['gio_di'];
    $gia = $_POST['gia_ve'];
    $ghe = $_POST['so_ghe'];
    $trangthai = $_POST['trang_thai'];

    $stmt = $conn->prepare("INSERT INTO chuyenbay (ma_chuyenbay, ten_chuyenbay, diem_di, diem_den, ngay_di, gio_di, gia_ve, so_ghe, trang_thai) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiii", $ma, $ten, $di, $den, $ngay, $gio, $gia, $ghe, $trangthai);
    $stmt->execute();
    $stmt->close();

    header("Location: chuyenbay.php");
    exit();
}

// === Xử lý xóa chuyến bay ===
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM chuyenbay WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: chuyenbay.php");
    exit();
}

// === Xử lý sửa chuyến bay ===
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $ma = $_POST['ma_chuyenbay'];
    $ten = $_POST['ten_chuyenbay'];
    $di = $_POST['diem_di'];
    $den = $_POST['diem_den'];
    $ngay = $_POST['ngay_di'];
    $gio = $_POST['gio_di'];
    $gia = $_POST['gia_ve'];
    $ghe = $_POST['so_ghe'];
    $trangthai = $_POST['trang_thai'];

    $stmt = $conn->prepare("UPDATE chuyenbay 
                            SET ma_chuyenbay=?, ten_chuyenbay=?, diem_di=?, diem_den=?, ngay_di=?, gio_di=?, gia_ve=?, so_ghe=?, trang_thai=? 
                            WHERE id=?");
    $stmt->bind_param("ssssssiiii", $ma, $ten, $di, $den, $ngay, $gio, $gia, $ghe, $trangthai, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: chuyenbay.php");
    exit();
}

// === Lấy danh sách chuyến bay ===
$result = mysqli_query($conn, "SELECT * FROM chuyenbay ORDER BY id DESC");
?>