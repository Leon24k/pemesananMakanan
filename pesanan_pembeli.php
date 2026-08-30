<?php
require_once 'auth.php';
require_login();
require_once 'koneksi.php';

// Menangani aksi pengosongan keranjang jika ada parameter unset
if (isset($_GET['unset'])) {
    unset($_SESSION['pesanan']);
    header("Location: menu_pembeli.php");
    exit;
}

// Menangani proses checkout / konfirmasi pesanan
if (isset($_POST['konfirm']) || isset($_POST['cetak'])) {
    if (empty($_SESSION["pesanan"])) {
        echo "<script>alert('Pesanan kosong!'); window.location.href='menu_pembeli.php';</script>";
        exit;
    }

    $id_user = (int)($_SESSION['id_user'] ?? 0);
    $tanggal_pemesanan = date("Y-m-d");
    $is_cetak = isset($_POST['cetak']);

    // 1. Verifikasi ketersediaan stok semua item terlebih dahulu
    $total_kalkulasi = 0;
    $items_to_process = [];

    foreach ($_SESSION["pesanan"] as $id_menu => $jumlah) {
        $id_menu = (int)$id_menu;
        $jumlah = (int)$jumlah;

        $stmt_check = mysqli_prepare($koneksi, "SELECT id_menu, nama_menu, harga, stok FROM produk WHERE id_menu = ?");
        mysqli_stmt_bind_param($stmt_check, "i", $id_menu);
        mysqli_stmt_execute($stmt_check);
        $prod = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check));
        mysqli_stmt_close($stmt_check);

        if (!$prod) {
            echo "<script>alert('Ada produk yang tidak valid di keranjang Anda!'); window.location.href='pesanan_pembeli.php';</script>";
            exit;
        }

        if ($prod['stok'] < $jumlah) {
            echo "<script>alert('Maaf, stok untuk " . htmlspecialchars(addslashes($prod['nama_menu'])) . " tidak mencukupi (Tersisa: " . $prod['stok'] . "). Silahkan kurangi pesanan Anda!'); window.location.href='pesanan_pembeli.php';</script>";
            exit;
        }

        $sub = $prod['harga'] * $jumlah;
        $total_kalkulasi += $sub;
        $items_to_process[] = [
            'id_menu' => $id_menu,
            'nama_menu' => $prod['nama_menu'],
            'harga' => $prod['harga'],
            'jumlah' => $jumlah,
            'subharga' => $sub
        ];
    }

    // 2. Simpan transaksi utama ke tabel pemesanan
    $status_pembayaran = 'Belum Bayar';
    $stmt_order = mysqli_prepare($koneksi, "INSERT INTO pemesanan (id_user, tanggal_pemesanan, total_belanja, status_pembayaran) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_order, "isis", $id_user, $tanggal_pemesanan, $total_kalkulasi, $status_pembayaran);
    $order_saved = mysqli_stmt_execute($stmt_order);
    $id_terbaru  = $koneksi->insert_id;
    mysqli_stmt_close($stmt_order);

    if ($order_saved && $id_terbaru > 0) {
        // 3. Simpan rincian ke pemesanan_produk dan kurangi stok di tabel produk
        $stmt_item = mysqli_prepare($koneksi, "INSERT INTO pemesanan_produk (id_pemesanan, id_menu, jumlah) VALUES (?, ?, ?)");
        $stmt_stok = mysqli_prepare($koneksi, "UPDATE produk SET stok = stok - ? WHERE id_menu = ?");

        foreach ($items_to_process as $item) {
            // Insert rincian pesanan
            mysqli_stmt_bind_param($stmt_item, "iii", $id_terbaru, $item['id_menu'], $item['jumlah']);
            mysqli_stmt_execute($stmt_item);

            // Kurangi stok produk
            mysqli_stmt_bind_param($stmt_stok, "ii", $item['jumlah'], $item['id_menu']);
            mysqli_stmt_execute($stmt_stok);
        }

        mysqli_stmt_close($stmt_item);
        mysqli_stmt_close($stmt_stok);

        // 4. Kosongkan keranjang belanja
        unset($_SESSION["pesanan"]);

        if ($is_cetak) {
            echo "<script>
                    alert('Pesanan berhasil dibuat!');
                    window.location.href = 'struk.php?id=" . $id_terbaru . "';
                  </script>";
        } else {
            echo "<script>
                    alert('Pesanan berhasil dikonfirmasi! Silahkan lakukan pembayaran di kasir.');
                    window.location.href = 'struk.php?id=" . $id_terbaru . "';
                  </script>";
        }
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan pesanan. Terjadi kesalahan server.'); window.location.href='pesanan_pembeli.php';</script>";
        exit;
    }
}

// Ambil data produk di keranjang untuk ditampilkan
$keranjang_items = [];
$totalbelanja = 0;

