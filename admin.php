<?php
require_once 'assets/php/db_connect.php';

// Đếm số người dùng
$user_count = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;

// Đếm số sản phẩm
$product_count = $conn->query("SELECT COUNT(*) AS total FROM books")->fetch_assoc()['total'] ?? 0;

// Đếm số đơn hàng
$order_count = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'] ?? 0;

// Tổng doanh thu
$total_revenue = $conn->query("SELECT SUM(total_price) AS total FROM orders")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bảng điều khiển - PK Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff; /* Nền trắng */
      color: #333;
    }
    .sidebar {
      height: 100vh;
      background: #f355a4; /* Màu chủ đạo */
      padding-top: 20px;
      color: white;
    }
    .sidebar a {
      color: white;
      display: block;
      padding: 10px 20px;
      text-decoration: none;
      transition: background 0.3s;
      font-weight: 500;
      border-radius: 10px;
    }
    .sidebar a:hover, .sidebar a.active {
      background: #ff77c7; /* Màu hover/active nhạt hơn */
    }
    .main-content {
      padding: 30px;
    }
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .card h5 {
      font-size: 1.1rem;
      color: #f355a4; /* Màu chủ đạo cho tiêu đề card */
    }
    .text-pink-custom {
      color: #f355a4;
    }
    .border-pink {
        border-color: #f355a4 !important;
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-2 col-md-3 sidebar sticky-md-top">
      <h4 class="text-center text-white mb-4">PK Admin</h4>
      <a href="admin.php" class="active"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
      <a href="admin_users.php"><i class="bi bi-people me-2"></i>Users</a>
      <a href="admin_products.php"><i class="bi bi-box-seam me-2"></i>Products</a>
      <a href="admin_orders.php"><i class="bi bi-receipt me-2"></i>Orders</a>
      <a href="dangxuat.php" class="mt-auto text-danger" style="color: #ffcccc !important;"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a>
    </div>

    <div class="col-lg-10 col-md-9 main-content">
      <h3 class="mb-4 text-dark fw-bold">Bảng điều khiển</h3>
      <div class="row g-4">
        <div class="col-md-3">
          <div class="card p-3 text-center">
            <h5><i class="bi bi-people-fill me-2"></i>Người dùng</h5>
            <h3 class="fw-bold text-dark"><?php echo $user_count; ?></h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card p-3 text-center">
            <h5><i class="bi bi-box-seam me-2"></i>Sản phẩm</h5>
            <h3 class="fw-bold text-dark"><?php echo $product_count; ?></h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card p-3 text-center">
            <h5><i class="bi bi-receipt me-2"></i>Đơn hàng</h5>
            <h3 class="fw-bold text-dark"><?php echo $order_count; ?></h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card p-3 text-center">
            <h5><i class="bi bi-cash-coin me-2"></i>Doanh thu</h5>
            <h3 class="fw-bold text-success">
              <?php echo number_format($total_revenue, 0, ',', '.'); ?>₫
            </h3>
          </div>
        </div>
      </div>

      <div class="mt-5">
        <div class="alert alert-light border border-pink shadow-sm" role="alert">
          <i class="bi bi-info-circle text-pink-custom"></i>  
          <strong>Chào mừng bạn đến với bảng điều khiển PK Admin!</strong><br>
          Quản lý người dùng, sản phẩm và đơn hàng của bạn thật dễ dàng.
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>