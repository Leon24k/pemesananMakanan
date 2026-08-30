<?php 
require_once 'auth.php';
require_admin();
require_once 'koneksi.php';

// Ambil data pemesanan beserta informasi pemesan jika ada
$query = "SELECT p.*, u.nama_lengkap, u.username 
          FROM pemesanan p 
          LEFT JOIN user u ON p.id_user = u.id_user 
          ORDER BY p.id_pemesanan DESC";
$ambil = mysqli_query($koneksi, $query);
$pesanan_list = mysqli_fetch_all($ambil, MYSQLI_ASSOC);
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

    <title>Data Pesanan Pelanggan - Restoran Web</title>
  </head>
  <body>
  <!-- Jumbotron -->
      <div class="jumbotron jumbotron-fluid text-center mb-0">
        <div class="container">
          <h1 class="display-4"><span class="font-weight-bold">Restoran Web</span></h1>
          <hr>
          <p class="lead font-weight-bold">Daftar Transaksi Pesanan Pelanggan</p>
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
            <li class="nav-item">
              <a class="nav-link mr-3" href="daftar_menu.php"><i class="fas fa-utensils mr-1"></i> DAFTAR MENU</a>
            </li>
            <li class="nav-item active">
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

  <!-- Menu Pesanan -->
    <div class="container my-5">
      <div class="judul-pesanan text-center mb-4">
        <h3 class="font-weight-bold">DATA PESANAN PELANGGAN</h3>
      </div>

      <div class="card shadow-sm p-3">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="example">
            <thead class="thead-dark">
              <tr>
                <th scope="col" style="width: 5%;">No.</th>
                <th scope="col">ID Pesanan</th>
                <th scope="col">Nama Pemesan</th>
                <th scope="col">Tanggal Pesan</th>
                <th scope="col">Total Bayar</th>
                <th scope="col">Status Pembayaran</th>
                <th scope="col" style="width: 20%;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $nomor = 1; ?>
              <?php foreach($pesanan_list as $row) : ?>
              <tr>
                <td class="text-center"><?php echo $nomor; ?></td>
                <td>#<?php echo htmlspecialchars($row["id_pemesanan"]); ?></td>
                <td>
                  <strong>
                    <?php echo htmlspecialchars($row["nama_lengkap"] ?? $row["username"] ?? 'Pelanggan'); ?>
                  </strong>
                </td>
                <td><?php echo date('d-m-Y', strtotime($row["tanggal_pemesanan"])); ?></td>
                <td>Rp. <?php echo number_format($row["total_belanja"], 0, ',', '.'); ?></td>
                <td>
                  <?php if (($row['status_pembayaran'] ?? '') === 'Lunas'): ?>
                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Lunas</span>
                  <?php else: ?>
                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Belum Bayar</span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="detail_pesanan.php?id=<?php echo urlencode($row['id_pemesanan']); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye mr-1"></i> Detail
                  </a>
                  <a href="clear_pesanan.php?id=<?php echo urlencode($row['id_pemesanan']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan #<?php echo htmlspecialchars($row['id_pemesanan']); ?>?');">
                    <i class="fas fa-trash mr-1"></i> Hapus
                  </a>
                </td>
              </tr>
              <?php $nomor++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <!-- Akhir Menu Pesanan -->

  <!-- Awal Footer -->
      <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
          <p class="mb-0">&copy; <?php echo date('Y'); ?> Restoran Web. All Rights Reserved.</p>
        </div>
      </footer>
  <!-- Akhir Footer -->

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
      $(document).ready(function() {
          $('#example').DataTable({
            "language": {
              "search": "Cari:",
              "lengthMenu": "Tampilkan _MENU_ data",
              "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
              "paginate": {
                "first": "Awal",
                "last": "Akhir",
                "next": "Berikutnya",
                "previous": "Sebelumnya"
              }
            }
          });
      });
    </script>
  </body>
</html>