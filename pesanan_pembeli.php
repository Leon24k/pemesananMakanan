<?php
include('koneksi.php');
session_start();
if (!isset($_SESSION['login_user'])) {
  header("location: login.php");
} else {
?>

  
  <?php

  if (isset($_GET['unset'])) {
    unset($_SESSION['pesanan']);
  }

  ?>

  <?php
  if (empty($_SESSION["pesanan"]) or !isset($_SESSION["pesanan"])) {
    echo "<script>alert('Pesanan kosong, Silahkan Pesan dahulu');</script>";
    echo "<script>location= 'menu_pembeli.php'</script>";
  }
  ?>

  <!doctype html>
  <html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

    <title>Restoran Web</title>
  </head>

  <body>
    <!-- Jumbotron -->
    <div class="jumbotron jumbotron-fluid text-center">
      <div class="container">
        <h1 class="display-4"><span class="font-weight-bold">BRestoran Web</span></h1>
        <hr>
        <p class="lead font-weight-bold">Silahkan Pesan Menu Sesuai Keinginan Anda <br>
          Enjoy Your Meal</p>
      </div>
    </div>
    <!-- Akhir Jumbotron -->

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg  bg-dark">
      <div class="container">
        <a class="navbar-brand text-white" href="user.php"><strong>Restoran Web</strong> New</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link mr-4" href="index.php">HOME</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mr-4" href="menu_pembeli.php">DAFTAR MENU</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mr-4" href="pesanan_pembeli.php">PESANAN ANDA</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mr-4" href="logout.php">LOGOUT</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Akhir Navbar -->

    <!-- Menu -->
    <div class="container">
      <div class="judul-pesanan mt-5">

        <h3 class="text-center font-weight-bold">PESANAN ANDA</h3>

      </div>
      <table class="table table-bordered" id="example">
        <thead class="thead-light">
          <tr>
            <th scope="col">No</th>
            <th scope="col">Nama Pesanan</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Subharga</th>
            <th scope="col">Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php $nomor = 1; ?>
          <?php $totalbelanja = 0; ?>
          <?php foreach ($_SESSION["pesanan"] as $id_menu => $jumlah) : ?>

            <?php
            include('koneksi.php');
            $ambil = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_menu='$id_menu'");
            $pecah = $ambil->fetch_assoc();
            $subharga = $pecah["harga"] * $jumlah;
            ?>
            <tr>
              <td><?php echo $nomor; ?></td>
              <td><?php echo $pecah["nama_menu"]; ?></td>
              <td>Rp. <?php echo number_format($pecah["harga"]); ?></td>
              <td><?php echo $jumlah; ?></td>
              <td>Rp. <?php echo number_format($subharga); ?></td>
              <td>
                <a href="hapus_pesanan.php?id_menu=<?php echo $id_menu ?>" class="badge badge-danger">Hapus</a>
              </td>
            </tr>
            <?php $nomor++; ?>
            <?php $totalbelanja += $subharga; ?>
          <?php endforeach ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="4">Total Belanja</th>
            <th colspan="2">Rp. <?php echo number_format($totalbelanja) ?></th>
          </tr>
        </tfoot>
      </table><br>
      <div class="d-inline">
        <a href="menu_pembeli.php" class="btn btn-primary btn-sm">Lihat Menu</a>
        <button class="btn btn-success btn-sm" id="tampil">Konfirmasi Pesanan</button>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="konfirmasi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">CETAK STRUK</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Apakah Anda Ingin Mencetak Struk ?
            </div>
            <div class="modal-footer justify-content-between ms-auto d-flex">
              <form action="" method="POST">
                <input type="hidden" name="total" value="<?= $totalbelanja; ?>">
                <button class="btn btn-secondary" name="konfirm">Tidak</button>
              </form>
              <form action="" method="POST">
                <input type="hidden" name="total" value="<?= $totalbelanja; ?>">
                <button class="btn btn-primary" name="cetak">Iyaa</button>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>

    <?php

    if (isset($_POST['konfirm'])) {
      $tanggal_pemesanan = date("Y-m-d");

      // Menyimpan data ke tabel pemesanan
      $insert = mysqli_query($koneksi, "INSERT INTO pemesanan (tanggal_pemesanan, total_belanja) VALUES ('$tanggal_pemesanan', '$totalbelanja')");

      // Mendapatkan ID barusan
      $id_terbaru = $koneksi->insert_id;

      // Menyimpan data ke tabel pemesanan produk
      foreach ($_SESSION["pesanan"] as $id_menu => $jumlah) {
        $insert = mysqli_query($koneksi, "INSERT INTO pemesanan_produk (id_pemesanan, id_menu, jumlah) VALUES ('$id_terbaru', '$id_menu', '$jumlah') ");
      }

      // Mengosongkan pesanan
      unset($_SESSION["pesanan"]);

      // Dialihkan ke halaman nota
      // header("location:menu_pembeli.php");
      echo "<script>alert('Pesanan Berhasil di Konfirmasi')</script>";
      echo "<script>location= 'menu_pembeli.php'</script>";
    } elseif (isset($_POST['cetak'])) {
      $tanggal_pemesanan = date("Y-m-d");

      // Menyimpan data ke tabel pemesanan
      $insert = mysqli_query($koneksi, "INSERT INTO pemesanan (tanggal_pemesanan, total_belanja) VALUES ('$tanggal_pemesanan', '$totalbelanja')");

      // Mendapatkan ID barusan
      $id_terbaru = $koneksi->insert_id;

      // Menyimpan data ke tabel pemesanan produk
      foreach ($_SESSION["pesanan"] as $id_menu => $jumlah) {
        $insert = mysqli_query($koneksi, "INSERT INTO pemesanan_produk (id_pemesanan, id_menu, jumlah) 
    VALUES ('$id_terbaru', '$id_menu', '$jumlah') ");
      }

      // Mengosongkan pesanan
      // unset($_SESSION["pesanan"]);

      // Dialihkan ke halaman nota
      echo "<script>
            window.location.href = 'http://localhost:8080/resto/struk.php';
        </script>";
      // echo "<script>location= 'menu_pembeli.php'</script>";
      // echo "<script>location= 'menu_pembeli.php'</script>";
    }

    ?>

    <!-- Akhir Menu -->


    <!-- Awal Footer -->
    <hr class="footer">
    <div class="container">
      <div class="row footer-body">
        <div class="col-md-6">
          <div class="copyright">
            <strong>Copyright</strong> <i class="far fa-copyright"></i> 2020 - Web Restoran New </p>
          </div>
        </div>

        <div class="col-md-6 d-flex justify-content-end">
          <div class="icon-contact">
            <label class="font-weight-bold">Follow Us </label>
            <a href="#"><img src="images/icon/fb.png" class="mr-3 ml-4" data-toggle="tooltip" title="Facebook"></a>
            <a href="#"><img src="images/icon/ig.png" class="mr-3" data-toggle="tooltip" title="Instagram"></a>
            <a href="#"><img src="images/icon/twitter.png" class="" data-toggle="tooltip" title="Twitter"></a>
          </div>
        </div>
      </div>
    </div>
    <!-- Akhir Footer -->



    <!-- Modal -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <script>
      $(document).ready(function() {
        $('#example').DataTable();
      });
    </script>
    <script>
      $(document).ready(function() {
        $("#tampil").click(function() {
          $("#konfirmasi").modal();
        });
      });
    </script>
  </body>

  </html>
<?php } ?>