<!doctype html>
<html lang="id">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">

    <title>Halaman Registrasi - Restoran Web</title>
  </head>
  <body class="bg-light">
 
  <!-- Form Registrasi -->
  <div class="container mt-5">
    <div class="card shadow-sm p-4 mb-5 bg-white rounded col-md-8 mx-auto">
      <h3 class="text-center font-weight-bold mb-4">HALAMAN REGISTRASI</h3>
      <form method="POST" action="simpan_register.php">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="user font-weight-bold">Username</label>
            <input type="text" class="form-control" id="user" name="username" placeholder="Masukkan Username" required autocomplete="username">
          </div>
          <div class="form-group col-md-6">
            <label for="pass font-weight-bold">Password</label>
            <input type="password" class="form-control" id="pass" name="password" placeholder="Masukkan Password (min 4 karakter)" required minlength="4" autocomplete="new-password">
          </div>
        </div>
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" class="form-control" id="nama" name="nama_lengkap" placeholder="Masukkan Nama Lengkap" required>
        </div>
        <div class="form-group">
          <label for="jk">Jenis Kelamin</label><br>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk1" value="Laki-Laki" checked>
            <label class="form-check-label" for="jk1">Laki-Laki</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk2" value="Perempuan">
            <label class="form-check-label" for="jk2">Perempuan</label>
          </div>
        </div>
        <div class="form-group">
          <label for="tgl">Tanggal Lahir</label>
          <input type="date" class="form-control" id="tgl" name="tanggal_lahir" required>
        </div>
        <div class="form-row">
          <div class="form-group col-md-7">
            <label for="rumah">Alamat</label>
            <input type="text" class="form-control" id="rumah" name="alamat" placeholder="Masukkan Alamat" required>
          </div>
          <div class="form-group col-md-5">
            <label for="telp">No. Telepon / HP</label>
            <input type="tel" class="form-control" id="telp" name="hp" placeholder="08xxxxxxxxxx" required>
          </div>
        </div>     
        <div class="mt-3">
          <button type="submit" class="btn btn-primary btn-block">REGISTER SEKARANG</button>
          <a href="login.php" class="btn btn-outline-secondary btn-block mt-2">Sudah Punya Akun? Login</a>
        </div>
      </form>
    </div>
  </div>
  <!-- Akhir Form Registrasi -->

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>