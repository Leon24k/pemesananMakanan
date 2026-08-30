<?php 
require_once 'auth.php';
require_admin();
require_once 'koneksi.php';

$error_msg = "";

if (isset($_POST['tambah'])) {
    $nama   = trim($_POST['nama_menu'] ?? '');
    $jenis  = trim($_POST['jenis_menu'] ?? 'Makanan');
    $stok   = (int)($_POST['stok'] ?? 0);
    $harga  = (int)($_POST['harga'] ?? 0);
    
    if (empty($nama) || $harga <= 0) {
        $error_msg = "Nama menu dan harga yang valid wajib diisi!";
    } elseif ($stok < 0) {
        $error_msg = "Stok tidak boleh bernilai negatif!";
    } else {
        $nama_file_final = "";

        // Validasi File Upload jika ada gambar
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES['gambar']['tmp_name'];
            $file_name = $_FILES['gambar']['name'];
            $file_size = $_FILES['gambar']['size'];
            
            $allowed_ext  = ['jpg', 'jpeg', 'png', 'webp'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Cek ekstensi
            if (!in_array($file_ext, $allowed_ext)) {
                $error_msg = "Format gambar tidak didukung! Hanya JPG, JPEG, PNG, dan WEBP yang diizinkan.";
            } 
            // Cek ukuran file (Maks 3MB)
            elseif ($file_size > 3 * 1024 * 1024) {
                $error_msg = "Ukuran file gambar terlalu besar! Maksimal 3MB.";
            } 
            // Cek apakah benar-benar file gambar
            elseif (@getimagesize($file_tmp) === false) {
                $error_msg = "File yang diunggah bukan file gambar yang valid!";
            } else {
                $nama_file_final = uniqid('menu_', true) . '.' . $file_ext;
                $target_folder   = __DIR__ . '/upload/' . $nama_file_final;
                
                if (!move_uploaded_file($file_tmp, $target_folder)) {
                    $error_msg = "Gagal mengunggah file gambar ke server!";
                }
            }
        }

        if (empty($error_msg)) {
            // Gunakan Prepared Statement
            $stmt = mysqli_prepare($koneksi, "INSERT INTO produk (nama_menu, jenis_menu, stok, harga, gambar) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssiis", $nama, $jenis, $stok, $harga, $nama_file_final);
            $insert = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($insert) {
                echo "<script>
                        alert('Menu baru berhasil ditambahkan!');
                        window.location.href = 'daftar_menu.php';
                      </script>";
                exit;
            } else {
                $error_msg = "Terjadi kesalahan database saat menyimpan menu.";
            }
        }
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
    <link rel="stylesheet" type="text/css" href="index.css">

    <title>Tambah Menu Baru - Restoran Web</title>
  </head>
  <body class="bg-light">

  <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
        <a class="navbar-brand" href="admin.php"><strong>Resto</strong> Admin</a>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link mr-3" href="admin.php">HOME</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link mr-3" href="daftar_menu.php">DAFTAR MENU</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mr-3" href="pesanan.php">PESANAN</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-danger" href="logout.php">LOGOUT</a>
            </li>
          </ul>
        </div>
       </div> 
      </nav>
  <!-- Akhir Navbar -->
 
  <!-- Form Tambah Menu -->
  <div class="container my-5">
    <div class="card shadow-sm p-4 p-md-5 col-md-8 mx-auto bg-white rounded">
      <h3 class="text-center font-weight-bold mb-4">TAMBAH MENU BARU</h3>

      <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-triangle mr-2"></i> <?php echo htmlspecialchars($error_msg); ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>

      <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
          <label for="nama_menu" class="font-weight-bold">Nama Menu</label>
          <input type="text" class="form-control" id="nama_menu" name="nama_menu" placeholder="Contoh: Ayam Bakar Spesial" required value="<?php echo htmlspecialchars($_POST['nama_menu'] ?? ''); ?>">
        </div>
        <div class="form-group">
          <label class="font-weight-bold">Jenis Menu</label>
          <div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="jenis1" value="Makanan" name="jenis_menu" checked>
              <label class="form-check-label" for="jenis1">Makanan</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="jenis2" value="Minuman" name="jenis_menu" <?php echo (isset($_POST['jenis_menu']) && $_POST['jenis_menu'] === 'Minuman') ? 'checked' : ''; ?>>
              <label class="form-check-label" for="jenis2">Minuman</label>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="stok" class="font-weight-bold">Jumlah Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" placeholder="Contoh: 50" min="0" required value="<?php echo htmlspecialchars($_POST['stok'] ?? '0'); ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="harga" class="font-weight-bold">Harga Menu (Rp)</label>
            <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh: 15000" min="1" required value="<?php echo htmlspecialchars($_POST['harga'] ?? ''); ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="gambar" class="font-weight-bold">Foto Menu</label>
          <input type="file" class="form-control-file border p-1" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.webp">
          <small class="form-text text-muted">Format yang diperbolehkan: JPG, JPEG, PNG, WEBP. Maksimal 3MB.</small>
        </div>
        <div class="mt-4">
          <button type="submit" class="btn btn-primary" name="tambah"><i class="fas fa-save mr-1"></i> SIMPAN MENU</button>
          <a href="daftar_menu.php" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> KEMBALI</a>
        </div>
      </form>
    </div>
  </div>
  <!-- Akhir Form Tambah Menu -->

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>