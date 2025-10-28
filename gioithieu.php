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
      .list-unstyled i {
        color: #f355a4;
      }
    </style>
  </head>

  <body>
     <?php include 'navbar.php'; ?>

    <!-- content -->
    <section class="py-5" data-aos="fade-up">
      <div class="container">
        <div class="row align-items-center">
          <!-- Nội dung bên trái -->
          <div class="col-md-12">
            <h2 class="mb-4 fw-bold">
              Giới thiệu về <span style="color: #f355a4">PK</span> Store
            </h2>
            <p class="mb-4">
              PK Store là tổ chức sàn buôn bán sách/truyện để các khách hàng có
              thể mua những loại sách mình mong muốn một cách nhanh chóng và
              tiện lại mà không cần phải đi đâu xa! <br />
              <br />
              Với phương châm hoạt động “Tất cả vì Khách Hàng”, PK Store luôn
              không ngừng nỗ lực nâng cao chất lượng dịch vụ và sản phẩm, từ đó
              mang đến trải nghiệm mua sắm trọn vẹn cho Khách Hàng Việt Nam với
              dịch vụ giao hàng nhanh trong 2 tiếng và ngày hôm sau , cùng cam
              kết cung cấp hàng chính hãng với chính sách hoàn tiền 111% nếu
              phát hiện hàng giả, hàng nhái.
            </p>

            <div class="row">
              <div class="col-sm-6 mb-3">
                <h5 class="fw-bold" style="color: #f355a4">Mục tiêu</h5>
                <ul class="list-unstyled">
                  <li>
                    <i class="bi bi-check-circle me-2"></i>Cung cấp sách chất
                    lượng
                  </li>
                  <li>
                    <i class="bi bi-check-circle me-2"></i>Giá hợp lý, dễ tiếp
                    cận
                  </li>
                  <li>
                    <i class="bi bi-check-circle me-2"></i>Lan tỏa tri thức &
                    niềm đam mê đọc
                  </li>
                </ul>
              </div>
              <div class="col-sm-6 mb-3">
                <h5 class="fw-bold" style="color: #f355a4">Định hướng</h5>
                <ul class="list-unstyled">
                  <li>
                    <i class="bi bi-bullseye me-2"></i>Trở thành nền tảng sách
                    uy tín
                  </li>
                  <li>
                    <i class="bi bi-bullseye me-2"></i>Dễ dàng tìm tri thức &
                    cảm hứng
                  </li>
                  <li>
                    <i class="bi bi-bullseye me-2"></i>Gắn kết cộng đồng yêu
                    sách
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Ảnh bên phải -->
          <div class="col-md-12 text-center">
            <img
              src="../assets/images/nha-sach-lon-nhat-sai-gon-banner.jpg"
              class="img-fluid rounded shadow"
              alt="About Company"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- đánh giá -->
    <div class="container my-5" data-aos="fade-up">
      <h2 class="mb-5 text-center">Đánh giá của khách hàng</h2>
      <div class="row mt-4">
        <!-- Cột bên trái: số điểm + sao -->
        <div class="col-md-4 text-center">
          <div style="font-size: 48px; font-weight: bold">4,7</div>
          <div>⭐⭐⭐⭐☆</div>
          <p>2.994</p>
        </div>

        <!-- Cột bên phải: progress -->
        <div class="col-md-8" data-aos="fade-up">
          <div class="d-flex align-items-center mb-2">
            <span class="me-2">5</span>
            <div class="progress flex-grow-1">
              <div class="progress-bar bg-primary" style="width: 70%"></div>
            </div>
          </div>
          <div class="d-flex align-items-center mb-2">
            <span class="me-2">4</span>
            <div class="progress flex-grow-1">
              <div class="progress-bar bg-primary" style="width: 15%"></div>
            </div>
          </div>
          <div class="d-flex align-items-center mb-2">
            <span class="me-2">3</span>
            <div class="progress flex-grow-1">
              <div class="progress-bar bg-primary" style="width: 7%"></div>
            </div>
          </div>
          <div class="d-flex align-items-center mb-2">
            <span class="me-2">2</span>
            <div class="progress flex-grow-1">
              <div class="progress-bar bg-primary" style="width: 5%"></div>
            </div>
          </div>
          <div class="d-flex align-items-center mb-2">
            <span class="me-2">1</span>
            <div class="progress flex-grow-1">
              <div class="progress-bar bg-primary" style="width: 3%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- commemt -->
    <div class="container-fluid" data-aos="fade-up">
      <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10 shadow">
          <div class="mb-3">
            <label
              for="exampleFormControlInput1"
              class="form-label fw-bold pt-2"
              >Email</label
            >
            <input
              type="email"
              class="form-control"
              id="exampleFormControlInput1"
              placeholder="Nhập email của bạn"
            />
          </div>
          <div class="mb-2">
            <label for="exampleFormControlTextarea1" class="form-label fw-bold"
              >Thêm đánh giá</label
            >
            <textarea
              class="form-control"
              id="exampleFormControlTextarea1"
              rows="3"
            ></textarea>
            <a href="" class="btn mt-2 px-3" style="background-color: #f355a4"
              >Gửi</a
            >
          </div>
        </div>
        <div class="col-lg-1 p-0"></div>
      </div>
    </div>

    <!-- Q&A -->
    <section class="py-5" data-aos="fade-up">
      <div class="container">
        <h2 class="mb-4 text-center">Những câu hỏi thường gặp</h2>

        <div class="accordion" id="faqAccordion">
          <!-- Item 1 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button
                class="accordion-button bg-white fw-bold text-dark"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapseOne"
                aria-expanded="true"
                aria-controls="collapseOne"
              >
                1. Tôi cần tạo tài khoản để mua sách không?
              </button>
            </h2>
            <div
              id="collapseOne"
              class="accordion-collapse collapse show"
              aria-labelledby="headingOne"
              data-bs-parent="#faqAccordion"
            >
              <div class="accordion-body bg-secondary-subtle">
                Bạn càn phải tạo tài khoản bạn sẽ dễ dàng theo dõi đơn hàng, lưu
                lịch sử mua và nhận ưu đãi đặc biệt.
              </div>
            </div>
          </div>

          <!-- Item 2 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button
                class="accordion-button collapsed bg-white fw-bold text-dark"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapseTwo"
                aria-expanded="false"
                aria-controls="collapseTwo"
              >
                2. Thời gian giao hàng mất bao lâu?
              </button>
            </h2>
            <div
              id="collapseTwo"
              class="accordion-collapse collapse bg-secondary-subtle"
              aria-labelledby="headingTwo"
              data-bs-parent="#faqAccordion"
            >
              <div class="accordion-body">
                Thông thường đơn hàng sẽ được giao trong vòng 2–5 ngày làm việc,
                tùy địa chỉ và phương thức vận chuyển bạn chọn.
              </div>
            </div>
          </div>

          <!-- Item 3 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button
                class="accordion-button collapsed bg-white fw-bold text-dark"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapseThree"
                aria-expanded="false"
                aria-controls="collapseThree"
              >
                3. Tôi có thể đổi hoặc trả sách không?
              </button>
            </h2>
            <div
              id="collapseThree"
              class="accordion-collapse collapse bg-secondary-subtle"
              aria-labelledby="headingThree"
              data-bs-parent="#faqAccordion"
            >
              <div class="accordion-body">
                Có. Bạn có thể đổi/trả sách trong vòng 7 ngày kể từ khi nhận
                hàng nếu sách bị lỗi in ấn, hư hỏng hoặc giao sai.
              </div>
            </div>
          </div>

          <!-- Item 4 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
              <button
                class="accordion-button collapsed bg-white fw-bold text-dark"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapseFour"
                aria-expanded="false"
                aria-controls="collapseFour"
              >
                4. Làm sao để liên hệ hỗ trợ khi có vấn đề với đơn hàng?
              </button>
            </h2>
            <div
              id="collapseFour"
              class="accordion-collapse collapse bg-secondary-subtle"
              aria-labelledby="headingFour"
              data-bs-parent="#faqAccordion"
            >
              <div class="accordion-body">
                Bạn có thể liên hệ qua mục ** Liên hệ ** trên website, gọi
                hotline, hoặc chat trực tiếp với nhân viên tư vấn.
              </div>
            </div>
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
