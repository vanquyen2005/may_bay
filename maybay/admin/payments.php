<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/auth.php';

// Kiểm tra đăng nhập admin
checkLogin();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../giaodien/index.php");
    exit();
}

$conn = getDbConnection();

// ===== THÊM THANH TOÁN =====
if (isset($_POST['add_payment'])) {
    $order_code = $_POST['order_code']; // lấy đúng input trong form
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO payments (order_code, amount, payment_method, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $order_code, $amount, $payment_method, $status);
    $stmt->execute();
}

// ===== CẬP NHẬT TRẠNG THÁI =====
if (isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE payments SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}
// ===== LẤY DANH SÁCH THANH TOÁN =====
$result = mysqli_query($conn, "SELECT * FROM payments ORDER BY created_at DESC");

if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Trang Quản Trị - T-Q Airline</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar {
            width: 240px;
            height: 100vh;
            background: linear-gradient(180deg, #007bff 0%, #0056b3 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
        }
        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>

<body>
<div class="sidebar">
    <h4 class="text-center">🛫 T-Q Airline</h4> 
    <hr>
    <a href="menu.php"><i class="bi bi-speedometer2"></i> Thống kê</a>
    <a href="../giaodien/user.php"><i class="bi bi-display"></i> Giao diện người dùng</a>
    <a href="ve.php"><i class="bi bi-ticket-detailed"></i> Quản lý vé</a>
    <a href="chuyenbay.php"><i class="bi bi-airplane"></i> Quản lý Chuyến bay</a>
    <a href="khachhang.php"><i class="bi bi-people"></i> Khách hàng</a>
    <a href="payments.php" class="fw-bold"><i class="bi bi-cash-coin"></i> Quản lý Thanh Toán</a>
</div>

<div class="content">
    <h3 class="mb-4">Quản lý Thanh Toán</h3>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
        + Thêm thanh toán
    </button>

    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Mã đơn hàng</th>
                <th>Số tiền</th>
                <th>Phương thức</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>

        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['order_code']) ?></td>
                <td><?= number_format($row['amount'], 0, ',', '.') ?>₫</td>
                <td><?= ucfirst($row['payment_method']) ?></td>
                <td>
                    <?php if ($row['status'] == 'pending') : ?>
                        <span class="badge bg-warning text-dark">Pending</span>
                    <?php elseif ($row['status'] == 'completed') : ?>
                        <span class="badge bg-success">Completed</span>
                    <?php else : ?>
                        <span class="badge bg-danger">Failed</span>
                    <?php endif; ?>
                </td>
                <td><?= $row['created_at'] ?></td>

                <td>
                    <form method="POST" class="d-flex gap-2">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">

                        <select name="status" class="form-select form-select-sm">
                            <option value="pending" <?= $row['status']=='pending'?'selected':'' ?>>Pending</option>
                            <option value="completed" <?= $row['status']=='completed'?'selected':'' ?>>Completed</option>
                            <option value="failed" <?= $row['status']=='failed'?'selected':'' ?>>Failed</option>
                        </select>

                        <button type="submit" name="update_status" class="btn btn-sm btn-success">
                            Lưu
                        </button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>


<!-- Modal thêm thanh toán -->
<div class="modal fade" id="addPaymentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Thêm thanh toán mới</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label>Mã đơn hàng</label>
            <input type="text" name="order_code" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Số tiền</label>
            <input type="number" name="amount" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Phương thức</label>
            <select name="payment_method" class="form-select">
              <option value="card">Card</option>
              <option value="momo">Momo</option>
              <option value="bank">Bank</option>
              <option value="vnpay">VNPay</option>
              <option value="zalopay">ZaloPay</option>
            </select>
          </div>

          <div class="mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-select">
              <option value="pending">Pending</option>
              <option value="completed">Completed</option>
              <option value="failed">Failed</option>
            </select>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" name="add_payment" class="btn btn-primary">Lưu</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
