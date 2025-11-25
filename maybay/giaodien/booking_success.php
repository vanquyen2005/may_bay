<?php
session_start();
require_once('../functions/db_connection.php');

// Kiểm tra session booking
if (!isset($_SESSION['booking_success'])) {
    $_SESSION['flash_error'] = 'Không có booking nào vừa thực hiện';
    header('Location: ../giaodien/user.php');
    exit;
}

$booking = $_SESSION['booking_success'];
unset($_SESSION['booking_success']); // Xóa session sau khi lấy dữ liệu

$conn = getDbConnection();
$flight = $booking['flight'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt vé thành công - T-Q Airline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <div class="alert alert-success text-center">
        <h3>✅ Đặt vé thành công!</h3>
        <p>Mã đơn hàng của bạn: <strong><?= htmlspecialchars($booking['order_code']) ?></strong></p>
        <p>Hành khách: <strong><?= htmlspecialchars($booking['full_name']) ?></strong></p>
    </div>

    <div class="row">
        <!-- Chi tiết chuyến bay -->
        <div class="col-md-6">
            <div class="border p-3 rounded shadow-sm bg-light">
                <h4>Chi tiết chuyến bay</h4>
                <p><strong>Mã chuyến bay:</strong> <?= htmlspecialchars($flight['ma_chuyenbay']) ?></p>
                <p><strong>Tên chuyến bay:</strong> <?= htmlspecialchars($flight['ten_chuyenbay']) ?></p>
                <p><strong>Điểm đi:</strong> <?= htmlspecialchars($flight['diem_di']) ?></p>
                <p><strong>Điểm đến:</strong> <?= htmlspecialchars($flight['diem_den']) ?></p>
                <p><strong>Ngày đi:</strong> <?= $flight['ngay_di'] ?></p>
                <p><strong>Giờ đi:</strong> <?= $flight['gio_di'] ?></p>
            </div>
        </div>

        <!-- Chi tiết thanh toán -->
        <div class="col-md-6">
            <div class="border p-3 rounded shadow-sm bg-light">
                <h4>Chi tiết thanh toán</h4>
                <p><strong>Số tiền:</strong> <?= number_format($booking['amount'],0,',','.') ?>₫</p>
                <p><strong>Phương thức:</strong> <?= htmlspecialchars($booking['payment_method']) ?></p>
                <p><strong>Trạng thái:</strong> <span class="text-warning">Đang chờ</span></p>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="../giaodien/user.php" class="btn btn-primary">Quay về trang danh sách chuyến bay</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
