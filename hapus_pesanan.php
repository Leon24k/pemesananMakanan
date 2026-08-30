<?php 
require_once 'auth.php';
require_login();

$id_menu = (int)($_GET["id_menu"] ?? 0);

if ($id_menu > 0 && isset($_SESSION["pesanan"][$id_menu])) {
    unset($_SESSION["pesanan"][$id_menu]);
    echo "<script>alert('Menu telah dihapus dari keranjang pesanan.'); window.location.href='pesanan_pembeli.php';</script>";
    exit;
}

header('Location: pesanan_pembeli.php');
exit;
?>