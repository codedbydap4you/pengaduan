-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 14 Mar 2026 pada 09.32
-- Versi server: 9.1.0
-- Versi PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pengaduan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `Username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`Username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`Username`, `password`) VALUES
('1', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aspirasi`
--

DROP TABLE IF EXISTS `aspirasi`;
CREATE TABLE IF NOT EXISTS `aspirasi` (
  `id_aspirasi` int NOT NULL,
  `status` enum('Menunggu','Proses','Selesai','Ditolak') DEFAULT 'Menunggu',
  `id_kategori` int DEFAULT NULL,
  `feedback` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_aspirasi`),
  KEY `id_kategori` (`id_kategori`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `aspirasi`
--

INSERT INTO `aspirasi` (`id_aspirasi`, `status`, `id_kategori`, `feedback`) VALUES
(5, 'Selesai', NULL, 'jdbbvduukjjghg'),
(2, '', NULL, 'kk\r\n'),
(1, 'Selesai', NULL, 'sedang diproses, saat ini sudah dilakukan patokan di tanah kelas'),
(4, 'Selesai', NULL, 'baik selesai'),
(3, '', NULL, 'selesai'),
(6, '', NULL, ''),
(7, 'Selesai', NULL, 'coba cek'),
(8, '', NULL, ''),
(9, '', NULL, 'oke bentar ya\r\n'),
(10, '', NULL, ''),
(11, '', NULL, ''),
(12, 'Selesai', NULL, 'asd'),
(13, '', NULL, ''),
(14, '', NULL, ''),
(15, '', NULL, ''),
(16, '', NULL, ''),
(17, '', NULL, ''),
(18, '', NULL, ''),
(19, '', NULL, ''),
(20, '', NULL, ''),
(21, 'Selesai', NULL, ''),
(22, '', NULL, ''),
(23, '', NULL, ''),
(24, 'Ditolak', NULL, ''),
(25, 'Ditolak', NULL, ''),
(26, 'Ditolak', NULL, ''),
(27, 'Ditolak', NULL, ''),
(28, 'Selesai', NULL, ''),
(29, 'Ditolak', NULL, ''),
(30, 'Proses', NULL, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aspirasi_chat`
--

DROP TABLE IF EXISTS `aspirasi_chat`;
CREATE TABLE IF NOT EXISTS `aspirasi_chat` (
  `id_chat` int NOT NULL AUTO_INCREMENT,
  `id_aspirasi` varchar(20) NOT NULL,
  `sender_type` enum('admin','siswa') NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_chat`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `aspirasi_chat`
--

INSERT INTO `aspirasi_chat` (`id_chat`, `id_aspirasi`, `sender_type`, `message`, `is_read`, `created_at`) VALUES
(1, '13', 'siswa', 'halo apakah sudah ada perkembangan\r\n', 1, '2026-02-06 03:41:13'),
(2, '13', 'admin', 'saat ini sedang dilakukan perbaikan', 1, '2026-02-06 03:41:58'),
(3, '13', 'siswa', 'haiii\r\nhaiii', 1, '2026-02-06 03:45:12'),
(4, '20', 'siswa', 'h\r\n', 1, '2026-02-09 07:21:56'),
(5, '20', 'admin', 'ya', 1, '2026-02-09 07:22:06'),
(6, '20', 'admin', 'ya', 1, '2026-02-09 07:22:16'),
(7, '20', 'siswa', 'ya', 1, '2026-02-09 07:22:36'),
(8, '20', 'siswa', 'ya', 1, '2026-02-09 07:22:45'),
(9, '27', 'admin', 'ooooo', 1, '2026-02-10 00:24:19'),
(10, '27', 'siswa', 'kkkk', 1, '2026-02-10 00:24:28'),
(11, '13', 'admin', 'kjjnjndjfn\r\nrthejsjrksrwjjjrjjejnsfnwnaj\r\n\r\n\r\nkfjwa', 0, '2026-02-10 02:35:22'),
(12, '27', 'admin', 'm', 1, '2026-02-10 02:35:28'),
(13, '27', 'admin', 'hjsrhu\r\nfiwi\r\nekhwsj', 1, '2026-02-10 02:35:36'),
(14, '27', 'siswa', 'ghgh', 1, '2026-02-10 02:38:52'),
(15, '27', 'siswa', 'l', 1, '2026-02-10 03:04:21'),
(16, '27', 'siswa', 'lll', 1, '2026-02-10 03:04:36'),
(17, '19', 'admin', 'mmm', 0, '2026-02-10 03:05:55'),
(18, '24', 'admin', 'fycycyc', 0, '2026-02-10 03:17:00'),
(19, '27', 'admin', 'jddhzcjxj\r\nczcmsx\r\n', 1, '2026-02-10 06:37:22'),
(20, '27', 'admin', 'jnbczxn', 1, '2026-02-10 06:50:06'),
(21, '27', 'admin', 'zncmzcnnz ', 1, '2026-02-10 06:50:10'),
(22, '27', 'admin', 'mandmnd', 1, '2026-02-10 06:50:16'),
(23, '27', 'admin', 'zzzzz', 1, '2026-02-10 06:50:23'),
(24, '27', 'admin', 'jhjhbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb', 1, '2026-02-10 07:08:27'),
(25, '27', 'admin', 'nnn\r\n', 1, '2026-02-10 07:16:26'),
(26, '29', 'admin', 'nnn', 1, '2026-02-10 07:24:44'),
(27, '29', 'admin', 'mohon maaf tidak sesuai tolong kirim api ulang\r\n\r\n', 1, '2026-02-11 03:44:31'),
(28, '29', 'siswa', 'ya\r\n', 1, '2026-02-12 20:04:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `input_aspirasi`
--

DROP TABLE IF EXISTS `input_aspirasi`;
CREATE TABLE IF NOT EXISTS `input_aspirasi` (
  `id_pelaporan` int NOT NULL AUTO_INCREMENT,
  `nis` int DEFAULT NULL,
  `id_kategori` int DEFAULT NULL,
  `lokasi` varchar(50) DEFAULT NULL,
  `ket` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tgl_input` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pelaporan`),
  KEY `nis` (`nis`),
  KEY `id_kategori` (`id_kategori`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `input_aspirasi`
--

INSERT INTO `input_aspirasi` (`id_pelaporan`, `nis`, `id_kategori`, `lokasi`, `ket`, `tgl_input`) VALUES
(1, 1234567890, 2, 'a', 'a', '2026-01-30 11:16:14'),
(2, 1234567890, 1, 'depan kelas ', 'jghfgvg', '2026-01-30 11:16:14'),
(3, 1234567890, 2, 'kelas xi pplg 2', 'di a10 banyak jamur di dinding ', '2026-01-30 11:16:14'),
(4, 11111111, 1, 'depan kelas 12', 'rusak kran air', '2026-01-30 11:16:14'),
(5, 1234567890, 1, 'kantin', 'kantin kotor', '2026-02-03 09:49:07'),
(6, 1234567890, 2, 'ss', 'ss', '2026-02-03 10:17:43'),
(7, 1234567890, 1, 'a', 'a', '2026-02-03 10:22:08'),
(8, 1234567890, 1, 'nbnxbaaa', 'a', '2026-02-03 10:41:17'),
(9, 1234567890, 4, 'rpl 2', 'eternet mati', '2026-02-05 10:33:14'),
(10, 1234567890, 1, 'depan kelas 12', 'qq', '2026-02-05 21:20:25'),
(11, 12345, 3, 'sdf', 'sdf', '2026-02-06 09:57:31'),
(12, 12345, 5, 'sdf', 'laporan baru ', '2026-02-06 09:59:17'),
(13, 12345, 1, 'depan kelas 12', 'ssss', '2026-02-06 10:25:58'),
(14, 12345, 7, 'jkjkhj', 'vvbvb', '2026-02-06 10:48:21'),
(15, 12345, 2, 'x', 'x', '2026-02-06 10:55:29'),
(16, 12345, 2, 'jhj', 'nbh', '2026-02-06 11:13:38'),
(17, 12345, 6, 'mbbhg', 'hj', '2026-02-06 11:16:27'),
(18, 12345, 3, 'a', 'a', '2026-02-06 11:17:37'),
(19, 12345, 2, 'k', 'k', '2026-02-09 13:27:29'),
(20, 12345, 3, 'k', 'k', '2026-02-09 14:21:46'),
(21, 12345, 6, 'd', 'd', '2026-02-09 14:24:53'),
(22, 12345, 3, 's', 's', '2026-02-09 14:27:02'),
(23, 12345, 3, 'z', 'z', '2026-02-09 14:52:00'),
(24, 12345, 3, 'd', 's', '2026-02-09 14:55:58'),
(25, 12345, 6, 'x', 'x', '2026-02-09 15:00:22'),
(26, 12345, 5, 'xx', 'x', '2026-02-09 15:04:07'),
(27, 12345, 5, 'k', 'k', '2026-02-10 07:23:44'),
(28, 12345, 2, 'l', ';', '2026-02-10 08:21:48'),
(29, 12345, 5, 'b', 'b', '2026-02-10 14:24:19'),
(30, 12345, 7, 'l', 'l', '2026-02-13 02:43:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

DROP TABLE IF EXISTS `kategori`;
CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `ket_kategori` varchar(30) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `ket_kategori`) VALUES
(1, 'Kebersihan'),
(2, 'Fasilitas Kelas'),
(3, 'Sarana Prasarana'),
(5, 'Layanan Beasiswa'),
(6, 'Kurikulum & Pembelajaran'),
(7, 'Keamanan Sekolah'),
(8, 'Kegiatan Ekstrakurikuler'),
(9, 'Kesehatan (UKS)'),
(10, 'Layanan Kantin'),
(11, 'Lainnya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
CREATE TABLE IF NOT EXISTS `notifikasi` (
  `id_notif` int NOT NULL AUTO_INCREMENT,
  `id_aspirasi` int DEFAULT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `pesan` text,
  `is_read` tinyint(1) DEFAULT '0',
  `tgl_notif` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notif`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id_notif`, `id_aspirasi`, `nis`, `pesan`, `is_read`, `tgl_notif`) VALUES
(1, 7, '1234567890', 'Admin memperbarui feedback pada laporan #7', 1, '2026-02-03 03:37:59'),
(2, 8, '1234567890', 'Laporan #8 Anda sekarang berstatus: Ditolak', 1, '2026-02-03 03:41:31'),
(3, 9, '1234567890', 'Laporan #9 Anda sekarang berstatus: Proses', 1, '2026-02-05 03:33:32'),
(4, 9, '1234567890', 'Admin memperbarui feedback pada laporan #9', 1, '2026-02-05 03:33:42'),
(5, 9, '1234567890', 'Laporan #9 Anda sekarang berstatus: Proses', 0, '2026-02-05 14:02:15'),
(6, 9, '1234567890', 'Laporan #9 Anda sekarang berstatus: Ditolak', 0, '2026-02-05 14:02:42'),
(7, 2, '1234567890', 'Laporan #2 Anda sekarang berstatus: Proses', 0, '2026-02-05 14:14:21'),
(8, 2, '1234567890', 'Laporan #2 Anda sekarang berstatus: Proses', 0, '2026-02-05 14:19:51'),
(9, 9, '1234567890', 'Admin memberikan tanggapan pada laporan #9', 0, '2026-02-05 14:20:09'),
(10, 10, '1234567890', 'Laporan #10 Anda sekarang berstatus: Proses', 0, '2026-02-05 14:20:40'),
(11, 11, '12345', 'Admin memberikan tanggapan pada laporan #11', 0, '2026-02-06 02:58:07'),
(12, 11, '12345', 'Admin memberikan tanggapan pada laporan #11', 0, '2026-02-06 02:58:15'),
(13, 11, '12345', 'Laporan #11 Anda sekarang berstatus: Proses', 0, '2026-02-06 02:58:27'),
(14, 12, '12345', 'Admin memberikan tanggapan pada laporan #12', 0, '2026-02-06 02:59:28'),
(15, 12, '12345', 'Laporan #12 Anda sekarang berstatus: Proses', 0, '2026-02-06 02:59:52'),
(16, 12, '12345', 'Admin memberikan tanggapan pada laporan #12', 0, '2026-02-06 02:59:59'),
(17, 12, '12345', 'Admin memberikan tanggapan pada laporan #12', 0, '2026-02-06 03:01:07'),
(18, 13, '12345', 'Laporan #13 Anda sekarang berstatus: Proses', 0, '2026-02-06 03:26:26'),
(19, 13, '12345', 'Laporan #13 Anda sekarang berstatus: Proses', 0, '2026-02-06 03:27:19'),
(20, 13, '12345', 'Admin membalas laporan #13: saat ini sedang dilakukan perb...', 0, '2026-02-06 03:41:58'),
(21, 13, '12345', 'Status laporan #13 berubah menjadi: Proses', 0, '2026-02-06 03:42:07'),
(22, 13, '12345', 'Status laporan #13 berubah menjadi: Ditolak', 0, '2026-02-06 03:44:40'),
(23, 14, '12345', 'Status laporan #14 berubah menjadi: Proses', 0, '2026-02-06 03:48:37'),
(24, 14, '12345', 'Status laporan #14 berubah menjadi: Ditolak', 0, '2026-02-06 03:54:16'),
(25, 15, '12345', 'Status laporan #15 berubah menjadi: Proses', 0, '2026-02-06 03:55:39'),
(26, 11, '12345', 'Status laporan #11 berubah menjadi: Ditolak', 0, '2026-02-06 03:56:44'),
(27, 10, '1234567890', 'Status laporan #10 berubah menjadi: Ditolak', 0, '2026-02-06 03:56:53'),
(28, 15, '12345', 'Status laporan #15 berubah menjadi: Ditolak', 0, '2026-02-06 03:58:08'),
(29, 16, '12345', 'Status laporan #16 diperbarui menjadi: Proses', 0, '2026-02-06 04:13:49'),
(30, 16, '12345', 'Status laporan #16 diperbarui menjadi: Ditolak', 0, '2026-02-06 04:13:54'),
(31, 17, '12345', 'Status laporan #17 diperbarui menjadi: Proses', 0, '2026-02-06 04:16:37'),
(32, 18, '12345', 'Status laporan #18 berubah menjadi: Proses', 0, '2026-02-06 04:17:53'),
(33, 18, '12345', 'Status laporan #18 berubah menjadi: Ditolak', 0, '2026-02-06 04:17:57'),
(34, 2, '1234567890', 'Status laporan #2 berubah menjadi: Ditolak', 0, '2026-02-06 04:23:24'),
(35, 18, '12345', 'Maaf, laporan #18 DITOLAK dan ditutup.', 0, '2026-02-06 04:28:45'),
(36, 18, '12345', 'Status laporan #18 berubah menjadi: Proses', 0, '2026-02-06 04:29:29'),
(37, 18, '12345', 'Status laporan #18 berubah menjadi: Ditolak', 0, '2026-02-06 04:29:32'),
(38, 18, '12345', 'Status laporan #18 berubah menjadi: Proses', 0, '2026-02-06 04:29:34'),
(39, 18, '12345', 'Status laporan #18 berubah menjadi: Ditolak', 0, '2026-02-06 04:29:36'),
(40, 17, '12345', 'Laporan #17 Anda sekarang berstatus: Ditolak', 0, '2026-02-06 04:29:50'),
(41, 19, '12345', 'Status laporan #19 berubah menjadi: Proses', 0, '2026-02-09 06:36:29'),
(42, 19, '12345', 'Status laporan #19 berubah menjadi: Ditolak', 0, '2026-02-09 06:38:07'),
(43, 20, '12345', 'Admin membalas laporan #20: ya...', 0, '2026-02-09 07:22:06'),
(44, 20, '12345', 'Status laporan #20 diperbarui menjadi: Proses', 0, '2026-02-09 07:22:11'),
(45, 20, '12345', 'Admin membalas laporan #20: ya...', 0, '2026-02-09 07:22:16'),
(46, 20, '12345', 'Status laporan #20 diperbarui menjadi: Ditolak', 0, '2026-02-09 07:22:49'),
(47, 21, '12345', 'Status laporan #21 diperbarui menjadi: Proses', 0, '2026-02-09 07:25:01'),
(48, 22, '12345', 'Status laporan #22 diperbarui menjadi: Ditolak', 0, '2026-02-09 07:27:08'),
(49, 22, '12345', 'Status laporan #22 diperbarui menjadi: Ditolak', 0, '2026-02-09 07:32:32'),
(50, 22, '12345', 'Status laporan #22 diperbarui menjadi: Proses', 0, '2026-02-09 07:32:41'),
(51, 22, '12345', 'Status laporan #22 diperbarui menjadi: Ditolak', 0, '2026-02-09 07:32:43'),
(52, 22, '12345', 'Status laporan #22 diperbarui menjadi: Proses', 0, '2026-02-09 07:32:46'),
(53, 22, '12345', 'Status laporan #22 diperbarui menjadi: Ditolak', 0, '2026-02-09 07:32:49'),
(54, 22, '12345', 'Laporan #22 Anda telah: Ditolak', 0, '2026-02-09 07:39:09'),
(55, 22, '12345', 'Laporan #22 Anda telah: Proses', 0, '2026-02-09 07:39:11'),
(56, 22, '12345', 'Laporan #22 Anda telah: Ditolak', 0, '2026-02-09 07:39:13'),
(57, 22, '12345', 'Laporan #22 Anda telah: Ditolak', 0, '2026-02-09 07:39:16'),
(58, 22, '12345', 'Laporan #22 Anda telah: Proses', 0, '2026-02-09 07:39:25'),
(59, 22, '12345', 'Laporan #22 Anda telah: Ditolak', 0, '2026-02-09 07:39:26'),
(60, 22, '12345', 'Laporan #22 Anda telah: Ditolak', 0, '2026-02-09 07:39:29'),
(61, 22, '12345', 'Laporan #22 Anda telah: Proses', 0, '2026-02-09 07:39:33'),
(62, 22, '12345', 'Laporan #22 Anda telah: Ditolak', 0, '2026-02-09 07:39:36'),
(63, 22, '12345', 'Laporan #22 Anda telah: Proses', 0, '2026-02-09 07:39:38'),
(64, 22, '12345', 'Laporan #22 Anda telah: Ditolak', 0, '2026-02-09 07:39:40'),
(65, 22, '12345', 'Laporan #22 Anda telah: Proses', 0, '2026-02-09 07:39:41'),
(66, 22, '12345', 'Status laporan #22 diperbarui menjadi: Ditolak', 0, '2026-02-09 07:44:04'),
(67, 23, '12345', 'Status laporan #23 diperbarui menjadi: Ditolak', 0, '2026-02-09 07:52:05'),
(68, 24, '12345', 'Status laporan #24 diperbarui menjadi: Ditolak', 0, '2026-02-09 07:56:02'),
(69, 25, '12345', 'Status laporan #25 diperbarui menjadi: Proses', 0, '2026-02-09 08:00:35'),
(70, 25, '12345', 'Status laporan #25 diperbarui menjadi: Ditolak', 0, '2026-02-09 08:00:42'),
(71, 26, '12345', 'Status laporan #26 diperbarui menjadi: Proses', 0, '2026-02-09 08:04:14'),
(72, 26, '12345', 'Status laporan #26 diperbarui menjadi: Ditolak', 0, '2026-02-09 08:04:16'),
(73, 27, '12345', 'Status laporan #27 diperbarui menjadi: Proses', 0, '2026-02-10 00:23:59'),
(74, 27, '12345', 'Status laporan #27 diperbarui menjadi: Ditolak', 0, '2026-02-10 00:24:06'),
(75, 27, '12345', 'Admin membalas laporan #27: ooooo...', 0, '2026-02-10 00:24:19'),
(76, 28, '12345', 'Status laporan #28 diperbarui menjadi: Proses', 0, '2026-02-10 01:23:03'),
(77, 13, '12345', 'Admin membalas laporan #13: kjjnjndjfn\r\nrthejsjrksrwjjjr...', 0, '2026-02-10 02:35:22'),
(78, 27, '12345', 'Admin membalas laporan #27: m...', 0, '2026-02-10 02:35:28'),
(79, 27, '12345', 'Admin membalas laporan #27: hjsrhu\r\nfiwi\r\nekhwsj...', 0, '2026-02-10 02:35:36'),
(80, 19, '12345', 'Admin membalas laporan #19: mmm...', 0, '2026-02-10 03:05:55'),
(81, 24, '12345', 'Admin membalas laporan #24: fycycyc...', 0, '2026-02-10 03:17:00'),
(82, 27, '12345', 'Admin membalas laporan #27: jddhzcjxj\r\nczcmsx\r\n...', 0, '2026-02-10 06:37:22'),
(83, 27, '12345', 'Admin membalas laporan #27: jnbczxn...', 0, '2026-02-10 06:50:06'),
(84, 27, '12345', 'Admin membalas laporan #27: zncmzcnnz ...', 0, '2026-02-10 06:50:10'),
(85, 27, '12345', 'Admin membalas laporan #27: mandmnd...', 0, '2026-02-10 06:50:16'),
(86, 27, '12345', 'Admin membalas laporan #27: zzzzz...', 0, '2026-02-10 06:50:23'),
(87, 27, '12345', 'Admin membalas laporan #27: jhjhbbbbbbbbbbbbbbbbbbbbbbbbbb...', 0, '2026-02-10 07:08:27'),
(88, 27, '12345', 'Admin membalas laporan #27: nnn\r\n...', 0, '2026-02-10 07:16:26'),
(89, 29, '12345', 'Status laporan #29 diperbarui menjadi: Proses', 0, '2026-02-10 07:24:36'),
(90, 29, '12345', 'Admin membalas laporan #29: nnn...', 0, '2026-02-10 07:24:44'),
(91, 29, '12345', 'Status laporan #29 diperbarui menjadi: Ditolak', 0, '2026-02-11 03:44:10'),
(92, 29, '12345', 'Admin membalas laporan #29: mohon maaf tidak sesuai tolong...', 0, '2026-02-11 03:44:31'),
(93, 30, '12345', 'Status laporan #30 diperbarui menjadi: Proses', 0, '2026-02-12 19:44:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

DROP TABLE IF EXISTS `siswa`;
CREATE TABLE IF NOT EXISTS `siswa` (
  `nis` int NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`nis`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`nis`, `kelas`, `password`) VALUES
(1234567890, '11 pplg 2', '1'),
(111, 'xi pplg 3', '1'),
(11111111, 'xi pplg 3', '1'),
(22222222, 'XI', '123'),
(12345, '11 pplg 2', '123');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
