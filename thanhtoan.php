<?php
session_start();
// Đảm bảo đường dẫn đến db_connect.php là đúng
require_once 'assets/php/db_connect.php'; 

// --- CẤU HÌNH CỐ ĐỊNH ---
$SHIPPING_FEE = 30000; // Phí vận chuyển cố định
$DELIVERY_ADDRESS_DEFAULT = "Địa chỉ mặc định của người dùng (Cần lấy từ CSDL)";
$PAYMENT_METHOD_DEFAULT = "COD";
$error_message = ''; // Biến lưu lỗi

// Kiểm tra giỏ hàng và đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: dangnhap.php'); 
    exit;
}
if (empty($_SESSION['cart'])) {
    header('Location: giohang.php'); 
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_items = $_SESSION['cart'];

// --- 1. XỬ LÝ ĐẶT HÀNG KHI BẤM NÚT "XÁC NHẬN" ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'confirm_order') {
    
    // Lấy dữ liệu từ form
    $shipping_address = trim($_POST['shipping_address'] ?? $DELIVERY_ADDRESS_DEFAULT);
    $payment_method = $_POST['payment_method'] ?? $PAYMENT_METHOD_DEFAULT;
    
    // Tính lại tổng tiền cuối cùng (bao gồm phí ship)
    $subtotal = 0;
    foreach ($cart_items as $item) {
        $final_price = $item['price'] * (1 - ($item['discount'] ?? 0) / 100);
        $subtotal += $final_price * $item['quantity'];
    }
    $total_price = $subtotal + $SHIPPING_FEE;
    
    try {
        // Bắt đầu Transaction để đảm bảo tính toàn vẹn dữ liệu
        $conn->begin_transaction();

        // 1.1. Lưu thông tin chung vào bảng orders
        $stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_price, status, payment_method, shipping_address) VALUES (?, ?, 'Pending', ?, ?)");
        
        if (!$stmt_order) {
             throw new Exception("Lỗi prepare statement ORDERS: " . $conn->error);
        }

        $stmt_order->bind_param("idss", $user_id, $total_price, $payment_method, $shipping_address);
        $stmt_order->execute();
        $order_id = $conn->insert_id; // Lấy ID của đơn hàng vừa tạo
        $stmt_order->close();

        // 1.2. Lưu chi tiết sản phẩm vào bảng order_details
        $stmt_detail = $conn->prepare("INSERT INTO order_details (order_id, product_id, title, price, discount, quantity) VALUES (?, ?, ?, ?, ?, ?)");
        
        if (!$stmt_detail) {
            throw new Exception("Lỗi prepare statement ORDER_DETAILS: " . $conn->error);
        }

        foreach ($cart_items as $product_id => $item) {
            $discount_value = $item['discount'] ?? 0;
            $final_price = $item['price'] * (1 - $discount_value / 100);
            
            // Tham số bind_param: iisdii (integer, integer, string, double/decimal, integer, integer)
            $stmt_detail->bind_param(
                "iisdii", 
                $order_id, 
                $product_id, 
                $item['title'], 
                $final_price, 
                $discount_value, 
                $item['quantity']
            );
            $stmt_detail->execute();
        }
        $stmt_detail->close();

        // Commit Transaction
        $conn->commit();

        // 1.3. Xóa giỏ hàng và chuyển hướng
        unset($_SESSION['cart']);
        header("Location: lichsu.php?order_success=" . $order_id);
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        // Cập nhật biến lỗi
        $error_message = "Có lỗi xảy ra trong quá trình đặt hàng. Vui lòng thử lại. Lỗi: " . $e->getMessage();
    }
}


// --- 2. TÍNH TOÁN LẠI TỔNG TIỀN ĐỂ HIỂN THỊ ---
$subtotal = 0;
foreach ($cart_items as $item) {
    $final_price = $item['price'] * (1 - ($item['discount'] ?? 0) / 100);
    $subtotal += $final_price * $item['quantity'];
}
$total_final = $subtotal + $SHIPPING_FEE;

