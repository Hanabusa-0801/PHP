<?php
session_start();
// Thêm kết nối CSDL và logic lấy sản phẩm
require_once 'assets/php/db_connect.php'; 

/**
 * Hàm lấy số lượng sản phẩm ngẫu nhiên
 * @param mysqli $conn Đối tượng kết nối CSDL
 * @param int $limit Số lượng sản phẩm muốn lấy
 * @return array Mảng chứa các sản phẩm
 */
function fetchRandomProducts($conn, $limit = 4) {
    // Chỉ lấy sản phẩm đang visible (visible = 1)
    $sql = "SELECT * FROM books WHERE visible = 1 ORDER BY RAND() LIMIT $limit";
    $result = $conn->query($sql);
    $products = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

// Lấy 4 sản phẩm ngẫu nhiên cho mục Light Novel
$light_novel_products = fetchRandomProducts($conn, 4);

// Lấy 4 sản phẩm ngẫu nhiên cho mục Manwa
$manwa_products = fetchRandomProducts($conn, 4);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PK Store</title>
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
      /* Style cho card sản phẩm - Lấy từ sanpham.php */
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

    <div class="container-md d-flex justify-content-center">
      <div
        id="carouselExampleSlidesOnly"
        class="carousel slide col-lg-12 rounded-5"
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
    </div>

    <section class="py-3 bg-light text-center">
      <div class="container py-4" data-aos="fade-up">
        <h3 class="mb-4">Light Novel (Sản phẩm nổi bật ngẫu nhiên)</h3>
        <div class="row g-4">
          <?php foreach ($light_novel_products as $row):
            $price = $row['price'];
            $discount = $row['discount'];
            $final_price = $price * (1 - $discount / 100);
            $sold = mt_rand(50, 500); // Lấy số bán ngẫu nhiên vì không có cột 'sold'
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
                  <a href="#" class="add-cart-btn">Thêm giỏ hàng</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          <?php if (empty($light_novel_products)): ?>
            <div class="col-12"><p>Không tìm thấy sản phẩm nào để hiển thị.</p></div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card p-4">
            <div class="card-body text-center">
              <h2>Trích dẫn nổi bật</h2>
              <blockquote data-aos="fade-up">
                <div id="carouselExample" class="carousel slide">
                  <div class="carousel-inner">
                    <div class="carousel-item active" style="min-height: 200px;">
                      <img
                        width="15%"
                        src="../assets/images/author-avt1.jpg"
                        class="d-block mx-auto py-3 w-25 h-25 rounded-circle"
                        alt="..."
                      />
                      <q
                        >“A human being is part of the whole, called by us
                        ‘Universe,’ a part limited in time and space. He
                        experiences himself, his thoughts and feelings as
                        something separated from the rest—a kind of optical
                        delusion of his consciousness. This delusion is a kind
                        of prison for us, restricting us to our personal desires
                        and to affection for a few persons nearest us. Our task
                        must be to free ourselves from this prison by widening
                        our circle of compassion to embrace all living creatures
                        and the whole of nature in its beauty.”</q
                      >
                      <div class="author-name">Albert Einstein</div>
                      </div>
                    <div class="carousel-item" style="min-height: 200px;">
                      <img
                        width="15%"
                        src="../assets/images/author-avt2.jpg"
                        class="d-block mx-auto py-3 w-25 h-25 rounded-circle"
                        alt="..."
                      />
                      <q
                        >“To live is the rarest thing in the world. Most people
                        exist, that is all. To live fully, truly, deeply means
                        embracing risks, accepting failures, and recognizing
                        beauty in both joy and sorrow. Life is not meant to be
                        measured merely by the number of breaths we take, but by
                        the moments that take our breath away.”</q
                      >
                      <div class="author-name">Oscar Wilde</div>
                      </div>
                    <div class="carousel-item">
                      <img
                        width="15%"
                        src="../assets/images/author-avt3.jpg"
                        class="d-block w-25 h-25 rounded-circle mx-auto py-3"
                        alt="..."
                      />
                      <q
                        >“You have brains in your head. You have feet in your
                        shoes. You can steer yourself in any direction you
                        choose. You're on your own. And you know what you know.
                        And YOU are the one who’ll decide where to go. Life may
                        test you with detours, bumps, and unexpected turns, but
                        remember that the journey itself is just as important as
                        the destination.”</q
                      >
                      <div class="author-name">Dr. Seuss</div>
                      </div>
                  </div>
                  <button
                    class="carousel-control-prev"
                    type="button"
                    data-bs-target="#carouselExample"
                    data-bs-slide="prev"
                  >
                    <span
                      class="carousel-control-prev-icon"
                      aria-hidden="true"
                    ></span>
                    <span class="visually-hidden">Previous</span>
                  </button>
                  <button
                    class="carousel-control-next"
                    type="button"
                    data-bs-target="#carouselExample"
                    data-bs-slide="next"
                  >
                    <span
                      class="carousel-control-next-icon"
                      aria-hidden="true"
                    ></span>
                    <span class="visually-hidden">Next</span>
                  </button>
                </div>
              </blockquote>
            </div>
          </div>
        </div>
      </div>
    </div>

    <section class="py-3 bg-light text-center">
      <div class="container py-4">
        <h3 class="mb-4">Manwa (Sản phẩm ngẫu nhiên khác)</h3>
        <div class="row g-4">
          <?php foreach ($manwa_products as $row):
            $price = $row['price'];
            $discount = $row['discount'];
            $final_price = $price * (1 - $discount / 100);
            $sold = mt_rand(50, 500); // Lấy số bán ngẫu nhiên vì không có cột 'sold'
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
          <?php endforeach; ?>
          <?php if (empty($manwa_products)): ?>
            <div class="col-12"><p>Không tìm thấy sản phẩm nào để hiển thị.</p></div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <section class="portfolio">
      <div class="container py-5">
        <div class="row g-3 align-items-center">
          <div class="col-md-5 d-flex justify-content-center">
            <img src="../assets/images/tacgia1.jpg" class="img-fluid w-75" />
          </div>
          <div class="col-md-7">
            <p>
              Her laugh is free. Her pose is soft—elegant, stylish, unique. Each
              frame crafted and captured through artistic vision and modern
              simplicity. She’s herself, unfiltered, bold, confident.
            </p>
            <p>
              Discover timeless beauty through our lens. Whether it’s fashion or
              lifestyle, we’re here to help you find yourself through
              professional photography and authentic storytelling.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="footer-gallery text-center py-4 text-white">
      <div class="container">
        <h3 class="mb-4">Danh mục truyện nổi bật | Bán chạy</h3>
        <div class="row g-4 justify-content-center">
          <div class="col-md-3">
            <img
              src="../assets/images/ảnh truyện/sach22.jpg"
              class="img-fluid container-sm"
              alt=""
            />
          </div>
          <div class="col-md-3">
            <img
              src="../assets/images/ảnh truyện/sach20.jpg"
              class="img-fluid container-sm"
              alt=""
            />
          </div>
          <div class="col-md-3">
            <img
              src="../assets/images/ảnh truyện/sach24.jpg"
              class="img-fluid container-sm"
              alt=""
            />
          </div>
        </div>
      </div>
      <hr />
    </section>

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
      // Cập nhật script để ngăn chặn click vào nút Thêm giỏ hàng
      document.querySelectorAll(".product-card").forEach((card) => {
        card.addEventListener("click", (event) => {
          // Ngăn chặn chuyển hướng nếu click vào nút "Thêm giỏ hàng"
          if (event.target.classList.contains('add-cart-btn')) {
            event.preventDefault();
            alert('Đã thêm sản phẩm vào giỏ hàng! (Chức năng này cần được lập trình thêm)');
            return;
          }
          window.location.href = card.dataset.link;
        });
      });
    </script>
  </body>
</html>