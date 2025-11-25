<?php
session_start();
// Kiểm tra session và vai trò (role) của người dùng
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
// Lưu ý: Biến $currentUser['username'] cần được thiết lập trước đó
// Ví dụ: $currentUser = ['username' => $_SESSION['username']];
// Tôi tạm thời giữ nguyên dòng này, bạn hãy đảm bảo biến này có giá trị.
$currentUser = ['username' => $_SESSION['username'] ?? 'Admin']; 
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
        <a href=""><i class="bi bi-speedometer2"></i> Thống kê</a>
        <a href="../giaodien/user.php"><i class="bi bi-display"></i> Giao Diện người dùng</a>
        <a href="ve.php"><i class="bi bi-ticket-detailed"></i> Quản lý vé</a>
        <a href="chuyenbay.php"><i class="bi bi-airplane"></i> Quản lý Chuyến bay</a>
        <a href="khachhang.php"><i class="bi bi-people"></i> Quản lý Khách hàng</a>
        <a href="payments.php"><i class="bi bi-book"></i> Quản lý Thanh Toán</a>
       <a href="../handle/logout_process.php" class="logout-btn">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </div>

    <div class="content">
        <nav class="navbar navbar-light bg-white shadow-sm rounded mb-4 px-3">
            <h5 class="navbar-brand mb-0">Bảng điều khiển T-Q Airline</h5> 
            <span>Xin chào, <b><?= htmlspecialchars($currentUser['username']) ?></b></span>
        </nav>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card card-stats p-3">
                    <h6>Doanh thu hôm nay</h6>
                    <h3 class="text-success">125,000,000₫</h3>
                    <p><i class="bi bi-arrow-up text-success"></i> +12% so với hôm qua</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stats p-3">
                    <h6>Vé đã bán</h6>
                    <h3 class="text-primary">235</h3>
                    <p><i class="bi bi-airplane"></i> Tổng số vé đã giao dịch</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stats p-3">
                    <h6>Chuyến bay đang hoạt động</h6>
                    <h3 class="text-warning">18</h3>
                    <p><i class="bi bi-clock-history"></i> Đang cất cánh hoặc hạ cánh</p>
                </div>
            </div>
        </div>

        <div class="card p-3 shadow-sm">
            <h5>Danh sách vé máy bay gần đây</h5>
            <table class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th>Mã vé</th>
                        <th>Tên khách hàng</th>
                        <th>Chuyến bay</th>
                        <th>Giá vé</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>VN1234</td>
                        <td>Nguyễn Văn A</td>
                        <td>Hà Nội → TP.HCM</td>
                        <td>1,200,000₫</td>
                        <td>04/11/2025</td>
                        <td><span class="badge bg-success">Đã thanh toán</span></td>
                    </tr>
                    <tr>
                        <td>VJ5678</td>
                        <td>Trần Thị B</td>
                        <td>Đà Nẵng → Hà Nội</td>
                        <td>950,000₫</td>
                        <td>03/11/2025</td>
                        <td><span class="badge bg-warning">Chờ thanh toán</span></td>
                    </tr>
                    <tr>
                        <td>BL9012</td>
                        <td>Phạm Văn C</td>
                        <td>TP.HCM → Phú Quốc</td>
                        <td>1,450,000₫</td>
                        <td>03/11/2025</td>
                        <td><span class="badge bg-success">Đã thanh toán</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>