if (!empty($_SESSION["pesanan"])) {
    foreach ($_SESSION["pesanan"] as $id_menu => $jumlah) {
        $id_menu = (int)$id_menu;
        $jumlah = (int)$jumlah;

        $stmt = mysqli_prepare($koneksi, "SELECT * FROM produk WHERE id_menu = ?");
        mysqli_stmt_bind_param($stmt, "i", $id_menu);
        mysqli_stmt_execute($stmt);
        $prod = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if ($prod) {
            $subharga = $prod["harga"] * $jumlah;
            $totalbelanja += $subharga;
            $keranjang_items[] = [
                'id_menu' => $id_menu,
                'nama_menu' => $prod['nama_menu'],
                'harga' => $prod['harga'],
                'jumlah' => $jumlah,
                'subharga' => $subharga
            ];
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
  <link rel="stylesheet" type="text/css" href="index.css">
  <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

  <title>Pesanan Anda - Restoran Web</title>
</head>

<body>
  <!-- Jumbotron -->
  <div class="jumbotron jumbotron-fluid text-center mb-0">
    <div class="container">
      <h1 class="display-4"><span class="font-weight-bold">Restoran Web</span></h1>
      <hr>
      <p class="lead font-weight-bold">Keranjang & Konfirmasi Pesanan Anda</p>
    </div>
  </div>
  <!-- Akhir Jumbotron -->

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.php"><strong>Resto</strong> Web</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link mr-3" href="index.php"><i class="fas fa-home mr-1"></i> HOME</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mr-3" href="menu_pembeli.php"><i class="fas fa-utensils mr-1"></i> DAFTAR MENU</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link mr-3" href="pesanan_pembeli.php"><i class="fas fa-shopping-cart mr-1"></i> PESANAN ANDA</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i> LOGOUT</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- Akhir Navbar -->

  <!-- Konten Keranjang Pesanan -->
  <div class="container my-5">
    <div class="judul-pesanan text-center mb-4">
      <h3 class="font-weight-bold">KERANJANG PESANAN ANDA</h3>
    </div>

    <?php if (empty($keranjang_items)): ?>
      <div class="card shadow-sm p-5 text-center">
        <i class="fas fa-shopping-basket fa-4x text-muted mb-3"></i>
        <h4>Keranjang Pesanan Anda Masih Kosong</h4>
        <p class="text-muted">Silahkan pilih menu makanan atau minuman favorit Anda terlebih dahulu.</p>
        <div class="mt-3">
          <a href="menu_pembeli.php" class="btn btn-primary btn-lg"><i class="fas fa-utensils mr-1"></i> Lihat Daftar Menu</a>
        </div>
      </div>
    <?php else: ?>
      <div class="card shadow-sm p-3">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="example">
            <thead class="thead-light">
              <tr>
                <th scope="col" style="width: 5%;">No</th>
                <th scope="col">Nama Menu</th>
                <th scope="col">Harga Satuan</th>
                <th scope="col" class="text-center" style="width: 10%;">Jumlah</th>
                <th scope="col">Subtotal</th>
                <th scope="col" class="text-center" style="width: 12%;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $nomor = 1; ?>
              <?php foreach ($keranjang_items as $item): ?>
                <tr>
                  <td class="text-center"><?php echo $nomor; ?></td>
                  <td><strong><?php echo htmlspecialchars($item["nama_menu"]); ?></strong></td>
                  <td>Rp. <?php echo number_format($item["harga"], 0, ',', '.'); ?></td>
                  <td class="text-center"><?php echo $item["jumlah"]; ?></td>
                  <td>Rp. <?php echo number_format($item["subharga"], 0, ',', '.'); ?></td>
                  <td class="text-center">
                    <a href="hapus_pesanan.php?id_menu=<?php echo urlencode($item['id_menu']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus menu ini dari pesanan?');">
                      <i class="fas fa-trash mr-1"></i> Hapus
                    </a>
                  </td>
                </tr>
                <?php $nomor++; ?>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr class="bg-light">
                <th colspan="4" class="text-right font-weight-bold">Total Pembayaran:</th>
                <th colspan="2" class="text-primary font-weight-bold">Rp. <?php echo number_format($totalbelanja, 0, ',', '.'); ?></th>
              </tr>
            </tfoot>
          </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
          <a href="menu_pembeli.php" class="btn btn-secondary"><i class="fas fa-plus mr-1"></i> Tambah Menu Lain</a>
          <button class="btn btn-success" data-toggle="modal" data-target="#konfirmasiModal">
            <i class="fas fa-check-circle mr-1"></i> Konfirmasi Pesanan
          </button>
        </div>
      </div>

      <!-- Modal Konfirmasi Pesanan -->
      <div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-dark text-white">
              <h5 class="modal-title font-weight-bold" id="modalLabel">Konfirmasi Pemesanan</h5>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p class="mb-2">Total pesanan Anda: <strong class="text-primary">Rp. <?php echo number_format($totalbelanja, 0, ',', '.'); ?></strong></p>
              <p class="text-muted mb-0">Apakah Anda ingin langsung mencetak struk pemesanan setelah pesanan dibuat?</p>
            </div>
            <div class="modal-footer">
              <form action="" method="POST" class="d-inline">
                <button type="submit" class="btn btn-outline-primary" name="konfirm">Konfirmasi Saja</button>
                <button type="submit" class="btn btn-success" name="cetak"><i class="fas fa-print mr-1"></i> Konfirmasi & Cetak Struk</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
  <!-- Akhir Menu Pembeli -->

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
      if ($('#example tbody tr').length > 0) {
        $('#example').DataTable({
          "paging": false,
          "info": false,
          "searching": false
        });
      }
    });
  </script>
</body>
</html>