<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/auth.php';

$page_title = 'Đặt vé';
$current_page = 'booking';

$conn = getDbConnection();

// Lấy ID chuyến bay từ URL
$flight_id = $_GET['flight_id'] ?? 0;
$passengers = $_GET['passengers'] ?? 1;

if (!$flight_id) {
    $_SESSION['booking_errors'][] = 'Vui lòng chọn chuyến bay';
    header("Location: /"); exit;
}

// Lấy thông tin chuyến bay từ bảng chuyenbay
$stmt = mysqli_query($conn, "SELECT * FROM chuyenbay WHERE id = $flight_id");
$flight = mysqli_fetch_assoc($stmt);

if (!$flight) {
    $_SESSION['booking_errors'][] = 'Không tìm thấy chuyến bay';
    header("Location: /"); exit;
}

// Xử lý form đặt vé
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $id_card = trim($_POST['id_card'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $payment_method = $_POST['payment_method'] ?? 'card';

    if (empty($full_name)) $errors[] = 'Vui lòng nhập họ tên';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ';
    if (empty($phone)) $errors[] = 'Vui lòng nhập số điện thoại';

    if (empty($errors)) {
        // Lưu thông tin khách hàng và đặt vé
        mysqli_begin_transaction($conn);
        try {
            // Kiểm tra khách hàng đã tồn tại
            $res = mysqli_query($conn, "SELECT id FROM khachhang WHERE email='$email' OR phone='$phone'");
            if ($row = mysqli_fetch_assoc($res)) {
                $customer_id = $row['id'];
                // Cập nhật thông tin khách hàng
                mysqli_query($conn, "
                    UPDATE khachhang 
                    SET full_name='$full_name', phone='$phone', id_card='$id_card', date_of_birth='$dob', gender='$gender'
                    WHERE id=$customer_id
                ");
            } else {
                // Thêm khách hàng mới
                mysqli_query($conn, "
                    INSERT INTO khachhang (full_name, email, phone, id_card, date_of_birth, gender)
                    VALUES ('$full_name', '$email', '$phone', '$id_card', '$dob', '$gender')
                ");
                $customer_id = mysqli_insert_id($conn);
            }

            // Tạo vé
            $booking_code = strtoupper(substr(md5(time()),0,6));
            mysqli_query($conn, "
                INSERT INTO ve (flight_id, customer_id, booking_code, payment_method, passengers)
                VALUES ($flight_id, $customer_id, '$booking_code', '$payment_method', $passengers)
            ");

            mysqli_commit($conn);

            $_SESSION['booking_success'] = [
                'code' => $booking_code,
                'flight' => $flight,
                'customer' => [
                    'name' => $full_name,
                    'email' => $email,
                    'phone' => $phone
                ]
            ];

            header("Location: /booking_success.php");
            exit;

        } catch (Exception $e) {
            mysqli_rollback($conn);
            $errors[] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="container mx-auto px-4 py-8 max-w-6xl space-y-6">
    <a href="javascript:history.back()" class="text-blue-600 hover:underline">&larr; Quay lại danh sách chuyến bay</a>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-200 text-red-800 rounded-lg p-4 my-4">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form thông tin hành khách -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm space-y-6">
            <h2 class="text-2xl font-bold mb-4">Thông tin hành khách</h2>
            <form method="POST" class="space-y-4">
                <input type="text" name="full_name" placeholder="Họ và tên" class="w-full p-3 border rounded" required value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                <input type="email" name="email" placeholder="Email" class="w-full p-3 border rounded" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <input type="tel" name="phone" placeholder="Số điện thoại" class="w-full p-3 border rounded" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                <input type="text" name="id_card" placeholder="CMND/CCCD" class="w-full p-3 border rounded" value="<?= htmlspecialchars($_POST['id_card'] ?? '') ?>">
                <input type="date" name="dob" max="<?= date('Y-m-d') ?>" class="w-full p-3 border rounded" value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>">
                <select name="gender" class="w-full p-3 border rounded">
                    <option value="">Chọn giới tính</option>
                    <option value="male" <?= ($_POST['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Nam</option>
                    <option value="female" <?= ($_POST['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Nữ</option>
                    <option value="other" <?= ($_POST['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Khác</option>
                </select>
                <select name="payment_method" class="w-full p-3 border rounded">
                    <option value="card">💳 Thẻ tín dụng/ghi nợ</option>
                    <option value="bank">🏦 Chuyển khoản ngân hàng</option>
                    <option value="momo">📱 Ví MoMo</option>
                    <option value="zalopay">💰 ZaloPay</option>
                    <option value="vnpay">🔵 VNPay</option>
                </select>
                <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700">Xác nhận đặt vé</button>
            </form>
        </div>

        <!-- Sidebar chi tiết chuyến bay -->
        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-sm space-y-4 sticky top-24">
            <h3 class="font-bold text-lg">Chi tiết chuyến bay</h3>
            <p><strong>Mã chuyến bay:</strong> <?= htmlspecialchars($flight['ma_chuyenbay']) ?></p>
            <p><strong>Tên chuyến bay:</strong> <?= htmlspecialchars($flight['ten_chuyenbay']) ?></p>
            <p><strong>Điểm đi:</strong> <?= htmlspecialchars($flight['diem_di']) ?></p>
            <p><strong>Điểm đến:</strong> <?= htmlspecialchars($flight['diem_den']) ?></p>
            <p><strong>Ngày đi:</strong> <?= $flight['ngay_di'] ?></p>
            <p><strong>Giờ đi:</strong> <?= $flight['gio_di'] ?></p>
            <p><strong>Số ghế còn:</strong> <?= $flight['so_ghe'] ?></p>

            <div class="border-t pt-3">
                <p><strong>Giá vé (<?= $passengers ?> người):</strong> <?= number_format($flight['gia_ve'] * $passengers,0,',','.') ?>₫</p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
<?php mysqli_close($conn); ?>
