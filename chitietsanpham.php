<?php
// Bắt đầu session và kết nối CSDL
session_start();
// Đảm bảo đường dẫn đến db_connect.php là đúng
require_once 'assets/php/db_connect.php'; 

// Lấy ID sản phẩm từ URL
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    // Nếu không có ID, chuyển hướng về trang sản phẩm
    header("Location: sanpham.php");
    exit;
}

// Truy vấn lấy chi tiết sản phẩm
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ? AND visible = 1");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Không tìm thấy sản phẩm
    echo "<div class='alert alert-danger text-center mt-5'>Sản phẩm không tồn tại hoặc đã bị ẩn.</div>";
    exit;
}

$product = $result->fetch_assoc();

// Tính toán giá cuối cùng
$price = $product['price'];
$discount = $product['discount'] ?? 0;
$final_price = $price * (1 - $discount / 100);

// Giả định số lượng đã bán (vì không có cột 'sold')
$sold = mt_rand(50, 500); 
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($product['title']) ?> | PK Store</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="/../assets/css/style.css" /> 
    
    <style>
      body {
        background-color: #fef6f9; /* Nền trắng hồng nhẹ nhàng */
      }
      .product-img-detail {
        max-height: 500px;
        width: 100%;
        object-fit: cover;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 10px;
        background-color: white; /* Ảnh có nền trắng */
      }
      .detail-card {
        background-color: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      }
      .detail-price {
        font-size: 2.5rem;
        color: #f355a4; /* Màu chủ đạo */
        font-weight: 800;
        line-height: 1;
      }
      .old-price-detail {
        text-decoration: line-through;
        color: #999;
        font-size: 1.1rem;
        margin-left: 10px;
      }
      .discount-badge-detail {
        background-color: #d9448d; /* Màu đậm hơn */
        color: white;
        padding: 4px 10px;
        border-radius: 5px;
        font-size: 0.9rem;
        font-weight: bold;
        margin-left: 10px;
      }
      .btn-add-cart {
        background-color: #f355a4;
        color: white;
        border: none;
        padding: 15px 30px;
        font-size: 1.1rem;
        border-radius: 10px;
        font-weight: bold;
      }
      .btn-buy-now {
        background-color: white;
        color: #f355a4;
        border: 2px solid #f355a4; 
        padding: 15px 30px;
        font-size: 1.1rem;
        border-radius: 10px;
        font-weight: bold;
      }
      .btn-add-cart:hover {
        background-color: #d9448d;
        color: white;
      }
      .btn-buy-now:hover {
        background-color: #fef6f9;
        color: #d9448d;
      }
      .description-section {
        background-color: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      }
      .quantity-input {
        max-width: 100px;
        border: 1px solid #ccc;
        border-radius: 5px;
        height: 40px;
      }
    </style>
  </head>
  <body>
    <?php include 'navbar.php'; ?>
    
    <div class="container-lg my-5">
      <div class="row mb-4">
        <div class="col-12">
           <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white p-2 rounded-3">
              <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
              <li class="breadcrumb-item"><a href="sanpham.php">Sản phẩm</a></li>
              <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['title']) ?></li>
            </ol>
          </nav>
        </div>
      </div>
      
      <div class="row g-5">
        <div class="col-lg-5 col-md-6 text-center">
          <img
            src="../assets/images/<?= htmlspecialchars($product['image']) ?>"
            alt="<?= htmlspecialchars($product['title']) ?>"
            class="img-fluid product-img-detail"
          />
        </div>

        <div class="col-lg-7 col-md-6">
          <div class="detail-card">
              <h1 class="fw-bold mb-3"><?= htmlspecialchars($product['title']) ?></h1>
              
              <p class="text-muted fs-6">
                Thể loại: <span class="fw-bold text-dark"><?= htmlspecialchars($product['category']) ?></span> 
                | Tác giả: <span class="fw-bold text-dark">Đang cập nhật</span>
                | Đã bán: <span class="fw-bold text-dark"><?= $sold ?></span>
              </p>

              <hr>

              <div class="d-flex align-items-baseline mb-4">
                <span class="detail-price"><?= number_format($final_price, 0, ',', '.') ?> đ</span>
                <?php if ($discount > 0): ?>
                    <span class="old-price-detail"><?= number_format($price, 0, ',', '.') ?> đ</span>
                    <span class="discount-badge-detail">-<?= $discount ?>%</span>
                <?php endif; ?>
              </div>

              <form method="POST" action="giohang.php">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                
                <div class="mb-5 d-flex align-items-center">
                  <label for="quantity" class="form-label me-3 fw-bold m-0">Số lượng:</label>
                  <input 
                    type="number" 
                    class="form-control quantity-input text-center" 
                    id="quantity" 
                    name="quantity" 
                    value="1" 
                    min="1" 
                    max="100" 
                    required
                  />
                </div>
                
                <div class="d-grid gap-3 d-sm-block">
                  <button 
                    type="submit" 
                    name="action" 
                    value="add_to_cart" 
                    class="btn btn-add-cart me-sm-3 w-100 w-sm-auto"
                  >
                    <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ hàng
                  </button>

                  <button 
                    type="button" 
                    class="btn btn-buy-now w-100 w-sm-auto"
                    onclick="document.querySelector('form').action='thanhtoan.php'; document.querySelector('form').submit();"
                  >
                    <i class="bi bi-lightning-charge me-1"></i> Mua ngay
                  </button>
                </div>
              </form>
          </div>
        </div>
      </div>
      
      <div class="row mt-5">
        <div class="col-12">
          <div class="description-section">
            <h4 class="fw-bold text-pink mb-4" style="color: #f355a4;">Mô tả sách</h4>
            <div class="description-content">
              <p><strong>Tóm tắt:</strong></p>
              <p>
                <?= htmlspecialchars($product['title']) ?> là tác phẩm tiêu biểu của thể loại <?= htmlspecialchars($product['category']) ?>. Câu chuyện xoay quanh nhân vật chính trong bối cảnh giả tưởng đầy màu sắc, nơi sự sống và cái chết đan xen, đòi hỏi sự dũng cảm và trí tuệ để vượt qua các thử thách.
              </p>
              <p>
                Sách không chỉ mang đến những tình tiết kịch tính, bất ngờ mà còn chứa đựng những thông điệp sâu sắc về tình bạn, lòng trung thành và ý nghĩa của sự hy sinh. Đây là cuốn sách không thể thiếu cho những ai yêu thích khám phá và phiêu lưu.
              </p>
              <p>
                *Lưu ý: Nội dung mô tả này chỉ mang tính chất minh họa. Bạn cần thêm cột 'description' vào bảng books nếu muốn hiển thị nội dung thực tế.*
              </p>
            </div>
          </div>
        </div>
      </div>

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
        const quantityInput = document.getElementById('quantity');
        quantityInput.addEventListener('change', () => {
            if (parseInt(quantityInput.value) < 1 || isNaN(parseInt(quantityInput.value))) {
                quantityInput.value = 1;
            }
        });
    </script>
  </body>
</html>