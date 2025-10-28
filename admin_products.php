<?php
require_once "assets/php/db_connect.php";

// Xử lý tìm kiếm
$search = $_GET['search'] ?? '';

// Xử lý xóa sản phẩm
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $result = $conn->query("SELECT image FROM books WHERE id = $id");
  $row = $result->fetch_assoc();
  if ($row && file_exists("../assets/images/" . $row['image'])) {
    unlink("../assets/images/" . $row['image']);
  }
  $conn->query("DELETE FROM books WHERE id = $id");
  header("Location: admin_products.php");
  exit;
}

// Xử lý thêm / sửa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'] ?? '';
  $title = $_POST['title'];
  $price = $_POST['price'];
  $discount = $_POST['discount'];
  $category = $_POST['category'];
  $visible = isset($_POST['visible']) ? 1 : 0;

  // Ảnh
  $image = $_FILES['image']['name'] ?? '';
  $upload_dir = "../assets/images/";
  $image_path = '';

  if ($image) {
    move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image);
    $image_path = $image;
  } elseif (!empty($_POST['old_image'])) {
    $image_path = $_POST['old_image'];
  }

  if ($id) {
    // Cập nhật
    $stmt = $conn->prepare("UPDATE books SET title=?, price=?, discount=?, category=?, image=?, visible=? WHERE id=?");
    $stmt->bind_param("sdsssii", $title, $price, $discount, $category, $image_path, $visible, $id);
    $stmt->execute();
  } else {
    // Thêm mới
    $stmt = $conn->prepare("INSERT INTO books (title, price, discount, category, image, visible) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsssi", $title, $price, $discount, $category, $image_path, $visible);
    $stmt->execute();
  }

  header("Location: admin_products.php");
  exit;
}

// Lấy danh sách sản phẩm
$sql = "SELECT * FROM books WHERE title LIKE '%$search%' OR category LIKE '%$search%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Quản lý sản phẩm | PK Admin</title>
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

    table img {
      width: 60px;
      border-radius: 8px;
    }

    table thead {
      background-color: #ffe4f2;
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
        <a href="admin_products.php" class="active"><i class="bi bi-box-seam me-2"></i> Products</a>
        <a href="admin_orders.php"><i class="bi bi-receipt me-2"></i> Orders</a>
        <a href="dangxuat.php" class="mt-auto text-danger" style="color: #ffcccc !important;"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a>
      </div>

      <div class="col-lg-10 col-md-9 main-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3>Quản lý sản phẩm</h3>
          <button class="btn btn-pink" data-bs-toggle="modal" data-bs-target="#productModal"><i class="bi bi-plus-lg me-1"></i> Thêm sản phẩm</button>
        </div>

        <form class="d-flex mb-3" method="get">
          <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control me-2" placeholder="Tìm theo tên hoặc thể loại">
          <button class="btn btn-pink"><i class="bi bi-search"></i> Tìm kiếm</button>
        </form>

        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center">
            <thead>
              <tr>
                <th>ID</th>
                <th>Ảnh</th>
                <th>Tiêu đề</th>
                <th>Giá</th>
                <th>Giảm giá</th>
                <th>Thể loại</th>
                <th>Hiển thị</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['id'] ?></td>
                  <td><img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt=""></td>
                  <td class="text-start"><?= htmlspecialchars($row['title']) ?></td>
                  <td><?= number_format($row['price'], 0, ',', '.') ?>đ</td>
                  <td><?= htmlspecialchars($row['discount']) ?>%</td>
                  <td><?= htmlspecialchars($row['category']) ?></td>
                  <td><input type="checkbox" <?= $row['visible'] ? 'checked' : '' ?> disabled></td>
                  <td>
                    <button class="btn btn-sm btn-warning"
                      data-bs-toggle="modal"
                      data-bs-target="#productModal"
                      data-id="<?= $row['id'] ?>"
                      data-title="<?= htmlspecialchars($row['title']) ?>"
                      data-price="<?= $row['price'] ?>"
                      data-discount="<?= $row['discount'] ?>"
                      data-category="<?= htmlspecialchars($row['category']) ?>"
                      data-image="<?= htmlspecialchars($row['image']) ?>"
                      data-visible="<?= $row['visible'] ?>"><i class="bi bi-pencil"></i></button>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa sản phẩm này?')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title">Thêm / Sửa sản phẩm</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="product_id">
            <input type="hidden" name="old_image" id="old_image">

            <div class="mb-2">
              <label class="form-label">Tên sách</label>
              <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Giá</label>
              <input type="number" name="price" id="price" class="form-control" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Giảm giá (%)</label>
              <input type="number" name="discount" id="discount" class="form-control">
            </div>
            <div class="mb-2">
              <label class="form-label">Thể loại</label>
              <input type="text" name="category" id="category" class="form-control">
            </div>
            <div class="mb-2">
              <label class="form-label">Ảnh sản phẩm</label>
              <input type="file" name="image" class="form-control">
              <img id="preview" src="" class="mt-2" style="width:100px; display:none;">
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="visible" id="visible">
              <label class="form-check-label">Hiển thị sản phẩm</label>
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
    const modal = document.getElementById('productModal');
    modal.addEventListener('show.bs.modal', event => {
      const button = event.relatedTarget;
      const id = button?.getAttribute('data-id') || '';
      document.getElementById('product_id').value = id;
      document.getElementById('title').value = button?.getAttribute('data-title') || '';
      document.getElementById('price').value = button?.getAttribute('data-price') || '';
      document.getElementById('discount').value = button?.getAttribute('data-discount') || '';
      document.getElementById('category').value = button?.getAttribute('data-category') || '';
      document.getElementById('visible').checked = button?.getAttribute('data-visible') == 1;
      document.getElementById('old_image').value = button?.getAttribute('data-image') || '';
      const preview = document.getElementById('preview');
      if (button?.getAttribute('data-image')) {
        preview.src = "../assets/images/" + button.getAttribute('data-image');
        preview.style.display = 'block';
      } else {
        preview.style.display = 'none';
      }
    });
  </script>
</body>

</html>