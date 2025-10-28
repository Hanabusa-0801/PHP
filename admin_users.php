<?php
require_once 'assets/php/db_connect.php';

// Xử lý tìm kiếm
$search = $_GET['search'] ?? '';

// Lấy dữ liệu users
$sql = "SELECT * FROM users";
if ($search) {
  // Thêm điều kiện tìm kiếm theo username HOẶC email
  // Lưu ý: Đảm bảo đã thiết lập kết nối $conn và nó là đối tượng mysqli
  $escaped_search = $conn->real_escape_string($search);
  $sql .= " WHERE username LIKE '%$escaped_search%' OR email LIKE '%$escaped_search%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý người dùng | PK Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff;
      /* Nền trắng */
      color: #333;
    }

    .sidebar {
      height: 100vh;
      background-color: #f355a4;
      /* Màu chủ đạo */
      color: white;
      padding-top: 20px;
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
      background-color: #ff77c7;
      /* Màu hover/active nhạt hơn */
    }

    .main-content {
      padding: 30px;
    }

    .btn-pink {
      background-color: #f355a4;
      color: white;
      border: none;
    }

    .btn-pink:hover {
      background-color: #d9448d;
      color: white;
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
        <a href="admin_users.php" class="active"><i class="bi bi-people me-2"></i> Users</a>
        <a href="admin_products.php"><i class="bi bi-box-seam me-2"></i> Products</a>
        <a href="admin_orders.php"><i class="bi bi-receipt me-2"></i> Orders</a>
        <a href="dangxuat.php" class="mt-auto text-danger" style="color: #ffcccc !important;"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a>
      </div>

      <div class="col-lg-10 col-md-9 main-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3>Quản lý người dùng</h3>
          <button class="btn btn-pink" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus me-1"></i> Thêm người dùng
          </button>
        </div>

        <form class="d-flex mb-4" method="get">
          <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control me-2" placeholder="Tìm theo tên đăng nhập hoặc email">
          <button class="btn btn-pink"><i class="bi bi-search"></i> Tìm kiếm</button>
        </form>

        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['id'] ?></td>
                  <td><?= htmlspecialchars($row['username']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['role']) ?></td>
                  <td><?= $row['created_at'] ?></td>
                  <td>
                    <button class="btn btn-sm btn-warning"
                      data-bs-toggle="modal"
                      data-bs-target="#editUserModal"
                      data-id="<?= $row['id'] ?>"
                      data-username="<?= htmlspecialchars($row['username']) ?>"
                      data-email="<?= htmlspecialchars($row['email']) ?>"
                      data-role="<?= htmlspecialchars($row['role']) ?>">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <a href="../assets/php/delete_user.php?id=<?= $row['id'] ?>"
                      class="btn btn-sm btn-danger"
                      onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
              <?php if ($result->num_rows == 0): ?>
                <tr>
                  <td colspan="6" class="text-center">Không tìm thấy người dùng nào.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="../assets/php/add_user.php" method="POST">
          <div class="modal-header">
            <h5 class="modal-title">Thêm người dùng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label>Tên đăng nhập</label>
              <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
              <label>Mật khẩu</label>
              <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
              <label>Vai trò</label>
              <select class="form-select" name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-pink">Thêm</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="../assets/php/edit_user.php" method="POST">
          <div class="modal-header">
            <h5 class="modal-title">Chỉnh sửa người dùng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="edit-id">
            <div class="mb-3">
              <label>Tên đăng nhập</label>
              <input type="text" class="form-control" name="username" id="edit-username" required>
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" class="form-control" name="email" id="edit-email" required>
            </div>
            <div class="mb-3">
              <label>Vai trò</label>
              <select class="form-select" name="role" id="edit-role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-pink">Lưu thay đổi</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Gán dữ liệu vào modal sửa
    const editModal = document.getElementById('editUserModal');
    editModal.addEventListener('show.bs.modal', event => {
      const button = event.relatedTarget;
      document.getElementById('edit-id').value = button.getAttribute('data-id');
      document.getElementById('edit-username').value = button.getAttribute('data-username');
      document.getElementById('edit-email').value = button.getAttribute('data-email');
      document.getElementById('edit-role').value = button.getAttribute('data-role');
    });
  </script>
</body>

</html>