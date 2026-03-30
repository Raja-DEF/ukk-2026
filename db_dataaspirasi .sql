-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Mar 2026 pada 14.52
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_dataaspirasi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `input_aspirasi`
--

CREATE TABLE `input_aspirasi` (
  `id_pelaporan` int(5) NOT NULL,
  `nis` int(10) NOT NULL,
  `id_kategori` int(5) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `ket` varchar(50) NOT NULL,
  `penyelesaian` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'menunggu',
  `tanggal_input` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `input_aspirasi`
--

INSERT INTO `input_aspirasi` (`id_pelaporan`, `nis`, `id_kategori`, `lokasi`, `ket`, `penyelesaian`, `status`, `tanggal_input`) VALUES
(0, 71207, 5, 'Kantin SMKN 5 Telkom Banda Aceh ', 'Kurangnya ragam makanan yang mengakibatkan bosan', NULL, 'selesai', '2026-03-02 10:45:47'),
(1, 2023001001, 3, 'Depan Gedung A', 'Halaman sekolah banyak sampah berserakan', '', 'selesai', '2026-02-28 10:40:22'),
(2, 2023001002, 1, 'Kelas X-A', 'AC di kelas tidak berfungsi dengan baik', NULL, 'selesai', '2026-02-28 10:40:22'),
(3, 2023001003, 5, 'Kantin Utama', 'Harga makanan di kantin terlalu mahal', NULL, 'selesai', '2026-02-28 10:40:22'),
(4, 2023001004, 7, 'Toilet Lantai 1', 'Toilet putri sering tidak ada airnya', NULL, 'menunggu', '2026-02-28 10:40:22'),
(5, 2023001005, 6, 'Perpustakaan', 'Koleksi buku pelajaran sangat terbatas', NULL, 'menunggu', '2026-02-28 10:40:22'),
(6, 2023001006, 2, 'Kelas XI-A', 'Metode belajar monoton, kurang interaktif', NULL, 'menunggu', '2026-02-28 10:40:22'),
(7, 2023001007, 4, 'Lapangan Olahraga', 'Kegiatan pramuka perlu ditingkatkan', NULL, 'menunggu', '2026-02-28 10:40:22'),
(8, 2023001008, 8, 'Pintu Gerbang', 'Penjagaan keamanan di gerbang kurang ketat', NULL, 'menunggu', '2026-02-28 10:40:22'),
(9, 2023001009, 10, 'Ruang Guru', 'Guru sering terlambat masuk kelas', NULL, 'menunggu', '2026-02-28 10:40:22'),
(10, 2023001010, 11, 'Ruang Kelas XII-A', 'Jadwal pelajaran terlalu padat', NULL, 'menunggu', '2026-02-28 10:40:22'),
(11, 2023001011, 9, 'Halte Depan Sekolah', 'Tidak ada antar jemput khusus siswa', NULL, 'menunggu', '2026-02-28 10:40:22'),
(12, 2023001012, 12, 'Aula Sekolah', 'Aula jarang digunakan untuk kegiatan siswa', '-', 'proses', '2026-02-28 10:40:22'),
(13, 71207, 2, 'kelas 10', 'jelek', NULL, 'selesai', '2026-03-11 10:12:02'),
(14, 211207, 0, 'Taman', 'rusak', NULL, 'menunggu', '2026-03-30 19:15:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int(15) NOT NULL,
  `username` text NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `username`, `password`) VALUES
(1, 'Rajaul', 'ef24143f286c6c8f06347936a1b6aaaa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_aspirasi`
--

CREATE TABLE `tb_aspirasi` (
  `id_aspirasi` int(5) NOT NULL,
  `status` enum('Menunggu','Proses','Selesai') NOT NULL,
  `id_pelaporan` int(5) NOT NULL,
  `feedback` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_aspirasi`
--

INSERT INTO `tb_aspirasi` (`id_aspirasi`, `status`, `id_pelaporan`, `feedback`) VALUES
(0, 'Selesai', 0, 'Baik,akan ditindak lanjuti'),
(1, 'Selesai', 1, 'Telah dilakukan pembersihan rutin setiap pagi oleh petugas kebersihan'),
(2, 'Selesai', 2, 'Sedang dalam proses perbaikan AC oleh teknisi'),
(3, 'Selesai', 3, 'selesai ya'),
(4, 'Selesai', 4, 'Perbaikan pipa air telah dilakukan oleh bagian sarana prasarana'),
(5, 'Proses', 5, 'Sedang dilakukan pengadaan buku baru untuk perpustakaan'),
(6, 'Menunggu', 6, ''),
(7, 'Selesai', 7, 'Jadwal pramuka telah diperbarui dan pembina baru telah ditunjuk'),
(8, 'Proses', 8, 'Koordinasi dengan satpam sedang dilakukan'),
(9, 'Menunggu', 9, ''),
(10, 'Selesai', 10, 'Jadwal pelajaran telah direvisi oleh bagian kurikulum'),
(11, 'Menunggu', 11, ''),
(12, 'Proses', 12, 'Sedang dijadwalkan kegiatan siswa untuk penggunaan aula'),
(13, 'Selesai', 13, 'akan kami proses'),
(14, 'Menunggu', 14, 'baik diproses');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int(5) NOT NULL,
  `ket_kategori` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_kategori`
--

INSERT INTO `tb_kategori` (`id_kategori`, `ket_kategori`) VALUES
(0, 'Kamar Kecil'),
(1, 'Fasilitas Sekolah'),
(2, 'Kegiatan Belajar'),
(3, 'Lingkungan Sekolah'),
(4, 'Ekstrakurikuler'),
(5, 'Kantin'),
(6, 'Perpustakaan'),
(7, 'Toilet'),
(8, 'Keamanan'),
(9, 'Transportasi'),
(10, 'Guru dan Staf'),
(11, 'Kurikulum'),
(12, 'Lainnya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `nis` int(10) NOT NULL,
  `kelas` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_siswa`
--

INSERT INTO `tb_siswa` (`nis`, `kelas`) VALUES
(71207, 'XII-RPL 1'),
(211207, 'XII-4'),
(2023001001, 'X-A'),
(2023001002, 'X-A'),
(2023001003, 'X-B'),
(2023001004, 'X-B'),
(2023001005, 'XI-A'),
(2023001006, 'XI-A'),
(2023001007, 'XI-B'),
(2023001008, 'XI-B'),
(2023001009, 'XII-A'),
(2023001010, 'XII-A'),
(2023001011, 'XII-B'),
(2023001012, 'XII-B');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `input_aspirasi`
--
ALTER TABLE `input_aspirasi`
  ADD PRIMARY KEY (`id_pelaporan`),
  ADD KEY `nis` (`nis`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `tb_aspirasi`
--
ALTER TABLE `tb_aspirasi`
  ADD PRIMARY KEY (`id_aspirasi`),
  ADD KEY `id_pelaporan` (`id_pelaporan`);

--
-- Indeks untuk tabel `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`nis`);

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `input_aspirasi`
--
ALTER TABLE `input_aspirasi`
  ADD CONSTRAINT `input_aspirasi_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `tb_kategori` (`id_kategori`),
  ADD CONSTRAINT `input_aspirasi_ibfk_2` FOREIGN KEY (`nis`) REFERENCES `tb_siswa` (`nis`);

--
-- Ketidakleluasaan untuk tabel `tb_aspirasi`
--
ALTER TABLE `tb_aspirasi`
  ADD CONSTRAINT `tb_aspirasi_ibfk_1` FOREIGN KEY (`id_pelaporan`) REFERENCES `input_aspirasi` (`id_pelaporan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
