<?php
require_once "assets/php/db_connect.php";

// Xử lý cập nhật trạng thái đơn hàng
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['order_id'])) {
  $order_id = $_POST['order_id'];
  $status = $_POST['status'];
  $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
  $stmt->bind_param("si", $status, $order_id);
  $stmt->execute();
  header("Location: admin_orders.php");
  exit;
}

// Lấy danh sách đơn hàng
$search = $_GET['search'] ?? '';
$sql = "
    SELECT o.*, u.username 
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    WHERE u.username LIKE '%$search%' OR o.status LIKE '%$search%'
    ORDER BY o.created_at DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Quản lý đơn hàng | PK Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff;
      /* Nền trắng */
      color: #333;
    }

    .sidebar {
      height: 100vh;
      background: #f355a4;
      /* Màu chủ đạo */
      padding-top: 20px;
      color: white;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
      border-radius: 10px;
      transition: background 0.2s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: #ff77c7;
      /* Màu hover/active nhạt hơn */
    }

    .main-content {
      padding: 30px;
    }

    .btn-pink {
      background: #f355a4;
      color: white;
      border: none;
    }

    .btn-pink:hover {
      background: #d9448d;
      color: white;
    }

    table thead {
      background-color: #ffe4f2;
    }

    .status-pending {
      color: #e67e22;
      font-weight: 600;
    }

    .status-completed {
      color: #27ae60;
      font-weight: 600;
    }

    .status-cancelled {
      color: #e74c3c;
      font-weight: 600;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-2 col-md-3 sidebar d-flex flex-column sticky-md-top">
        <h4 class="text-center mb-4">PK Admin</h4>
        <a href="admin.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a href="admin_users.php"><i class="bi bi-people me-2"></i> Users</a>
        <a href="admin_products.php"><i class="bi bi-box-seam me-2"></i> Products</a>
        <a href="admin_orders.php" class="active"><i class="bi bi-receipt me-2"></i> Orders</a>
        <a href="dangxuat.php" class="mt-auto text-danger" style="color: #ffcccc !important;"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a>
      </div>

      <div class="col-lg-10 col-md-9 main-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3>Quản lý đơn hàng</h3>
        </div>

        <form class="d-flex mb-3" method="get">
          <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control me-2" placeholder="Tìm theo tên người dùng hoặc trạng thái...">
          <button class="btn btn-pink"><i class="bi bi-search"></i> Tìm kiếm</button>
        </form>

        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center">
            <thead>
              <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['username'] ?? '—') ?></td>
                    <td><?= number_format($row['total_price'], 0, ',', '.') ?>đ</td>
                    <td>
                      <span class="status-<?= strtolower($row['status']) ?>">
                        <?= ucfirst($row['status']) ?>
                      </span>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                    <td>
                      <button
                        class="btn btn-sm btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#statusModal"
                        data-id="<?= $row['id'] ?>"
                        data-status="<?= $row['status'] ?>">Cập nhật</button>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center">Không có đơn hàng nào</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post">
          <div class="modal-header">
            <h5 class="modal-title">Cập nhật trạng thái đơn hàng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="order_id" id="order_id">
            <div class="mb-3">
              <label class="form-label">Trạng thái</label>
              <select name="status" id="status" class="form-select">
                <option value="Pending">Đang xử lý</option>
                <option value="Completed">Hoàn tất</option>
                <option value="Cancelled">Đã hủy</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-pink">Lưu</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const modal = document.getElementById('statusModal');
    modal.addEventListener('show.bs.modal', event => {
      const button = event.relatedTarget;
      const id = button.getAttribute('data-id');
      const status = button.getAttribute('data-status');
      document.getElementById('order_id').value = id;
      document.getElementById('status').value = status;
    });
  </script>
</body>

</html>