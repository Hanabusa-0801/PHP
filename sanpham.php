<?php
// Đường dẫn tương đối dựa trên cấu trúc file đã thấy
require_once 'assets/php/db_connect.php'; 

// === 1. LẤY THÔNG TIN TÌM KIẾM VÀ LỌC ===
$search = $_GET['search'] ?? ''; // Tìm kiếm chung (tên/thể loại)
$category_filter = $_GET['category'] ?? ''; // Lọc theo nút category
$sort = $_GET['sort'] ?? 'popular'; // Mặc định là popular

// === 2. XÂY DỰNG CÂU TRUY VẤN SQL ===
$sql = "SELECT * FROM books WHERE visible = 1";

// Thêm điều kiện tìm kiếm chung (theo title HOẶC category)
if (!empty($search)) {
    $escaped_search = '%' . $conn->real_escape_string($search) . '%';
    $sql .= " AND (title LIKE '$escaped_search' OR category LIKE '$escaped_search')";
}

// Thêm điều kiện lọc theo Category (chọn từ nút/dropdown)
if (!empty($category_filter) && $category_filter !== 'all') {
    $escaped_category = $conn->real_escape_string($category_filter);
    $sql .= " AND category LIKE '%$escaped_category%'"; // Dùng LIKE để linh hoạt hơn
}

// === 3. XỬ LÝ SẮP XẾP ===
switch ($sort) {
    case 'price_asc':
        $sql .= " ORDER BY (price * (1 - discount / 100)) ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY (price * (1 - discount / 100)) DESC";
        break;
    case 'newest':
        $sql .= " ORDER BY id DESC"; 
        break;
    case 'popular':
    default:
        $sql .= " ORDER BY id ASC"; // Giả định ID tăng dần/sắp xếp ban đầu là phổ biến
        break;
}

