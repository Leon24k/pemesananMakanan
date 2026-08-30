<?php 
require_once 'koneksi.php';
require_once 'auth.php';

// Jika sudah login, redirect sesuai role
if (isset($_SESSION['login_user']) && isset($_SESSION['user_status'])) {
    if ($_SESSION['user_status'] === 'admin') {
        header('Location: admin.php');
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
}

$error_msg = "";

if (isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error_msg = "Silahkan isi username dan password!";
    } else {
        // Gunakan Prepared Statement untuk mencegah SQL Injection
        $stmt = mysqli_prepare($koneksi, "SELECT id_user, username, password, nama_lengkap, status FROM user WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $login_success = false;

            // 1. Cek dengan password_verify() (Bcrypt)
            if (password_verify($password, $user_data['password'])) {
                $login_success = true;
            } 
            // 2. Fallback untuk akun lama plain text & auto-rehash
            elseif ($user_data['password'] === $password) {
                $login_success = true;
                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = mysqli_prepare($koneksi, "UPDATE user SET password = ? WHERE id_user = ?");
                mysqli_stmt_bind_param($update_stmt, "si", $new_hash, $user_data['id_user']);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
            }

            if ($login_success) {
                $_SESSION['login_user']  = $user_data['username'];
                $_SESSION['id_user']     = $user_data['id_user'];
                $_SESSION['nama_lengkap'] = $user_data['nama_lengkap'];
                $_SESSION['user_status'] = $user_data['status'];

                if ($user_data['status'] === 'admin') {
                    header('Location: admin.php');
                    exit;
                } else {
                    header('Location: index.php');
                    exit;
                }
            } else {
                $error_msg = "Username atau password salah!";
            }
        } else {
            $error_msg = "Username atau password salah!";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!doctype html>
<html lang="id">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">

    <title>Halaman Login - Restoran Web</title>
  </head>
  <body>
  <!-- Form Login -->
    <div class="container">
      <h4 class="text-center font-weight-bold">FORM LOGIN</h4>
      <hr>

      <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-circle mr-2"></i> <?php echo htmlspecialchars($error_msg); ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-user"></i></div>
            </div>
            <input type="text" class="form-control" id="username" placeholder="Masukkan Username" name="username" required autocomplete="username">
          </div>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-unlock-alt"></i></div>
            </div>
            <input type="password" class="form-control" id="password" placeholder="Masukkan Password" name="password" required autocomplete="current-password">
          </div>
        </div>
        <div class="mb-3">
          <small><a href="register.php" class="text-dark">Belum Punya Akun? <strong>Daftar di sini!</strong></a></small>
        </div>
        <button type="submit" name="submit" class="btn btn-primary btn-block">LOGIN</button>
        <button type="reset" class="btn btn-danger btn-block">RESET</button>
      </form>
    </div>
  <!-- Akhir Form Login -->

    <!-- Optional JavaScripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>
