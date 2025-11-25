<?php
session_start();
require_once('../functions/db_connection.php');
require_once('../functions/auth.php');

checkLogin('../index.php');
$currentUser = getCurrentUser();

$conn = getDbConnection();
$result = mysqli_query($conn, "SELECT * FROM ve ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="vi">
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
        .sidebar h4 {
            text-align: center;
            font-weight: bold;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.2);
            padding-left: 30px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .card-stats {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .logout-btn {
            display: block;
            background-color: #dc3545;
            color: white;
            text-align: center;
            padding: 12px 20px;
            margin: 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>🛫 T-Q Airline</h4> 
        <hr>
        <a href="menu.php"><i class="bi bi-speedometer2"></i> Thống kê</a>
        <a href="../giaodien/user.php"><i class="bi bi-display"></i> Giao Diện người dùng</a>
        <a href="ve.php"><i class="bi bi-ticket-detailed"></i> Quản lý vé</a>
        <a href="chuyenbay.php"><i class="bi bi-airplane"></i> Quản lý Chuyến bay</a>
        <a href="khachhang.php"><i class="bi bi-people"></i>Quản lý Khách hàng</a>
        <a href="payments.php"><i class="bi bi-people"></i> Quản lý Thanh Toán</a>
        <a href="../handle/logout_process.php" class="logout-btn">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </div>

    <div class="content">
        <nav class="navbar navbar-light bg-white shadow-sm rounded mb-4 px-3">
            <h5 class="navbar-brand mb-0">Bảng điều khiển T-Q Airline</h5> 
            <span>Xin chào, <b><?= htmlspecialchars($currentUser['username']) ?></b></span>
        </nav>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>📋 Danh sách vé</h3>
    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addTicketModal">+ Thêm vé mới</button>
  </div>

  <!-- Bảng dữ liệu vé -->
  <table class="table table-bordered table-striped shadow-sm">
    <thead class="table-primary">
      <tr>
        <th>ID</th>
        <th>Mã vé</th>
        <th>Khách hàng</th>
        <th>Chuyến bay</th>
        <th>Giá vé</th>
        <th>Ngày đặt</th>
        <th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['ma_ve']) ?></td>
            <td><?= htmlspecialchars($row['ten_khach']) ?></td>
            <td><?= htmlspecialchars($row['ma_chuyenbay']) ?></td>
            <td><?= number_format($row['gia_ve'], 0, ',', '.') ?>₫</td>
            <td><?= $row['ngay_dat'] ?></td>
            <td>
              <a href="../handle/delete_ve.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                 onclick="return confirm('Bạn có chắc muốn xóa vé này không?');">Xóa</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7" class="text-center text-muted">Chưa có vé nào trong hệ thống.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal thêm vé -->
<div class="modal fade" id="addTicketModal" tabindex="-1" aria-labelledby="addTicketModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../handle/add_ve.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Thêm vé mới</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Mã vé</label>
            <input type="text" name="ma_ve" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tên khách hàng</label>
            <input type="text" name="ten_khach" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mã chuyến bay</label>
            <input type="text" name="ma_chuyenbay" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Giá vé</label>
            <input type="number" name="gia_ve" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Ngày đặt</label>
            <input type="date" name="ngay_dat" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button a href = "add_ve.php" type="submit" class="btn btn-primary">Lưu vé</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
