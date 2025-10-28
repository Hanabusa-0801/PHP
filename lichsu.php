<?php
session_start();
// Đảm bảo đường dẫn đến db_connect.php là đúng
// LƯU Ý: Thay đổi đường dẫn này nếu file db_connect.php nằm ở vị trí khác
require_once 'assets/php/db_connect.php'; 

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: dangnhap.php'); 
    exit;
}

$user_id = $_SESSION['user_id'];
$search = $_GET['search'] ?? '';

// --- Truy vấn Lịch sử đơn hàng của người dùng hiện tại ---
// 1. Lấy tất cả orders của user_id
$sql_orders = "SELECT id, created_at, total_price, status, payment_method, shipping_address FROM orders WHERE user_id = ?";
$params_orders = [$user_id];
$types_orders = 'i';

// Thêm điều kiện tìm kiếm
if (!empty($search)) {
    // Tìm kiếm theo ID đơn hàng (vd: #DH000123) hoặc Trạng thái (Pending, Completed...)
    $sql_orders .= " AND (id LIKE ? OR status LIKE ?)"; 
    $params_orders[] = '%' . $search . '%';
    $params_orders[] = '%' . $search . '%';
    $types_orders .= 'ss';
}

$sql_orders .= " ORDER BY created_at DESC";

$stmt_orders = $conn->prepare($sql_orders);
// Bind tham số cho câu lệnh SQL chính
$stmt_orders->bind_param($types_orders, ...$params_orders);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();

