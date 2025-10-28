<?php
session_start();
require_once 'assets/php/db_connect.php';

// --- BƯỚC 1: XỬ LÝ THÊM/CẬP NHẬT/XÓA SẢN PHẨM TRONG GIỎ HÀNG (SESSIONS) ---

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Hàm lấy thông tin chi tiết sản phẩm từ CSDL
function get_product_details($conn, $product_id) {
    $stmt = $conn->prepare("SELECT id, title, image, price, discount FROM books WHERE id = ? AND visible = 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// 1. Xử lý Thêm sản phẩm từ trang khác (chitietsanpham.php/sanpham.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $product_id = $_POST['product_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    if ($product_id) {
        if (isset($_SESSION['cart'][$product_id])) {
            // Nếu sản phẩm đã tồn tại, tăng số lượng
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Lấy chi tiết sản phẩm từ CSDL
            $product = get_product_details($conn, $product_id);
            if ($product) {
                // Thêm sản phẩm mới vào giỏ hàng
                $product['quantity'] = $quantity;
                $_SESSION['cart'][$product_id] = $product;
            }
        }
    }
    // Ngăn chặn form resubmission sau khi thêm vào giỏ hàng
    header('Location: giohang.php'); 
    exit;
}

// 2. Xử lý Cập nhật số lượng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_quantity') {
    $product_id = $_POST['product_id'] ?? null;
    $new_quantity = $_POST['new_quantity'] ?? 1;
    
    if ($product_id && $new_quantity > 0 && isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = (int)$new_quantity;
    }
    header('Location: giohang.php'); 
    exit;
}

// 3. Xử lý Xóa sản phẩm
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header('Location: giohang.php');
    exit;
}

