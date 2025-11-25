<?php
require_once('../functions/db_connection.php');
$conn = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_ve = trim($_POST['ma_ve']);
    $ten_khach = trim($_POST['ten_khach']);
    $ma_chuyenbay = trim($_POST['ma_chuyenbay']);
    $gia_ve = floatval($_POST['gia_ve']);
    $ngay_dat = $_POST['ngay_dat'];

    if (empty($ma_ve) || empty($ten_khach) || empty($ma_chuyenbay) || empty($gia_ve) || empty($ngay_dat)) {
        die("❌ Vui lòng nhập đầy đủ thông tin vé!");
    }

    $sql = "INSERT INTO ve (ma_ve, ten_khach, ma_chuyenbay, gia_ve, ngay_dat)
            VALUES ('$ma_ve', '$ten_khach', '$ma_chuyenbay', '$gia_ve', '$ngay_dat')";

    if (mysqli_query($conn, $sql)) {
        header('Location: ../admin/ve.php');
        exit;
    } else {
        echo "❌ Lỗi khi thêm vé: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
