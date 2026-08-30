-- phpMyAdmin SQL Dump
-- Database: `dbpemesanan`
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan`
--

CREATE TABLE IF NOT EXISTS `pemesanan` (
  `id_pemesanan` int(50) NOT NULL AUTO_INCREMENT,
  `id_user` int(50) DEFAULT NULL,
  `tanggal_pemesanan` date NOT NULL,
  `total_belanja` int(50) NOT NULL,
  `status_pembayaran` enum('Belum Bayar','Lunas') NOT NULL DEFAULT 'Belum Bayar',
  PRIMARY KEY (`id_pemesanan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan_produk`
--

CREATE TABLE IF NOT EXISTS `pemesanan_produk` (
  `id_pemesanan_produk` int(50) NOT NULL AUTO_INCREMENT,
  `id_pemesanan` int(50) NOT NULL,
  `id_menu` int(50) NOT NULL,
  `jumlah` int(50) NOT NULL,
  PRIMARY KEY (`id_pemesanan_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE IF NOT EXISTS `produk` (
  `id_menu` int(50) NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(100) NOT NULL,
  `jenis_menu` varchar(50) NOT NULL,
  `stok` int(50) NOT NULL DEFAULT 0,
  `harga` int(50) NOT NULL DEFAULT 0,
  `gambar` varchar(255) NOT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_menu`, `nama_menu`, `jenis_menu`, `stok`, `harga`, `gambar`) VALUES
(6, 'Bakso Urat', 'Makanan', 80, 12000, 'bakso.jpeg'),
(7, 'Mie Ayam', 'Makanan', 50, 13000, 'mieayam.jpg'),
(8, 'Mie Ayam Bakso', 'Makanan', 50, 17000, 'mieayambakso.jpg'),
(9, 'Ayam Bakar', 'Makanan', 45, 20000, 'ayambakar.jpg'),
(10, 'Lele Bakar', 'Makanan', 50, 12000, 'lele.jpg'),
(11, 'Nasi Goreng', 'Makanan', 78, 10000, 'nasgor.jpg'),
(12, 'Nasi Putih', 'Makanan', 100, 2000, 'nasi.jpeg'),
(13, 'Es Jeruk', 'Minuman', 55, 8000, 'esjeruk.jpg'),
(14, 'Jus Alpukat', 'Minuman', 50, 10000, 'juspukat.jpg'),
(15, 'Jus Mangga', 'Minuman', 50, 10000, 'jusmangga.jpg'),
(16, 'Teh Obeng', 'Minuman', 60, 5000, 'tehobeng.jpg'),
(17, 'Air Mineral', 'Minuman', 100, 4000, 'sanford.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(25) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jenis_kelamin` varchar(25) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `hp` varchar(25) NOT NULL,
  `status` enum('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data default untuk user (password hash default: admin123 & user123, atau legacy plain text yang otomatis di-hash saat login pertama)
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama_lengkap`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `hp`, `status`) VALUES
(1, 'admin', '$2y$10$44i7a33e3J8W8gG87bM9i.nO4E1Fj.j5Lq6970O3Z8E5T9B.Xg1kK', 'Administrator Restoran', 'Laki-Laki', '1998-05-19', 'Bekasi', '089123614882', 'admin'),
(2, 'user', '$2y$10$bB0E44g76qJ16eN96rT7h.V4K3Q5B.b6M8w01Z9E7K6L9P.Xm2kK', 'Pelanggan Setia', 'Laki-Laki', '1999-01-11', 'Bekasi', '085233748222', 'user');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
