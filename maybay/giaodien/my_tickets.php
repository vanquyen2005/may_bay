<?php
session_start();
require_once "../functions/db_connection.php";
require_once "../functions/auth.php";

checkLogin("../index.php");
$user = getCurrentUser();

$conn = getDbConnection();

$sql = "SELECT t.id, f.code, f.from_airport, f.to_airport, f.date, 
               f.time_start, f.time_end, f.price
        FROM vecuatoi t
        JOIN flights f ON t.flight_id = f.id
        WHERE t.user_id = " . $user['id'];

$tickets = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vé của tôi</title>
    <link rel="stylesheet" href="/maybay/css/user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <h2 class="fw-bold">Vé của tôi</h2>
    <p class="text-muted">Danh sách vé bạn đã đặt</p>

    <table class="table table-hover bg-white shadow-sm mt-3">
        <thead class="table-dark">
            <tr>
                <th>Mã chuyến</th>
                <th>Đi</th>
                <th>Đến</th>
                <th>Ngày</th>
                <th>Giờ bay</th>
                <th>Giá</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($t = mysqli_fetch_assoc($tickets)) { ?>
                <tr>
                    <td><?= $t['code'] ?></td>
                    <td><?= $t['from_airport'] ?></td>
                    <td><?= $t['to_airport'] ?></td>
                    <td><?= $t['date'] ?></td>
                    <td><?= $t['time_start'] ?> → <?= $t['time_end'] ?></td>
                    <td class="text-danger fw-bold"><?= number_format($t['price']) ?> đ</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
