<?php 
require_once 'auth.php';
require_admin();
require_once 'koneksi.php';

$query = mysqli_query($koneksi, 'SELECT * FROM produk ORDER BY id_menu DESC');
$menu_list = mysqli_fetch_all($query, MYSQLI_ASSOC);
?>

<!doctype html>
<html lang="id">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">

    <title>Daftar Menu - Admin Restoran</title>
  </head>
  <body>
  <!-- Jumbotron -->
      <div class="jumbotron jumbotron-fluid text-center mb-0">
        <div class="container">
          <h1 class="display-4"><span class="font-weight-bold">Restoran Web</span></h1>
          <hr>
          <p class="lead font-weight-bold">Manajemen Daftar Menu Makanan & Minuman</p>
        </div>
      </div>
  <!-- Akhir Jumbotron -->

  <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
        <a class="navbar-brand" href="admin.php"><strong>Resto</strong> Admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link mr-3" href="admin.php"><i class="fas fa-home mr-1"></i> HOME</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link mr-3" href="daftar_menu.php"><i class="fas fa-utensils mr-1"></i> DAFTAR MENU</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mr-3" href="pesanan.php"><i class="fas fa-receipt mr-1"></i> PESANAN</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i> LOGOUT</a>
            </li>
          </ul>
        </div>
       </div> 
      </nav>
  <!-- Akhir Navbar -->

  <!-- Menu -->
      <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3 class="font-weight-bold mb-0">Daftar Menu Restoran</h3>
          <a href="tambah_menu.php" class="btn btn-success"><i class="fas fa-plus mr-1"></i> TAMBAH MENU BARU</a>
        </div>

        <div class="row">
          <?php if (empty($menu_list)): ?>
            <div class="col-12 text-center py-5">
              <p class="text-muted">Belum ada menu yang terdaftar. Silahkan tambah menu baru.</p>
            </div>
          <?php else: ?>
            <?php foreach($menu_list as $menu) : ?>
              <div class="col-md-3 mt-4">
                <div class="card shadow-sm h-100">
                  <?php 
                    $gambar_path = "upload/" . $menu['gambar'];
                    if (!empty($menu['gambar']) && file_exists($gambar_path)) {
                        $src = htmlspecialchars($gambar_path);
                    } else {
                        $src = "images/menu1.jpg";
                    }
                  ?>
                  <img src="<?php echo $src; ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="<?php echo htmlspecialchars($menu['nama_menu']); ?>">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title font-weight-bold"><?php echo htmlspecialchars($menu['nama_menu']); ?></h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <span class="badge badge-info"><?php echo htmlspecialchars($menu['jenis_menu']); ?></span>
                      <span class="badge <?php echo $menu['stok'] > 0 ? 'badge-success' : 'badge-danger'; ?>">
                        Stok: <?php echo htmlspecialchars($menu['stok']); ?>
                      </span>
                    </div>
                    <label class="card-text harga text-primary font-weight-bold">
                      Rp. <?php echo number_format($menu['harga'], 0, ',', '.'); ?>
                    </label>
                    <div class="mt-auto pt-2">
                      <a href="edit_menu.php?id_menu=<?php echo urlencode($menu['id_menu']); ?>" class="btn btn-warning btn-sm btn-block text-white mb-2">
                        <i class="fas fa-edit mr-1"></i> EDIT
                      </a>
                      <a href="hapus_menu.php?id_menu=<?php echo urlencode($menu['id_menu']); ?>" class="btn btn-danger btn-sm btn-block text-light" onclick="return confirm('Apakah Anda yakin ingin menghapus menu <?php echo htmlspecialchars(addslashes($menu['nama_menu'])); ?>?');">
                        <i class="fas fa-trash mr-1"></i> HAPUS
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
  <!-- Akhir Menu -->

  <!-- Awal Footer -->
      <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
          <p class="mb-0">&copy; <?php echo date('Y'); ?> Restoran Web. All Rights Reserved.</p>
        </div>
      </footer>
  <!-- Akhir Footer -->

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>