?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thanh toán</title>
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
        .body-thanhtoan {
            background-color: #fef6f9;
        }
        .checkout-container {
            max-width: 900px;
            padding-top: 50px;
            padding-bottom: 50px;
        }
        .info-card, .summary-card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }
        .table th, .table td {
            font-size: 0.95rem;
        }
        .order-summary strong.text-danger {
            font-size: 1.5rem;
        }
        .btn-confirm {
            background-color: #f355a4;
            color: white;
            font-weight: bold;
            padding: 10px 40px;
            border-radius: 25px;
        }
        .btn-confirm:hover {
            background-color: #d9448d;
            color: white;
        }
    </style>
  </head>
  <body class="body-thanhtoan">
    <?php include 'navbar.php'; // Đảm bảo bạn đã có file navbar.php ?>

    <div class="container checkout-container">
      <h3 class="text-center mb-4 text-uppercase fw-bold text-dark">
        Thanh Toán Đơn Hàng
      </h3>

      <?php if (!empty($error_message)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-octagon-fill"></i> <?= htmlspecialchars($error_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      <?php endif; ?>

      <form method="POST" action="thanhtoan.php">
        <input type="hidden" name="action" value="confirm_order">

        <div class="row">
            <div class="col-lg-7">
                <div class="info-card">
                    <h5 class="fw-bold mb-3" style="color: #f355a4;"><i class="bi bi-geo-alt-fill"></i> Thông tin nhận hàng</h5>
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Địa chỉ nhận hàng (*)</label>
                        <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" required><?= htmlspecialchars($DELIVERY_ADDRESS_DEFAULT) ?></textarea>
                    </div>

                    <h5 class="fw-bold mt-4 mb-3" style="color: #f355a4;"><i class="bi bi-wallet-fill"></i> Phương thức thanh toán</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="COD" checked required>
                        <label class="form-check-label" for="payment_cod">
                            Thanh toán khi nhận hàng (COD)
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_transfer" value="Chuyển khoản" required>
                        <label class="form-check-label" for="payment_transfer">
                            Chuyển khoản Ngân hàng
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_momo" value="Momo" required>
                        <label class="form-check-label" for="payment_momo">
                            Ví điện tử Momo
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="summary-card">
                    <h5 class="fw-bold mb-3" style="color: #f355a4;"><i class="bi bi-receipt-cutoff"></i> Đơn hàng của bạn</h5>
                    
                    <table class="table table-sm">
                      <thead>
                        <tr class="table-light">
                          <th>Sản phẩm</th>
                          <th class="text-center">SL</th>
                          <th class="text-end">Thành tiền</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($cart_items as $product_id => $item): 
                            $final_price = $item['price'] * (1 - ($item['discount'] ?? 0) / 100);
                            $item_subtotal = $final_price * $item['quantity'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item['title']) ?></td>
                            <td class="text-center"><?= $item['quantity'] ?></td>
                            <td class="text-end"><?= number_format($item_subtotal, 0, ',', '.') ?> đ</td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>

                    <div class="order-summary mt-3">
                      <div class="d-flex justify-content-between">
                        <span>Tạm tính:</span>
                        <strong><?= number_format($subtotal, 0, ',', '.') ?> đ</strong>
                      </div>
                      <div class="d-flex justify-content-between">
                        <span>Phí vận chuyển:</span>
                        <strong><?= number_format($SHIPPING_FEE, 0, ',', '.') ?> đ</strong>
                      </div>
                      <hr>
                      <div class="d-flex justify-content-between fs-5 mt-2">
                        <span>Tổng cộng:</span>
                        <strong class="text-danger"><?= number_format($total_final, 0, ',', '.') ?> đ</strong>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-confirm px-5">
                Xác nhận đặt hàng
            </button>
        </div>
      </form>
    </div>
    
  </body>
</html>