// 4. Cập nhật lại giỏ hàng (kiểm tra lại giá và chi tiết mới nhất)
$total_cart_price = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => &$item) {
        $product = get_product_details($conn, $id);
        if ($product) {
            // Cập nhật giá và chiết khấu mới nhất
            $item['price'] = $product['price'];
            $item['discount'] = $product['discount'];
            
            $final_price = $item['price'] * (1 - $item['discount'] / 100);
            $subtotal = $final_price * $item['quantity'];
            $total_cart_price += $subtotal;
        } else {
            // Nếu sản phẩm không còn tồn tại, xóa khỏi giỏ hàng
            unset($_SESSION['cart'][$id]);
        }
    }
    unset($item); // Phá vỡ tham chiếu
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Giỏ hàng của bạn | PK Store</title>
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
      /* FIX LỖI FOOTER: Sticky footer pattern */
      html, body {
        height: 100%;
        margin: 0;
      }
      body {
        display: flex;
        flex-direction: column;
        background-color: #fef6f9; /* Nền trắng hồng nhẹ nhàng */
      }
      .main-content-wrapper {
        flex-grow: 1; /* Nội dung chính chiếm hết không gian còn lại */
        padding-top: 30px;
        padding-bottom: 30px;
      }
      .cart-item-row {
          align-items: center;
          background-color: white;
          border-bottom: 1px solid #eee;
          padding: 15px 0;
      }
      .cart-item-img {
          width: 80px;
          height: 80px;
          object-fit: cover;
          border-radius: 8px;
      }
      .product-title a {
          color: #333;
          font-weight: 600;
          text-decoration: none;
      }
      .product-title a:hover {
          color: #f355a4;
      }
      .price-col {
          color: #f355a4;
          font-weight: bold;
      }
      .old-price {
          text-decoration: line-through;
          color: #999;
          font-size: 0.9em;
          margin-left: 5px;
      }
      .discount-label {
          font-size: 0.75em;
          color: green;
          font-weight: bold;
      }
      .total-price {
          font-size: 1.1em;
          font-weight: bold;
      }
      .btn-pink {
          background-color: #f355a4;
          color: white;
          font-weight: bold;
      }
      .btn-pink:hover {
          background-color: #d9448d;
          color: white;
      }
    </style>
  </head>
  <body class="body-cart">
    <?php include 'navbar.php'; ?>

    <div class="container main-content-wrapper">
        <h2 class="fw-bold mb-4" style="color: #f355a4;">Giỏ hàng của bạn (<?= count($_SESSION['cart'] ?? []) ?> sản phẩm)</h2>

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-cart-x-fill fs-2 d-block mb-3"></i>
                <h4 class="mb-3">Giỏ hàng của bạn đang trống.</h4>
                <p>Hãy khám phá các sản phẩm tuyệt vời của chúng tôi!</p>
                <a href="sanpham.php" class="btn btn-pink mt-3">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="bg-white p-4 rounded-3 shadow-sm">
                        <div class="row fw-bold text-muted border-bottom pb-2 mb-3 d-none d-md-flex">
                            <div class="col-1"></div> <div class="col-5">SẢN PHẨM</div>
                            <div class="col-2 text-center">ĐƠN GIÁ</div>
                            <div class="col-2 text-center">SỐ LƯỢNG</div>
                            <div class="col-2 text-end">TỔNG CỘNG</div>
                        </div>

                        <?php foreach ($_SESSION['cart'] as $id => $item): 
                            $final_price = $item['price'] * (1 - $item['discount'] / 100);
                            $subtotal = $final_price * $item['quantity'];
                        ?>
                            <div class="row cart-item-row align-items-center">
                                <div class="col-1 col-md-1 text-center">
                                    <input class="form-check-input" type="checkbox" value="<?= $id ?>" checked>
                                </div>
                                
                                <div class="col-10 col-md-5 d-flex align-items-center">
                                    <img src="../assets/images/<?= htmlspecialchars($item['image']) ?>" 
                                         alt="<?= htmlspecialchars($item['title']) ?>" 
                                         class="cart-item-img me-3">
                                    <div class="product-title">
                                        <a href="chitietsanpham.php?id=<?= $id ?>">
                                            <?= htmlspecialchars($item['title']) ?>
                                        </a>
                                        <?php if ($item['discount'] > 0): ?>
                                            <span class="discount-label d-block">-<?= $item['discount'] ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-4 col-md-2 text-center price-col">
                                    <span class="d-md-none text-muted">Giá: </span>
                                    <?= number_format($final_price, 0, ',', '.') ?>₫
                                    <?php if ($item['discount'] > 0): ?>
                                        <span class="old-price d-block"><?= number_format($item['price'], 0, ',', '.') ?>₫</span>
                                    <?php endif; ?>
                                </div>

                                <div class="col-5 col-md-2 text-center">
                                    <form method="POST" action="giohang.php" class="d-inline">
                                        <input type="hidden" name="action" value="update_quantity">
                                        <input type="hidden" name="product_id" value="<?= $id ?>">
                                        <input type="number" 
                                               name="new_quantity" 
                                               value="<?= $item['quantity'] ?>" 
                                               min="1" 
                                               class="form-control form-control-sm text-center d-inline" 
                                               style="width: 70px;"
                                               onchange="this.form.submit()">
                                    </form>
                                </div>

                                <div class="col-2 col-md-1 text-end total-price">
                                    <span class="d-md-none text-muted">Tổng: </span>
                                    <?= number_format($subtotal, 0, ',', '.') ?>₫
                                </div>

                                <div class="col-1 text-center">
                                    <a href="giohang.php?action=remove&id=<?= $id ?>" class="text-danger" title="Xóa sản phẩm">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="row pt-3 mt-3 border-top align-items-center">
                            <div class="col-6">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label ms-2" for="checkAll">Chọn tất cả</label>
                            </div>
                            <div class="col-6 text-end">
                                <button class="btn btn-outline-secondary btn-sm me-2" disabled id="deleteSelectedBtn">
                                    Xóa mục đã chọn
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-white p-4 rounded-3 shadow-sm">
                        <h4 class="fw-bold mb-4">Tóm tắt đơn hàng</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng tiền hàng (<?= count($_SESSION['cart'] ?? []) ?> sản phẩm):</span>
                            <span class="fw-bold"><?= number_format($total_cart_price, 0, ',', '.') ?>₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4 border-bottom pb-3">
                            <span>Phí vận chuyển:</span>
                            <span class="text-muted">Tính sau</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold" style="color: #f355a4;">Tổng thanh toán:</span>
                            <span class="fs-5 fw-bold text-dark"><?= number_format($total_cart_price, 0, ',', '.') ?>₫</span>
                        </div>
                        <a href="thanhtoan.php" class="btn btn-pink w-100 py-2">
                            Tiến hành Thanh toán
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>


    <section id="subscribe" class="py-5 mb-2">
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-md-5 mb-4 mb-md-0">
            <h2 class="fw-bold mb-3">Đăng Ký Nhận Tin Mới</h2>
            <hr class="border-2 opacity-100" style="width: 80px" />
          </div>

          <div class="col-md-7">
            <p class="mb-4">
              Sed eu feugiat amet, libero ipsum enim pharetra hac dolor sit
              amet, consectetur. Elit adipiscing enim pharetra hac Lorem ipsum
              dolor sit amet consectetur adipisicing elit. Ut odio amet officiis
              aspernatur, corporis et architecto possimus laboriosam nemo illo
              voluptatem provident hic optio consectetur nobis? Ipsam soluta
              tenetur adipisci.
            </p>
            <form class="d-flex pb-2">
              <input
                type="email"
                class="form-control border-0 shadow-none me-4"
                placeholder="       Enter your email address here"
              />
              <button
                type="submit"
                class="btn fw-bold text-white text-uppercase d-flex align-items-center gap-2 ps-4"
                style="background-color: #f355a4"
              >
                Xác Nhận <i class="bi bi-send"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>


    <footer class="footer pt-5">
      <div class="container-lg">
        <div class="row g-4">
                    <div class="col-lg-4 col-md-12">
            <h3 class="footer-title">
              PK <span style="color: black">Store</span>
            </h3>
            <p class="mb-4">
              Đọc sách có vai trò vô cùng quan trọng trong việc mở rộng tri thức
              và rèn luyện tư duy. Qua những trang sách, chúng ta không chỉ tiếp
              thu kiến thức mới mà còn học được cách nhìn nhận, phân tích và
              giải quyết vấn đề
            </p>
            <div class="social-links mb-3">
              <a href="#"><i class="bi bi-facebook"></i></a>
              <a href="#"><i class="bi bi-instagram"></i></a>
              <a href="#"><i class="bi bi-youtube"></i></a>
              <a href="#"><i class="bi bi-tiktok"></i></a>
              <a href="#"><i class="bi bi-github"></i></a>
            </div>
          </div>

          <div class="col-lg-2 col-6">
            <h3 class="footer-title">Chúng tôi</h3>
            <ul class="footer-links">
              <li><a href="gioithieu.php">Mục tiêu</a></li>
              <li><a href="gioithieu.php">Định hướng</a></li>
              <li><a href="#">Điều khoản dịch vụ</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-6">
            <h3 class="footer-title">Khám phá</h3>
            <ul class="footer-links">
              <li><a href="home.php">Trang chủ</a></li>
              <li><a href="sanpham.php">Sản phẩm</a></li>
              <li><a href="tacgia.php">Tác giả</a></li>
              <li><a href="lienhe.php">Liên hệ</a></li>
              <li><a href="gioithieu.php">Giới thiệu</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-6">
            <h3 class="footer-title">Tài khoản</h3>
            <ul class="footer-links">
              <li><a href="../../assets/login.php">Đăng nhập</a></li>
              <li><a href="../../assets/cart.php">Giỏ hàng</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-6">
            <h3 class="footer-title">Hỗ trợ</h3>
            <ul class="footer-links">
              <li><a href="lienhe.php">Trung tâm hỗ trợ</a></li>
              <li><a href="lienhe.php">Báo cáo sự cố</a></li>
              <li><a href="lienhe.php">Đề xuất chỉnh sửa</a></li>
              <li><a href="lienhe.php">Liên hệ</a></li>
            </ul>
          </div>
        </div>
        <p class="m-0 mt-3 pb-1 text-secondary text-center">
          © 2025 PK Store – Đọc sách mỗi ngày, mở rộng tri thức.
        </p>
      </div>
    </footer>

    <script>
      // Lấy phần tử checkbox chính
      const checkAll = document.getElementById('checkAll');
      // Lấy tất cả checkbox của sản phẩm
      const itemCheckboxes = document.querySelectorAll('.form-check-input[type="checkbox"][value]');
      const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');

      function updateCheckAllStatus() {
        const checkedItems = document.querySelectorAll('.form-check-input[type="checkbox"][value]:checked');
        checkAll.checked = checkedItems.length === itemCheckboxes.length && itemCheckboxes.length > 0;
        deleteSelectedBtn.disabled = checkedItems.length === 0;
      }

      // Khi bấm "chọn tất cả"
      checkAll.addEventListener('change', () => {
        itemCheckboxes.forEach(cb => {
          cb.checked = checkAll.checked;
        });
        updateCheckAllStatus();
      });

      // Khi bấm từng checkbox con → cập nhật lại trạng thái của "chọn tất cả"
      itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateCheckAllStatus);
      });

      // Khởi tạo trạng thái ban đầu
      updateCheckAllStatus();

      // Xử lý Xóa mục đã chọn
      deleteSelectedBtn.addEventListener('click', () => {
          const checkedItems = document.querySelectorAll('.form-check-input[type="checkbox"][value]:checked');
          if (checkedItems.length > 0) {
              const idsToDelete = Array.from(checkedItems).map(cb => `id[]=${cb.value}`).join('&');
              if (confirm(`Bạn có chắc muốn xóa ${checkedItems.length} sản phẩm đã chọn khỏi giỏ hàng?`)) {
                  // Chuyển hướng đến một trang xử lý xóa hàng loạt (hoặc dùng AJAX)
                  window.location.href = `giohang.php?action=remove_multiple&${idsToDelete}`;
              }
          }
      });
      
      // *LƯU Ý: CẦN THÊM LOGIC XÓA HÀNG LOẠT VÀO PHP (action=remove_multiple)*
    </script>
  </body>
</html>