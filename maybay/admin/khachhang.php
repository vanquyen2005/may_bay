<?php
session_start();
require_once('../functions/db_connection.php');
require_once('../functions/auth.php');

checkLogin('../index.php');
$currentUser = getCurrentUser();

$conn = getDbConnection();

// Xử lý thêm khách hàng
if (isset($_POST['add'])) {
    $ten = $_POST['ten'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $diachi = $_POST['diachi'];
    $cccd = $_POST['cccd'];
    $datve = 0; // mặc định ban đầu chưa đặt vé
    $chitieu = 0;

    $sql = "INSERT INTO khachhang (ten, email, sdt, diachi, cccd, dat_ve, chi_tieu) 
            VALUES ('$ten', '$email', '$sdt', '$diachi', '$cccd', '$datve', '$chitieu')";
    mysqli_query($conn, $sql);
}

// Xử lý xóa khách hàng
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM khachhang WHERE id = $id");
}

// Lấy danh sách khách hàng
$result = mysqli_query($conn, "SELECT * FROM khachhang ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý khách hàng - T-Q Airline</title>
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
        .table th {
            background-color: #007bff;
            color: white;
        }
        .btn-sm {
            padding: 4px 8px;
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
    <!-- Sidebar -->
    <div class="sidebar">
        <h4>🛫 T-Q Airline</h4>
        <hr>
        <a href="menu.php"><i class="bi bi-speedometer2"></i> Thống kê</a>
        <a href="../giaodien/user.php"><i class="bi bi-display"></i> Giao Diện người dùng</a>
        <a href="ve.php"><i class="bi bi-ticket-detailed"></i> Quản lý vé</a>
        <a href="chuyenbay.php"><i class="bi bi-airplane"></i> Quản lý chuyến bay</a>
        <a href="khachhang.php" class="fw-bold"><i class="bi bi-people"></i> Quản lý khách hàng</a>
        <a href="payments.php"><i class="bi bi-people"></i> Quản lý Thanh Toán</a>
        <a href="../handle/logout_process.php" class="logout-btn">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </div>

    <!-- Nội dung chính -->
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>👥 Quản lý khách hàng</h3>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                <i class="bi bi-plus-circle"></i> Thêm khách hàng
            </button>
        </div>

        <!-- Bảng danh sách khách hàng -->
        <div class="card p-3 shadow-sm">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>CMND/CCCD</th>
                        <th>Đặt vé</th>
                        <th>Chi tiêu (₫)</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['ten']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['sdt']) ?></td>
                            <td><?= htmlspecialchars($row['diachi']) ?></td>
                            <td><?= htmlspecialchars($row['cccd']) ?></td>
                            <td><?= $row['dat_ve'] ?></td>
                            <td><?= number_format($row['chi_tieu'], 0, ',', '.') ?></td>
                            <td>
                                <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Bạn có chắc muốn xóa khách hàng này không?')">
                                   <i class="bi bi-trash"></i>
                                </a>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                   <i class="bi bi-pencil-square"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal sửa -->
                        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="update_khachhang.php">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Sửa thông tin khách hàng</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <div class="mb-3">
                                                <label>Họ tên</label>
                                                <input type="text" name="ten" value="<?= htmlspecialchars($row['ten']) ?>" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Email</label>
                                                <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Điện thoại</label>
                                                <input type="text" name="sdt" value="<?= htmlspecialchars($row['sdt']) ?>" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label>Địa chỉ</label>
                                                <input type="text" name="diachi" value="<?= htmlspecialchars($row['diachi']) ?>" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label>CMND/CCCD</label>
                                                <input type="text" name="cccd" value="<?= htmlspecialchars($row['cccd']) ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="update" class="btn btn-primary">Cập nhật</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal thêm khách hàng -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm khách hàng mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Họ và tên</label>
                            <input type="text" name="ten" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Điện thoại</label>
                            <input type="text" name="sdt" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Địa chỉ</label>
                            <input type="text" name="diachi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>CMND/CCCD</label>
                            <input type="text" name="cccd" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Đặt vé</label>
                            <input type="text" name="cccd" class="form-control" required>
                        </div>
                       <div class="mb-3">
                            <label>Chi tiêu</label>
                            <input type="text" name="cccd" class="form-control" required>
                        </div>        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add" class="btn btn-primary">Lưu</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