// Thực thi truy vấn
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sản phẩm | PK Store</title>
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
      /* Thêm style cho card sản phẩm */
      .product-card {
        text-align: center;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 10px;
        transition: box-shadow 0.3s;
        cursor: pointer;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
      }
      .product-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      }
      .product-card img {
        height: 250px; /* Chiều cao cố định cho ảnh */
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
      }
      .product-info {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding-top: 10px;
      }
      .product-name {
        font-weight: 600;
        min-height: 40px; /* Giữ chỗ cho 2 dòng tên */
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 5px;
      }
      .price {
        color: #f355a4;
        font-weight: 700;
        font-size: 1.1rem;
        margin-right: 5px;
      }
      .old-price {
        color: #999;
        text-decoration: line-through;
        font-size: 0.9rem;
      }
      .discount-badge {
        background-color: #f355a4;
        color: white;
        padding: 2px 5px;
        border-radius: 5px;
        font-size: 0.75rem;
        font-weight: bold;
      }
      .sold {
        font-size: 0.85rem;
        color: #666;
        margin-top: 5px;
      }
      .add-cart-btn {
        display: block;
        margin-top: 10px;
        padding: 8px;
        background-color: #f355a4;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background 0.2s;
        font-weight: 500;
      }
      .add-cart-btn:hover {
        background-color: #d9448d;
      }
    </style>
  </head>
  <body>
     <?php include 'navbar.php'; ?>

    <div class="d-flex justify-content-center mt-3">
      <div class="row container-lg">
        <div
          id="carouselExampleSlidesOnly"
          class="col-md-6 carousel slide"
          data-bs-ride="carousel"
        >
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img
                src="../assets/images/banner2.jpg"
                class="d-block w-100 h-25 rounded-5"
                alt="..."
              />
            </div>
            <div class="carousel-item">
              <img
                src="../assets/images/banner3.jpg"
                class="d-block w-100 h-25 rounded-5"
                alt="..."
              />
            </div>
          </div>
        </div>

        <div
          id="carouselExampleSlidesOnly"
          class="col-md-6 carousel slide d-md-block d-none"
          data-bs-ride="carousel"
        >
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img
                src="../assets/images/banner3.jpg"
                class="d-block w-100 h-25 rounded-5"
                alt="..."
              />
            </div>
            <div class="carousel-item">
              <img
                src="../assets/images/banner2.jpg"
                class="d-block w-100 h-25 rounded-5"
                alt="..."
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-lg">
      <section class="container py-4">
        <div class="mb-3 text-center">
          <h4 class="fw-bold mb-3"></h4>
          <div class="d-flex flex-wrap justify-content-center gap-2">
            <?php 
            // Danh sách các thể loại cơ bản, bạn có thể lấy động từ DB nếu cần
            $categories = ['Light Novel', 'Truyện Tranh', 'Văn Học', 'Khoa Học', 'Thiếu Nhi', 'Tâm Lý - Kỹ Năng'];
            ?>
            <a href="?search=<?= htmlspecialchars($search) ?>&sort=<?= htmlspecialchars($sort) ?>" 
               class="btn btn-outline-danger <?= empty($category_filter) ? 'active' : '' ?>"
               style="background-color: <?= empty($category_filter) ? '#f89ac9' : 'transparent' ?>; color: black">
              Tất cả
            </a>
            <?php foreach($categories as $cat): ?>
            <a href="?category=<?= urlencode($cat) ?>&search=<?= htmlspecialchars($search) ?>&sort=<?= htmlspecialchars($sort) ?>" 
               class="btn btn-outline-danger <?= $category_filter === $cat ? 'active' : '' ?>" 
               style="background-color: <?= $category_filter === $cat ? '#f89ac9' : 'transparent' ?>; color: black">
              <?= $cat ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="filter-bar py-3 px-3 rounded-3 mb-4 shadow-sm bg-white">
          <div class="d-flex flex-wrap gap-2 align-items-center">
            <button class="btn btn-danger">
              <i class="bi bi-funnel"></i> Bộ lọc
            </button>
            <button class="btn btn-light">
              <i class="bi bi-box"></i> Sách mới
            </button>
            <button class="btn btn-light">
              <i class="bi bi-tag"></i> Đang giảm giá
            </button>
            <button class="btn btn-light">
              <i class="bi bi-graph-up"></i> Bán chạy
            </button>

            <select class="form-select form-select-sm w-auto">
              <option>Năm xuất bản</option>
              <option>2025</option>
              <option>2024</option>
              <option>Trước 2020</option>
            </select>

            <select class="form-select form-select-sm w-auto">
              <option>Thể loại</option>
              <option>Fantasy</option>
              <option>Học đường</option>
              <option>Khoa học</option>
            </select>

            <select class="form-select form-select-sm w-auto">
              <option>Tác giả</option>
              <option>J.K. Rowling</option>
              <option>Nguyễn Nhật Ánh</option>
              <option>Haruki Murakami</option>
            </select>
          </div>
        </div>

        <div
          class="d-flex flex-wrap justify-content-between align-items-center mb-3"
        >
          <h6 class="text-muted m-0">Sắp xếp theo:</h6>
          <div class="d-flex flex-wrap gap-2">
            
            <?php 
            $base_url = "sanpham.php?search=" . htmlspecialchars($search) . "&category=" . htmlspecialchars($category_filter);
            $sort_options = [
                'popular' => 'Phổ biến',
                'newest' => 'Mới nhất',
                'price_asc' => 'Giá thấp → cao',
                'price_desc' => 'Giá cao → thấp'
            ];
            ?>
            <?php foreach($sort_options as $key => $label): ?>
            <a href="<?= $base_url . "&sort=" . $key ?>"
               class="btn btn-outline-primary <?= $sort === $key ? 'active' : '' ?>"
               style="background-color: <?= $sort === $key ? '#e8f0fe' : 'transparent' ?>; color: black">
              <?= $label ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="row g-4">
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): 
                // Tính toán giá sau giảm giá
                $price = $row['price'];
                $discount = $row['discount'];
                $final_price = $price * (1 - $discount / 100);
                // Tạo số bán ngẫu nhiên (vì không có cột 'sold' trong bảng books)
                $sold = mt_rand(50, 500); 
            ?>
            <div class="col-md-3 col-6 d-flex">
              <div class="product-card w-100" data-link="chitietsanpham.php?id=<?= $row['id'] ?>">
                <img
                  src="../assets/images/<?= htmlspecialchars($row['image']) ?>"
                  alt="<?= htmlspecialchars($row['title']) ?>"
                  class="img-fluid"
                />
                <div class="product-info">
                  <div>
                    <div class="product-name">
                      <?= htmlspecialchars($row['title']) ?>
                    </div>
                    <div>
                      <span class="price"><?= number_format($final_price, 0, ',', '.') ?> đ</span>
                      <?php if ($discount > 0): ?>
                      <span class="discount-badge">-<?= $discount ?>%</span>
                      <?php endif; ?>
                    </div>
                    <?php if ($discount > 0): ?>
                    <div class="old-price"><?= number_format($price, 0, ',', '.') ?> đ</div>
                    <?php endif; ?>
                    <div class="sold">Đã bán <?= $sold ?></div>
                  </div>
                  <a href="giohang.php?action=add&id=<?= $row['id'] ?>" class="add-cart-btn">Thêm giỏ hàng</a>
                </div>
              </div>
            </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    Không tìm thấy sản phẩm nào phù hợp với tiêu chí tìm kiếm/lọc của bạn.
                </div>
            </div>
          <?php endif; ?>
        </div>
      </section>
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
      // Chức năng chuyển hướng khi click vào card
      document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', (event) => {
          // Ngăn chặn chuyển hướng nếu click vào nút "Thêm giỏ hàng"
          if (event.target.classList.contains('add-cart-btn')) {
            event.preventDefault();
            // Có thể thêm logic AJAX/giỏ hàng ở đây
            alert('Đã thêm sản phẩm vào giỏ hàng! (Chức năng này cần được lập trình thêm)');
            return;
          }
          window.location.href = card.dataset.link;
        });
      });
    </script>
  </body>
</html>