// Hàm lấy chi tiết sản phẩm của một đơn hàng
function get_order_details($conn, $order_id) {
    // Truy vấn bảng order_details
    $stmt = $conn->prepare("SELECT title, quantity, price, discount FROM order_details WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Map trạng thái sang class CSS để hiển thị màu sắc
function get_status_class($status) {
    switch ($status) {
        case 'Completed':
            return 'status-completed';
        case 'Cancelled':
            return 'status-cancelled';
        case 'Shipped':
            return 'status-shipped';
        default: // Pending
            return 'status-pending';
    }
}

// Xử lý Hủy đơn hàng (Nếu người dùng bấm nút Hủy)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'cancel' && isset($_GET['id'])) {
    $order_id_to_cancel = (int)$_GET['id'];
    
    // Chỉ cho phép hủy đơn hàng của chính user đó và đơn hàng phải ở trạng thái Pending
    $stmt_cancel = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ? AND user_id = ? AND status = 'Pending'");
    $stmt_cancel->bind_param("ii", $order_id_to_cancel, $user_id);
    $stmt_cancel->execute();
    
    // Sau khi hủy, chuyển hướng về trang lịch sử để làm mới
    header('Location: lichsu.php?cancel_success=' . $order_id_to_cancel);
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lịch sử đơn hàng | PK Store</title>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="/../assets/css/style.css" /> 
    <style>
        .body-lichsu {
            background-color: #fef6f9;
        }
        .history-container {
            padding-top: 30px;
            padding-bottom: 50px;
            min-height: 80vh;
        }
        .order-card {
            background-color: #ffffff;
            border: 1px solid #f355a4;
            border-radius: 12px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #f355a4;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .order-status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9em;
        }
        /* Định nghĩa màu sắc cho các trạng thái */
        .status-pending { 
            color: #ff9800;
            background-color: #fff3e0;
        }
        .status-shipped {
             color: #2196f3;
            background-color: #e3f2fd;
        }
        .status-completed {
            color: #4caf50;
            background-color: #e8f5e9;
        }
        .status-cancelled {
            color: #f44336;
            background-color: #ffebee;
        }
        .order-total {
            font-size: 1.2em;
            font-weight: bold;
            color: #f355a4;
        }
        .product-detail-item {
            border-left: 3px solid #f8acac;
            padding-left: 10px;
            margin-bottom: 10px;
            font-size: 0.9em;
        }
        .btn-cancel {
            background-color: #f355a4; 
            color: white;
            border: none;
        }
        .btn-cancel:hover {
            background-color: #d9448d;
            color: white;
        }
    </style>
</head>
<body class="body-lichsu">

    <?php include 'navbar.php'; // Đảm bảo bạn đã tạo file navbar.php ?>

  <div class="container history-container">
    <h2 class="page-title fw-bold mb-4" style="color: #f355a4;">Lịch Sử Đơn Hàng</h2>

    <?php if (isset($_GET['order_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> Đặt hàng thành công! Mã đơn hàng của bạn là: <strong>#DH<?= str_pad((int)$_GET['order_success'], 6, '0', STR_PAD_LEFT) ?></strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($_GET['cancel_success'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> Đơn hàng <strong>#DH<?= str_pad((int)$_GET['cancel_success'], 6, '0', STR_PAD_LEFT) ?></strong> đã được hủy thành công.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="GET" action="lichsu.php" class="input-group mb-4 w-100 w-md-50">
      <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo Mã đơn hàng hoặc Trạng thái..." value="<?= htmlspecialchars($search) ?>">
      <button class="btn btn-primary" type="submit" style="background-color: #f355a4; border-color: #f355a4;">Tìm kiếm</button>
    </form>

    <?php if ($result_orders->num_rows > 0): ?>
      <?php while ($order = $result_orders->fetch_assoc()): 
        $details_result = get_order_details($conn, $order['id']);
        $status_class = get_status_class($order['status']);
      ?>
        <div class="order-card">
          <div class="order-header">
            <div>
              <strong class="text-dark">Mã đơn hàng: #DH<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></strong>
              <span class="ms-3 text-muted d-block d-md-inline-block">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
            </div>
            <span class="order-status <?= $status_class ?>"><?= htmlspecialchars($order['status']) ?></span>
          </div>

          <?php while ($item = $details_result->fetch_assoc()): ?>
          <div class="row align-items-center mb-2 product-detail-item">
            <div class="col-md-9">
              <p class="mb-0"><strong><?= htmlspecialchars($item['title']) ?></strong></p>
              <p class="mb-0 text-muted">SL: <?= $item['quantity'] ?> | 
                  Đơn giá: <?= number_format($item['price'], 0, ',', '.') ?>₫
                  <?php if ($item['discount'] > 0): ?> (Giảm <?= $item['discount'] ?>%)<?php endif; ?>
              </p>
            </div>
            <div class="col-md-3 text-md-end">
              <?php 
                $subtotal = $item['price'] * $item['quantity'];
                echo '<p class="mb-0 fw-bold">' . number_format($subtotal, 0, ',', '.') . '₫</p>';
              ?>
            </div>
          </div>
          <?php endwhile; ?>
          
          <hr>

          <div class="row align-items-center">
            <div class="col-md-8">
              <p class="mb-1"><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
              <p class="mb-0"><strong>Địa chỉ nhận hàng:</strong> <?= htmlspecialchars($order['shipping_address']) ?></p>
            </div>
            <div class="col-md-4 text-md-end">
              <p class="order-total mb-2">Tổng thanh toán: <?= number_format($order['total_price'], 0, ',', '.') ?>₫</p>
              <?php if ($order['status'] === 'Pending'): ?>
                <button class="btn btn-sm btn-cancel" onclick="confirmCancel(<?= $order['id'] ?>)">Hủy đơn hàng</button>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert alert-info text-center py-4">
        <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
        <?php if (!empty($search)): ?>
            Không tìm thấy đơn hàng nào phù hợp với từ khóa "<?= htmlspecialchars($search) ?>".
        <?php else: ?>
            Bạn chưa có đơn hàng nào.
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </div>
  
  <footer class="footer pt-5">
    </footer>

  <script>
    // Hàm xác nhận hủy đơn hàng và chuyển hướng đến hành động hủy đơn hàng (GET request)
    function confirmCancel(orderId) {
      if (confirm("Bạn có chắc chắn muốn hủy đơn hàng #DH" + String(orderId).padStart(6, '0') + "?")) {
        // Chuyển hướng về chính trang lichsu.php nhưng với action=cancel và id=...
        window.location.href = `lichsu.php?action=cancel&id=${orderId}`;
      }
    }
  </script>
</body>
</html>