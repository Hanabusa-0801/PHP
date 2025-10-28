<?php
session_start();

require_once "assets/php/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  // Chuẩn bị truy vấn
  $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      // Lưu session
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['role'] = $user['role'];

      // Chuyển hướng theo quyền
      if ($user['role'] === 'admin') {
        header("Location: admin.php");
        exit();
      } else {
        header("Location: index.php");
        exit();
      }
    } else {
      echo "<script>alert('Sai mật khẩu!'); window.location='dangnhap.php';</script>";
    }
  } else {
    echo "<script>alert('Tài khoản không tồn tại!'); window.location='dangnhap.php';</script>";
  }
}
?>



<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập SMEMBER</title>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>

<body class="body-login">
  <div class="login-container">
    <div class="login-box">
      <!-- Bên trái -->
      <div class="login-left">
        <h3>Đăng nhập tài khoản <span>PK Store</span></h3>
        <p class="text-muted">Để không bỏ lỡ các ưu đãi hấp dẫn từ PK Store</p>

        <div class="benefit-box">
          <div class="benefit-item">
            <i class="bi bi-gift-fill"></i>
            <span><b>Chiết khấu đến 5%</b> khi mua các sản phẩm tại PK Store</span>
          </div>
          <div class="benefit-item">
            <i class="bi bi-truck"></i>
            <span><b>Miễn phí giao hàng</b> cho thành viên từ đơn 300.000đ</span>
          </div>
          <div class="benefit-item">
            <i class="bi bi-cash-coin"></i>
            <span><b>Hỗ trợ trả góp 0%</b></span>
          </div>
          <div class="benefit-item">
            <i class="bi bi-award-fill"></i>
            <span><b>Thăng hạng nhận voucher đến 300.000đ</b></span>
          </div>
        </div>
      </div>

      <!-- Bên phải -->
      <div class="login-right">
        <h2>Đăng nhập tài khoản</h2>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger py-2"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="dangnhap.php">
          <div class="mb-3">
            <label class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" name="username" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <button type="submit" class="btn btn-dangnhap w-100">Đăng nhập</button>
        </form>



        <div class="text-center mt-3">
          <a href="#" class="text-primary text-decoration-none">Quên mật khẩu?</a>
        </div>

        <div class="text-center my-3 text-muted">Hoặc đăng nhập bằng</div>
        <div class="d-flex justify-content-between social-login">
          <button><img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png"> Google</button>
          <button><img src="https://cdn-icons-png.flaticon.com/512/5968/5968841.png"> Zalo</button>
        </div>

        <div class="text-center mt-4">
          Bạn chưa có tài khoản?
          <a href="dangky.php" class="text-danger fw-bold text-decoration-none">Đăng ký ngay</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>