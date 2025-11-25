<?php
session_start();
require_once '../functions/db_connection.php';
$conn = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $order_code = $_POST['order_code'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $status = $_POST['status'];

    $sql = "INSERT INTO payments (user_id, order_code, amount, payment_method, status) 
            VALUES ('$user_id', '$order_code', '$amount', '$payment_method', '$status')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Thêm thanh toán thành công!";
    } else {
        $_SESSION['error'] = "Lỗi thêm thanh toán: " . mysqli_error($conn);
    }
}
header("Location: ../admin/payments.php");
exit;
?>
