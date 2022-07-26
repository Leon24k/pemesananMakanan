<?php 

elseif (isset($_POST['cetak'])) {
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
    unset($_SESSION["pesanan"]);

    // Dialihkan ke halaman nota
    header("location:struk.php");
    // echo "<script>location= 'menu_pembeli.php'</script>";
  }

?>