<?php
require_once 'auth.php';
require_login();
require_once 'koneksi.php';

$id_pemesanan = (int)($_GET['id'] ?? 0);

if ($id_pemesanan <= 0) {
    // Cari pesanan terakhir dari user ini
    $stmt_latest = mysqli_prepare($koneksi, "SELECT id_pemesanan FROM pemesanan WHERE id_user = ? ORDER BY id_pemesanan DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt_latest, "i", $_SESSION['id_user']);
    mysqli_stmt_execute($stmt_latest);
    $res_latest = mysqli_stmt_get_result($stmt_latest);
    if ($row_l = mysqli_fetch_assoc($res_latest)) {
        $id_pemesanan = (int)$row_l['id_pemesanan'];
    }
    mysqli_stmt_close($stmt_latest);
}

if ($id_pemesanan <= 0) {
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location.href='menu_pembeli.php';</script>";
    exit;
}

// Ambil info pesanan
$stmt_order = mysqli_prepare($koneksi, "SELECT p.*, u.nama_lengkap, u.hp, u.alamat, u.username FROM pemesanan p LEFT JOIN user u ON p.id_user = u.id_user WHERE p.id_pemesanan = ?");
mysqli_stmt_bind_param($stmt_order, "i", $id_pemesanan);
mysqli_stmt_execute($stmt_order);
$order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_order));
mysqli_stmt_close($stmt_order);

if (!$order) {
    echo "<script>alert('Data struk tidak ditemukan!'); window.location.href='menu_pembeli.php';</script>";
    exit;
}

// Otorisasi: Pastikan hanya pemilik pesanan atau admin yang bisa melihat struk ini
if ($_SESSION['user_status'] !== 'admin' && (int)$order['id_user'] !== (int)$_SESSION['id_user']) {
    echo "<script>alert('Anda tidak memiliki akses ke struk pesanan ini.'); window.location.href='index.php';</script>";
    exit;
}

// Ambil item produk dalam pesanan
$stmt_items = mysqli_prepare($koneksi, "SELECT pp.*, pr.nama_menu, pr.harga FROM pemesanan_produk pp JOIN produk pr ON pp.id_menu = pr.id_menu WHERE pp.id_pemesanan = ?");
mysqli_stmt_bind_param($stmt_items, "i", $id_pemesanan);
mysqli_stmt_execute($stmt_items);
$items_res = mysqli_stmt_get_result($stmt_items);
$items = mysqli_fetch_all($items_res, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_items);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">

    <title>Struk Pemesanan #<?php echo $id_pemesanan; ?> - Restoran Web</title>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Courier New', Courier, monospace;
        }
        .struk-card {
            max-width: 550px;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border: 1px dashed #ccc;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .struk-header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .struk-footer {
            border-top: 2px dashed #333;
            padding-top: 15px;
            margin-top: 20px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #fff;
            }
            .struk-card {
                border: none;
                box-shadow: none;
                padding: 0;
                margin: 0 auto;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Tombol Aksi (Hanya Tampil di Layar) -->
        <div class="no-print text-center mt-4">
            <button onclick="window.print()" class="btn btn-primary btn-sm mr-2">
                <i class="fas fa-print mr-1"></i> Cetak Struk
            </button>
            <?php if ($_SESSION['user_status'] === 'admin'): ?>
                <a href="pesanan.php" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Pesanan
                </a>
            <?php else: ?>
                <a href="menu_pembeli.php" class="btn btn-success btn-sm mr-2">
                    <i class="fas fa-utensils mr-1"></i> Pesan Menu Lain
                </a>
                <a href="index.php" class="btn btn-secondary btn-sm">
                    <i class="fas fa-home mr-1"></i> Beranda
                </a>
            <?php endif; ?>
        </div>

        <!-- Tampilan Struk -->
        <div class="struk-card">
            <div class="struk-header">
                <h3 class="font-weight-bold mb-1">RESTORAN WEB</h3>
                <p class="mb-1">Kota Bekasi, Gang Macan &bull; Telp: 089560328673</p>
                <small class="text-muted">Buka: 16:00 - 23:00 WIB</small>
            </div>

            <table class="table table-borderless table-sm mb-3" style="font-size: 13px;">
                <tr>
                    <td style="width: 40%;"><strong>No. Pesanan</strong></td>
                    <td>: #<?php echo htmlspecialchars($order['id_pemesanan']); ?></td>
                </tr>
                <tr>
                    <td><strong>Tanggal</strong></td>
                    <td>: <?php echo date('d-m-Y', strtotime($order['tanggal_pemesanan'])); ?></td>
                </tr>
                <tr>
                    <td><strong>Pemesan</strong></td>
                    <td>: <?php echo htmlspecialchars($order['nama_lengkap'] ?? $order['username'] ?? 'Pelanggan'); ?></td>
                </tr>
                <tr>
                    <td><strong>Status</strong></td>
                    <td>: 
                        <?php if (($order['status_pembayaran'] ?? '') === 'Lunas'): ?>
                            <span class="badge badge-success">LUNAS</span>
                        <?php else: ?>
                            <span class="badge badge-warning">MENUNGGU PEMBAYARAN</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <table class="table table-sm" style="font-size: 13px;">
                <thead style="border-top: 1px dashed #333; border-bottom: 1px dashed #333;">
                    <tr>
                        <th>Item Menu</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $grand_total = 0; ?>
                    <?php foreach ($items as $item): ?>
                        <?php 
                            $sub = $item['harga'] * $item['jumlah'];
                            $grand_total += $sub;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nama_menu']); ?></td>
                            <td class="text-center"><?php echo $item['jumlah']; ?></td>
                            <td class="text-right">Rp. <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                            <td class="text-right">Rp. <?php echo number_format($sub, 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot style="border-top: 1px dashed #333;">
                    <tr>
                        <th colspan="3" class="text-right">TOTAL BAYAR:</th>
                        <th class="text-right">Rp. <?php echo number_format($grand_total, 0, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>

            <div class="struk-footer text-center" style="font-size: 12px;">
                <p class="mb-1 font-weight-bold">-- TERIMA KASIH ATAS KUNJUNGAN ANDA --</p>
                <p class="mb-0 text-muted">Silahkan tunjukkan struk ini ke kasir jika belum melakukan pembayaran.</p>
            </div>
        </div>
    </div>
</body>
</html>