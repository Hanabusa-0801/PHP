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
  </head>

  <body>
       <?php include 'navbar.php'; ?>

    <!-- content -->
    <section id="contact" class="py-5 bg-light">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-md-10">
            <h2 class="text-center mb-5 fw-bold">
              Liên hệ với chúng tôi <br />
              <span class="fw-normal fs-6"
                >Chúng tôi sẽ trả lời mọi câu hỏi của bạn về dịch vụ bán hàng
                trực tuyến, quyền lợi hoặc hợp tác ngay tại đây!</span
              >
            </h2>

            <div class="row g-4 align-items-stretch">
              <!-- Form liên hệ -->
              <div class="col-md-6 slide-item" data-dir="left">
                <div class="p-4 bg-white shadow-sm rounded h-100">
                  <form>
                    <div class="mb-3">
                      <label for="name" class="form-label">Họ và Tên</label>
                      <input
                        type="text"
                        class="form-control"
                        id="name"
                        placeholder="Nhập tên của bạn"
                      />
                    </div>
                    <div class="mb-3">
                      <label for="email" class="form-label">Email</label>
                      <input
                        type="email"
                        class="form-control"
                        id="email"
                        placeholder="Nhập email của bạn"
                      />
                    </div>
                    <div class="mb-3">
                      <label for="message" class="form-label">Tin nhắn</label>
                      <textarea
                        class="form-control"
                        id="message"
                        rows="5"
                        placeholder="Nhập nội dung liên hệ..."
                      ></textarea>
                    </div>
                    <button
                      type="submit"
                      class="btn px-4"
                      style="background-color: #f355a4"
                    >
                      Gửi Tin Nhắn
                    </button>
                  </form>
                </div>
              </div>

              <!-- Bản đồ -->
              <div class="col-md-6">
                <div class="h-100">
                  <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4727911533982!2d106.63191911018389!3d10.775054859175565!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752ea144839ef1%3A0x798819bdcd0522b0!2zQ2FvIMSQ4bqzbmcgQ8O0bmcgTmdo4buHIFRow7RuZyBUaW4gVFAuSENN!5e0!3m2!1svi!2s!4v1759538853788!5m2!1svi!2s"
                    width="100%"
                    height="450"
                    style="border: 0"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                  >
                  </iframe>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row text-center mt-5">
          <div class="col-md-3 col-6 mb-4">
            <i class="bi bi-telephone-fill fs-2"></i>
            <p class="mt-2 mb-0 fs-5">+84 123-456-789</p>
          </div>
          <div class="col-md-3 col-6 mb-4">
            <i class="bi bi-envelope-fill fs-2"></i>
            <p class="mt-2 mb-0 fs-5">PKStore@gmail.com</p>
          </div>
          <div class="col-md-3 col-6 mb-4">
            <i class="bi bi-instagram fs-2"></i>
            <p class="mt-2 mb-0 fs-5">@PKStore</p>
          </div>
          <div class="col-md-3 col-6 mb-4">
            <i class="bi bi-facebook fs-2"></i>
            <p class="mt-2 mb-0 fs-5">PK Store</p>
          </div>
        </div>
      </div>
    </section>

    <!-- nhận thông tin -->
    <section id="subscribe" class="py-5 mb-2">
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <!-- Cột trái -->
          <div class="col-md-5 mb-4 mb-md-0">
            <h2 class="fw-bold mb-3">Đăng Ký Nhận Tin Mới</h2>
            <hr class="border-2 opacity-100" style="width: 80px" />
          </div>

          <!-- Cột phải -->
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
          <!-- Company Info -->
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
  </body>
</html>
