<?php 
require_once 'auth.php';
require_admin();
require_once 'koneksi.php';

$id_menu = (int)($_GET['id_menu'] ?? 0);

if ($id_menu <= 0) {
    header("Location: daftar_menu.php");
    exit;
}

$stmt = mysqli_prepare($koneksi, "SELECT * FROM produk WHERE id_menu = ?");
mysqli_stmt_bind_param($stmt, "i", $id_menu);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$menu = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$menu) {
    echo "<script>alert('Menu tidak ditemukan!'); window.location.href='daftar_menu.php';</script>";
    exit;
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

    <title>Edit Menu - Restoran Web</title>
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
 
  <!-- Form Edit Menu -->
  <div class="container my-5">
    <div class="card shadow-sm p-4 p-md-5 col-md-8 mx-auto bg-white rounded">
      <h3 class="text-center font-weight-bold mb-4">EDIT DATA MENU</h3>
      
      <form method="POST" action="edit.php" enctype="multipart/form-data">
        <input type="hidden" name="id_menu" value="<?php echo htmlspecialchars($menu['id_menu']); ?>">
        <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($menu['gambar']); ?>">

        <div class="form-group">
          <label for="nama_menu" class="font-weight-bold">Nama Menu</label>
          <input type="text" class="form-control" id="nama_menu" name="nama_menu" value="<?php echo htmlspecialchars($menu['nama_menu']); ?>" required>
        </div>

        <div class="form-group">
          <label class="font-weight-bold">Jenis Menu</label>
          <div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="jenis1" name="jenis_menu" value="Makanan" <?php echo ($menu['jenis_menu'] === 'Makanan') ? 'checked' : ''; ?>>
              <label class="form-check-label" for="jenis1">Makanan</label> 
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="jenis2" name="jenis_menu" value="Minuman" <?php echo ($menu['jenis_menu'] === 'Minuman') ? 'checked' : ''; ?>>
              <label class="form-check-label" for="jenis2">Minuman</label>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="stok" class="font-weight-bold">Jumlah Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" min="0" value="<?php echo htmlspecialchars($menu['stok']); ?>" required>
          </div>
          <div class="form-group col-md-6">
            <label for="harga" class="font-weight-bold">Harga Menu (Rp)</label>
            <input type="number" class="form-control" id="harga" name="harga" min="1" value="<?php echo htmlspecialchars($menu['harga']); ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label class="font-weight-bold">Foto Saat Ini</label><br>
          <?php if (!empty($menu['gambar']) && file_exists('upload/' . $menu['gambar'])): ?>
            <img src="upload/<?php echo htmlspecialchars($menu['gambar']); ?>" alt="Foto Menu" class="img-thumbnail mb-2" style="max-height: 150px;">
          <?php else: ?>
            <p class="text-muted font-italic">Belum ada foto.</p>
          <?php endif; ?>
          <br>
          <label for="gambar" class="font-weight-bold">Ganti Foto Menu (Opsional)</label>
          <input type="file" class="form-control-file border p-1" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.webp">
          <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG, WEBP (Maks 3MB).</small>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary" name="simpan_edit"><i class="fas fa-save mr-1"></i> SIMPAN PERUBAHAN</button>
          <a href="daftar_menu.php" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> BATAL</a>
        </div>
      </form>
    </div>
  </div>
  <!-- Akhir Form Edit Menu --> 

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>