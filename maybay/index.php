<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - T-Q Airline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @keyframes background-pan {
            0% {
                background-position: 0% 0%;
            }
            50% {
                background-position: 100% 100%;
            }
            100% {
                background-position: 0% 0%;
            }
        }

        @keyframes float-element {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        /* Thêm CSS cho hình ảnh nền Full Screen */
        .full-background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Thay thế bằng đường dẫn ảnh của bạn */
            background-image: url('images/maybay2.jpg'); 
            background-size: cover; /* Đảm bảo ảnh che phủ toàn bộ nền */
            background-position: center; /* Căn giữa ảnh */
            background-repeat: no-repeat; /* Không lặp lại ảnh */
            filter: brightness(60%) contrast(120%); /* Tối và sắc nét hơn để làm nổi bật form */
            z-index: 1; /* Đặt lớp nền thấp nhất */
        }

        body {
            /* Loại bỏ background gradient cũ */
            /* body chỉ còn là container chính */
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95); /* Nền trắng rõ hơn */
            backdrop-filter: blur(5px); /* Giảm blur một chút */
            border-radius: 20px;
            box-shadow: 0px 15px 40px rgba(0,0,0,0.3); /* Đổ bóng mạnh hơn */
            padding: 40px;
            animation: float-element 4s ease-in-out infinite;
            position: relative;
            z-index: 10; /* Đảm bảo form nằm trên nền */
        }
        .login-title {
            text-align: center;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 25px;
            font-size: 2.2em;
        }
        .login-btn {
            width: 100%;
            background: #007bff;
            border: none;
            transition: all 0.3s ease;
            padding: 10px 0;
            font-size: 1.1em;
            border-radius: 10px;
        }
        .login-btn:hover {
            background: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
        }
        /* Loại bỏ CSS cho .login-image vì nó không còn được dùng */
        footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            z-index: 5;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 15px;
        }
    </style>
</head>

<body>
<div class="full-background-image"></div> 

<div class="container py-5">
    <div class="row justify-content-center align-items-center">
        
        <div class="col-md-5">
            <div class="login-container">
                <h3 class="login-title">🛫 T-Q AIRLINE</h3> 

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <form action="./handle/login_process.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Tên đăng nhập</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu" required>
                    </div>
                    
                    <button type="submit" name="login" class="btn btn-primary login-btn">Đăng nhập</button>
                </form>
                <button class="link-btn" data-bs-toggle="modal" data-bs-target="#registerModal">Đăng ký</button>
                <button class="link-btn" data-bs-toggle="modal" data-bs-target="#forgotModal">Quên mật khẩu?</button>
            </div>
        </div>
    </div>
</div>

<footer>© 2025 - T-Q Airline</footer>

<!-- Modal Đăng ký -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4">
      <h4 class="text-center text-primary mb-3">Đăng ký tài khoản</h4>
      <form method="POST" action="./handle/register_process.php">
        <div class="mb-3">
          <label class="form-label">Tên đăng nhập</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Mật khẩu</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nhập lại mật khẩu</label>
          <input type="password" name="confirm" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
      </form>
    </div>
  </div>
</div>

<!-- Modal Quên mật khẩu -->
<div class="modal fade" id="forgotModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4">
      <h4 class="text-center text-warning mb-3">Quên mật khẩu</h4>
      <form method="POST" action="./handle/forgot_password.php">
        <div class="mb-3">
          <label class="form-label">Nhập Email để đặt lại mật khẩu</label>
          <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
        </div>
        <button type="submit" class="btn btn-warning w-100">Gửi yêu cầu</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>