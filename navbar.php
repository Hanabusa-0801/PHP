<?php 
// Khởi tạo session nếu chưa có (đảm bảo file nào include navbar cũng chạy)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Nếu cần lấy thông tin Giỏ hàng, bạn cần đảm bảo biến $_SESSION['cart'] đã được khởi tạo
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<div class="container-fluid top-bar d-flex justify-content-between bg-light border-bottom">
    <div class="icon-navbar mt-md-2 my-3">
        <a href="#"><i class="bi bi-facebook"></i></a>
        <a href="#"><i class="bi bi-twitter"></i></a>
        <a href="#"><i class="bi bi-youtube"></i></a>
        <a href="#"><i class="bi bi-behance"></i></a>
    </div>

    <div class="user-links text-dark pt-2 pb-2 d-flex flex-wrap ms-auto">
        <?php if (isset($_SESSION['username'])): ?>
        <div class="dropdown m-2">
            <a
                href="#"
                class="nut dropdown-toggle fw-bold text-white d-none d-md-block"
                id="userDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >
                <i class="bi bi-person-fill"></i> <?= htmlspecialchars($_SESSION['username']) ?>
            </a>
            <ul class="dropdown-menu" style="z-index: 99999999;" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="lichsu.php">Lịch sử mua hàng</a></li>
                <li><a class="dropdown-item" href="dangxuat.php">Đăng xuất</a></li>
            </ul>
        </div>
        <?php else: ?>
        <a href="dangnhap.php" type="button" class="nut m-2 d-none d-md-block">
            <i class="bi bi-person-fill "></i> Tài khoản
        </a>
        <?php endif; ?>

        <a href="giohang.php" type="button" class="nut m-2 d-none d-md-block">
            <i class="bi bi-bag-fill"></i> Giỏ hàng (<?= count($_SESSION['cart']) ?>)
        </a>
    </div>
</div>

<nav class="navbar navbar-expand-lg bg-light sticky-top shadow-sm">
    <div class="container-lg">
        <a class="navbar-brand fs-2 fw-bold" href="home.php">
            <span style="color: #f355a4">PK</span> Store
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">TRANG CHỦ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sanpham.php">SẢN PHẨM</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="tacgia.php">TÁC GIẢ</a>
                </li>

                <li class="nav-item d-md-none">
                    <?php if (isset($_SESSION['username'])): ?>
                    <a class="nav-link fw-bold" href="#">
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </a>
                    <?php else: ?>
                    <a class="nav-link fw-bold" href="dangnhap.php">TÀI KHOẢN</a>
                    <?php endif; ?>
                </li>

                <li class="nav-item d-md-none">
                    <a class="nav-link fw-bold" href="giohang.php">GIỎ HÀNG</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        THÊM
                    </a>
                    <ul class="dropdown-menu bg-light">
                        <li><a class="dropdown-item" href="lienhe.php">Liên hệ</a></li>
                        <li><a class="dropdown-item" href="gioithieu.php">Giới thiệu</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li>
                            <a
                                class="dropdown-item fw-semibold"
                                href="lichsu.php"
                                style="color: #f355a4;"
                            >
                                Lịch sử mua hàng
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <form class="d-flex ms-lg-3 mt-2 mt-lg-0" role="search" method="GET" action="sanpham.php">
                <input
                    class="form-control"
                    type="search"
                    placeholder="Tìm kiếm sách..."
                    aria-label="Search"
                    name="search"
                    style="border-radius: 20px; border-color: #f355a4"
                />
                <button
                    class="btn ms-2"
                    type="submit"
                    style="background-color: #f355a4; color: white; border-radius: 20px;"
                >
                    🔍
                </button>
            </form>
        </div>
    </div>
</nav>