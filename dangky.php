<?php
session_start();
require_once "assets/php/db_connect.php"; // đường dẫn đến file kết nối của bạn

// Khi form được gửi đi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm = trim($_POST["confirm_password"]);

    // Kiểm tra hợp lệ
    if (empty($fullname) || empty($password) || empty($confirm)) {
        $error = "⚠️ Vui lòng điền đầy đủ thông tin bắt buộc!";
    } elseif ($password !== $confirm) {
        $error = "⚠️ Mật khẩu nhập lại không khớp!";
    } elseif (strlen($password) < 6) {
        $error = "⚠️ Mật khẩu phải có ít nhất 6 ký tự!";
    } else {
        // Kiểm tra trùng username hoặc email
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $fullname, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "⚠️ Tên đăng nhập hoặc email đã tồn tại!";
        } else {
            // Mã hóa mật khẩu
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role = "user";

            // Thêm người dùng mới
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fullname, $hashed, $email, $role);

            if ($stmt->execute()) {
                $_SESSION["success"] = "🎉 Đăng ký thành công! Hãy đăng nhập.";
                header("Location: dangnhap.php");
                exit;
            } else {
                $error = "❌ Lỗi khi thêm dữ liệu: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký SMEMBER</title>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
  <link rel="stylesheet" href="/../assets/css/style.css" />  
</head>
<body class="body-dangky">
  <div class="register-container">
    <h3 class="text-center fw-bold mb-4">Đăng ký bằng tài khoản mạng xã hội</h3>
    <div class="social-login">
      <button><img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png" alt="Google"> Google</button>
      <button><img src="https://cdn-icons-png.flaticon.com/512/5968/5968841.png" alt="Zalo"> Zalo</button>
    </div>

    <div class="text-center mb-4 text-muted">Hoặc điền thông tin sau</div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <h4>Thông tin cá nhân</h4>
      <div class="row g-3">
        <div class="col-md-6">
          <label for="fullname" class="form-label">Họ và tên</label>
          <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nhập họ và tên" required>
        </div>
        <div class="col-md-6">
          <label for="birthday" class="form-label">Ngày sinh</label>
          <input type="date" class="form-control" id="birthday" name="birthday">
        </div>
        <div class="col-md-6">
          <label for="phone" class="form-label">Số điện thoại</label>
          <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại">
        </div>
        <div class="col-md-6">
          <label for="email" class="form-label">Email <span class="text-muted">(Không bắt buộc)</span></label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email">
          <div class="text-success small mt-1">✓ Hóa đơn VAT khi mua hàng sẽ được gửi qua email này</div>
        </div>
      </div>

      <h4 class="mt-4">Tạo mật khẩu</h4>
      <div class="row g-3">
        <div class="col-md-6">
          <label for="password" class="form-label">Mật khẩu</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu của bạn" required>
          <div class="note"><i class="bi bi-info-circle"></i> Mật khẩu tối thiểu 6 ký tự, có ít nhất 1 chữ số và 1 ký tự chữ.</div>
        </div>
        <div class="col-md-6">
          <label for="confirm-password" class="form-label">Nhập lại mật khẩu</label>
          <input type="password" class="form-control" id="confirm-password" name="confirm_password" placeholder="Nhập lại mật khẩu của bạn" required>
        </div>
      </div>

      <div class="form-check mt-3">
        <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
        <label class="form-check-label" for="newsletter">Đăng ký nhận tin khuyến mãi từ PK Store</label>
      </div>

      <p class="terms mt-3">
        Bằng việc Đăng ký, bạn đã đọc và đồng ý với
        <a href="#">Điều khoản sử dụng</a> và
        <a href="#">Chính sách bảo mật</a> của PK Store.
      </p>

      <div class="footer-actions">
        <a href="dangnhap.php" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
        </a>
        <button type="submit" class="btn btn-dangky">Hoàn tất đăng ký</button>
      </div>
    </form>
  </div>
</body>
</html>
