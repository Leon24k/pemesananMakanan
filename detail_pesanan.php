<?php 
require_once 'auth.php';
require_admin();
require_once 'koneksi.php';

$id_pemesanan = (int)($_GET['id'] ?? 0);

if ($id_pemesanan <= 0) {
    header("Location: pesanan.php");
    exit;
}

// Proses Konfirmasi Pembayaran
if (isset($_POST["bayar"])) {
    $stmt_pay = mysqli_prepare($koneksi, "UPDATE pemesanan SET status_pembayaran = 'Lunas' WHERE id_pemesanan = ?");
    mysqli_stmt_bind_param($stmt_pay, "i", $id_pemesanan);
    mysqli_stmt_execute($stmt_pay);
    mysqli_stmt_close($stmt_pay);

    echo "<script>alert('Status pesanan berhasil dikonfirmasi LUNAS!'); window.location.href='detail_pesanan.php?id=" . $id_pemesanan . "';</script>";
    exit;
}

// Ambil info pesanan & pemesan
$stmt_order = mysqli_prepare($koneksi, "SELECT p.*, u.nama_lengkap, u.hp, u.alamat FROM pemesanan p LEFT JOIN user u ON p.id_user = u.id_user WHERE p.id_pemesanan = ?");
mysqli_stmt_bind_param($stmt_order, "i", $id_pemesanan);
mysqli_stmt_execute($stmt_order);
$order_info = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_order));
mysqli_stmt_close($stmt_order);

if (!$order_info) {
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location.href='pesanan.php';</script>";
    exit;
}

// Ambil item produk dalam pesanan
$stmt_items = mysqli_prepare($koneksi, "SELECT pp.*, pr.nama_menu, pr.harga, pr.gambar FROM pemesanan_produk pp JOIN produk pr ON pp.id_menu = pr.id_menu WHERE pp.id_pemesanan = ?");
mysqli_stmt_bind_param($stmt_items, "i", $id_pemesanan);
mysqli_stmt_execute($stmt_items);
$items_res = mysqli_stmt_get_result($stmt_items);
$items = mysqli_fetch_all($items_res, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_items);
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

    <title>Detail Pesanan #<?php echo $id_pemesanan; ?> - Restoran Web</title>
  </head>
  <body>
  <!-- Jumbotron -->
      <div class="jumbotron jumbotron-fluid text-center mb-0">
        <div class="container">
          <h1 class="display-4"><span class="font-weight-bold">Restoran Web</span></h1>
          <hr>
          <p class="lead font-weight-bold">Rincian Detail Transaksi Pesanan</p>
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

  <!-- Konten Detail Pesanan -->
    <div class="container my-5">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0 font-weight-bold">Informasi Pesanan #<?php echo $id_pemesanan; ?></h5>
          <span>
            Status: 
            <?php if (($order_info['status_pembayaran'] ?? '') === 'Lunas'): ?>
              <span class="badge badge-success px-2 py-1">LUNAS</span>
            <?php else: ?>
              <span class="badge badge-warning px-2 py-1">BELUM BAYAR</span>
            <?php endif; ?>
          </span>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <table class="table table-borderless table-sm">
                <tr>
                  <th style="width: 35%;">Nama Pemesan</th>
                  <td>: <?php echo htmlspecialchars($order_info['nama_lengkap'] ?? 'Pelanggan'); ?></td>
                </tr>
                <tr>
                  <th>No. Telepon / HP</th>
                  <td>: <?php echo htmlspecialchars($order_info['hp'] ?? '-'); ?></td>
                </tr>
                <tr>
                  <th>Alamat</th>
                  <td>: <?php echo htmlspecialchars($order_info['alamat'] ?? '-'); ?></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6 mb-3">
              <table class="table table-borderless table-sm">
                <tr>
                  <th style="width: 35%;">Tanggal Pesan</th>
                  <td>: <?php echo date('d F Y', strtotime($order_info['tanggal_pemesanan'])); ?></td>
                </tr>
                <tr>
                  <th>Total Tagihan</th>
                  <td class="text-primary font-weight-bold">: Rp. <?php echo number_format($order_info['total_belanja'], 0, ',', '.'); ?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm p-3">
        <h5 class="font-weight-bold mb-3">Item Menu yang Dipesan</h5>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th scope="col" style="width: 5%;">No.</th>
                <th scope="col">Nama Menu</th>
                <th scope="col">Harga Satuan</th>
                <th scope="col" class="text-center">Jumlah</th>
                <th scope="col">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php $nomor = 1; $total_kalkulasi = 0; ?>
              <?php foreach ($items as $item): ?>
                <?php 
                  $subharga = $item['harga'] * $item['jumlah'];
                  $total_kalkulasi += $subharga;
                ?>
                <tr>
                  <td class="text-center"><?php echo $nomor; ?></td>
                  <td><strong><?php echo htmlspecialchars($item['nama_menu']); ?></strong></td>
                  <td>Rp. <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                  <td class="text-center"><?php echo $item['jumlah']; ?></td>
                  <td>Rp. <?php echo number_format($subharga, 0, ',', '.'); ?></td>
                </tr>
                <?php $nomor++; ?>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-right">Total Keseluruhan:</th>
                <th class="text-primary font-weight-bold">Rp. <?php echo number_format($total_kalkulasi, 0, ',', '.'); ?></th>
              </tr>
            </tfoot>
          </table>
        </div>

        <div class="d-flex justify-content-between mt-3">
          <a href="pesanan.php" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Pesanan</a>
          <div>
            <a href="struk.php?id=<?php echo urlencode($id_pemesanan); ?>" target="_blank" class="btn btn-info mr-2">
              <i class="fas fa-print mr-1"></i> Cetak Struk
            </a>
            <?php if (($order_info['status_pembayaran'] ?? '') !== 'Lunas'): ?>
              <form method="POST" action="" class="d-inline" onsubmit="return confirm('Konfirmasi bahwa pesanan ini telah DIBAYAR LUNAS?');">
                <button class="btn btn-success" name="bayar"><i class="fas fa-check-circle mr-1"></i> Konfirmasi Pembayaran (Lunas)</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <!-- Akhir Konten Detail Pesanan -->

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