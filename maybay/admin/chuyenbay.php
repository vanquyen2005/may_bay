<?php
session_start();
require_once('../functions/db_connection.php');
require_once('../functions/auth.php');

checkLogin('../index.php');
$currentUser = getCurrentUser();

$conn = getDbConnection();

// Xử lý thêm chuyến bay
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

    $sql = "INSERT INTO chuyenbay (ma_chuyenbay, ten_chuyenbay, diem_di, diem_den, ngay_di, gio_di, gia_ve, so_ghe, trang_thai)
            VALUES ('$ma', '$ten', '$di', '$den', '$ngay', '$gio', '$gia', '$ghe', '$trangthai')";
}

// Xử lý xóa chuyến bay
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM chuyenbay WHERE id = $id");
}

// Lấy danh sách chuyến bay
$conn = getDbConnection();
$result = mysqli_query($conn, "SELECT * FROM chuyenbay ORDER BY id DESC");
$currentUser = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý chuyến bay - T-Q Airline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 240px; height: 100vh; background: linear-gradient(180deg, #007bff 0%, #0056b3 100%); color: white; position: fixed; top:0; left:0; padding-top:20px; }
        .sidebar h4 { text-align:center; font-weight:bold; }
        .sidebar a { display:block; color:white; text-decoration:none; padding:12px 20px; transition:0.3s; }
        .sidebar a:hover { background: rgba(255,255,255,0.2); padding-left:30px; }
        .content { margin-left:250px; padding:20px; }
        .table th { background-color: #007bff; color:white; }
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
    <a href="chuyenbay.php" class="fw-bold"><i class="bi bi-airplane"></i> Quản lý chuyến bay</a>
    <a href="khachhang.php"><i class="bi bi-people"></i> Quản lý khách hàng</a>
    <a href="payments.php"><i class="bi bi-people"></i> Quản lý Thanh Toán</a>
    <a href="../handle/logout_process.php" class="logout-btn">
        <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a></div>
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>✈️ Quản lý chuyến bay</h3>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addFlightModal">
            <i class="bi bi-plus-circle"></i> Thêm chuyến bay
        </button>
    </div>

    <div class="card p-3 shadow-sm">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mã chuyến bay</th>
                    <th>Tên chuyến bay</th>
                    <th>Điểm đi</th>
                    <th>Điểm đến</th>
                    <th>Ngày đi</th>
                    <th>Giờ đi</th>
                    <th>Giá vé</th>
                    <th>Số ghế</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['ma_chuyenbay']) ?></td>
                        <td><?= htmlspecialchars($row['ten_chuyenbay']) ?></td>
                        <td><?= htmlspecialchars($row['diem_di']) ?></td>
                        <td><?= htmlspecialchars($row['diem_den']) ?></td>
                        <td><?= $row['ngay_di'] ?></td>
                        <td><?= $row['gio_di'] ?></td>
                        <td><?= number_format($row['gia_ve'],0,',','.') ?>₫</td>
                        <td><?= $row['so_ghe'] ?></td>
                        <td><?= htmlspecialchars($row['trang_thai']) ?></td>
                        <td>
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Bạn có chắc muốn xóa chuyến bay này không?')">
                               <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal thêm chuyến bay -->
<div class="modal fade" id="addFlightModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm chuyến bay mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Mã chuyến bay</label>
                        <input type="text" name="ma_chuyenbay" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Tên chuyến bay</label>
                        <input type="text" name="ten_chuyenbay" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Điểm đi</label>
                        <input type="text" name="diem_di" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Điểm đến</label>
                        <input type="text" name="diem_den" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Ngày đi</label>
                        <input type="date" name="ngay_di" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Giờ đi</label>
                        <input type="time" name="gio_di" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Giá vé</label>
                        <input type="number" name="gia_ve" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Số ghế</label>
                        <input type="number" name="so_ghe" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Trạng thái</label>
                        <input type="text" name="trang_thai" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button a href="add_chuyenbay.php" type="submit" name="add" class="btn btn-success">Lưu</button>
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
