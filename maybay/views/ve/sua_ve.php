<?php
session_start();
require_once('../functions/db_connection.php');
if (!isset($_SESSION['username'])) { header("Location: index.php"); exit(); }

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM ve WHERE id=$id");
$ve = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $ma_ve = $_POST['ma_ve'];
    $ten_khach_hang = $_POST['ten_khach_hang'];
    $ma_chuyen_bay = $_POST['ma_chuyen_bay'];
    $ngay_bay = $_POST['ngay_bay'];
    $gia_ve = $_POST['gia_ve'];
    $ghi_chu = $_POST['ghi_chu'];

    $sql = "UPDATE ve SET ma_ve='$ma_ve', ten_khach_hang='$ten_khach_hang', ma_chuyen_bay='$ma_chuyen_bay',
            ngay_bay='$ngay_bay', gia_ve='$gia_ve', ghi_chu='$ghi_chu' WHERE id=$id";
    mysqli_query($conn, $sql);
    header("Location: ve.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Sửa vé</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3 class="text-primary">✏️ Sửa thông tin vé</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Mã vé</label>
            <input type="text" name="ma_ve" value="<?= $ve['ma_ve'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tên khách hàng</label>
            <input type="text" name="ten_khach_hang" value="<?= $ve['ten_khach_hang'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Mã chuyến bay</label>
            <input type="text" name="ma_chuyen_bay" value="<?= $ve['ma_chuyen_bay'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Ngày bay</label>
            <input type="date" name="ngay_bay" value="<?= $ve['ngay_bay'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Giá vé</label>
            <input type="number" name="gia_ve" value="<?= $ve['gia_ve'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Ghi chú</label>
            <input type="text" name="ghi_chu" value="<?= $ve['ghi_chu'] ?>" class="form-control">
        </div>
        <button type="submit" name="update" class="btn btn-success">💾 Lưu thay đổi</button>
        <a href="ve.php" class="btn btn-secondary">⬅️ Quay lại</a>
    </form>
</div>
</body>
</html>
