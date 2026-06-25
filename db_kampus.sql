-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Bulan Mei 2026 pada 17.20
-- Versi server: 10.6.7-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kampus`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) DEFAULT NULL,
  `jadwal_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id`, `mahasiswa_id`, `jadwal_id`, `status`, `tanggal`) VALUES
(5, 13, 5, 'Hadir', '2026-05-05 11:57:36'),
(6, 13, 4, 'Hadir', '2026-05-06 00:24:05'),
(7, 14, 4, 'Hadir', '2026-05-06 00:25:39'),
(8, 15, 4, 'Belum', '2026-05-06 00:28:49'),
(9, 16, 4, 'Belum', '2026-05-06 00:28:49'),
(10, 21, 12, 'Hadir', '2026-05-06 03:28:09'),
(11, 23, 12, 'Belum', '2026-05-06 03:28:47'),
(12, 22, 12, 'Belum', '2026-05-06 03:28:47'),
(13, 13, 9, 'Belum', '2026-05-06 06:01:28'),
(14, 14, 9, 'Belum', '2026-05-06 06:01:28'),
(15, 15, 9, 'Belum', '2026-05-06 06:01:28'),
(16, 16, 9, 'Belum', '2026-05-06 06:01:28'),
(17, 14, 5, 'Belum', '2026-05-06 06:16:23'),
(18, 15, 5, 'Belum', '2026-05-06 06:16:23'),
(19, 16, 5, 'Belum', '2026-05-06 06:16:23'),
(20, 16, 13, 'Hadir', '2026-05-06 06:23:37'),
(21, 13, 14, 'Belum', '2026-05-06 06:26:57'),
(22, 14, 14, 'Belum', '2026-05-06 06:26:57'),
(23, 15, 14, 'Belum', '2026-05-06 06:26:57'),
(24, 16, 14, 'Belum', '2026-05-06 06:26:57'),
(25, 15, 13, 'Hadir', '2026-05-06 06:33:34'),
(26, 14, 13, 'Hadir', '2026-05-06 06:42:17'),
(27, 9, 15, 'Hadir', '2026-05-06 07:03:36'),
(28, 10, 15, 'Hadir', '2026-05-06 07:10:53'),
(29, 11, 15, 'Hadir', '2026-05-06 07:11:56'),
(30, 21, 20, 'Hadir', '2026-05-06 10:31:43'),
(31, 13, 21, 'Hadir', '2026-05-06 11:17:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `id` int(11) NOT NULL,
  `nidn` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `prodi` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`id`, `nidn`, `nama`, `prodi`, `email`, `telp`, `alamat`, `foto`) VALUES
(6, '0023087203', 'Prof. Dr. Ir. H. Ahmad Fauzi, M.Kom.', 'Pendidikan Teknologi Informasi', '0023087203@unesa.ac.id', '081230210953', 'Gresik', ''),
(7, '0628039201', 'Andini Wahyuni, S.Kom., M.Sc.', 'Sistem Informasi', '0628039201@unesa.ac.id', '081230210952', 'Surabaya', ''),
(8, '0702108703', 'Fahmi Idris, S.Pd., M.T.', 'Sistem Informasi', '0702108703@unesa.ac.id', '081230210953', 'Surabaya', NULL),
(10, '0311098104', 'Dr. Sri Wahyuni, S.Kom., M.M.', 'Teknik Informatika', '0311098104@gmail.com', '081230210953', 'Surabaya', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `matkul_id` int(11) DEFAULT NULL,
  `dosen_id` int(11) DEFAULT NULL,
  `prodi` varchar(100) DEFAULT NULL,
  `angkatan` varchar(10) DEFAULT NULL,
  `kelas` varchar(5) DEFAULT NULL,
  `hari` varchar(20) DEFAULT NULL,
  `jam` varchar(20) DEFAULT NULL,
  `ruangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jadwal`
--

INSERT INTO `jadwal` (`id`, `matkul_id`, `dosen_id`, `prodi`, `angkatan`, `kelas`, `hari`, `jam`, `ruangan`) VALUES
(4, 6, 6, 'Pendidikan Teknologi Informasi', '2024', 'B', 'Senin', '08.00-10.000', '10.1.01'),
(5, 7, 6, 'Pendidikan Teknologi Informasi', '2024', 'B', 'Selasa', '09.00-11.000', '10.1.02'),
(6, 8, 6, 'Pendidikan Teknologi Informasi', '2024', 'B', 'Rabu', '11.000-13.00', '10.1.03'),
(7, 9, 6, 'Pendidikan Teknologi Informasi', '2024', 'B', 'Kamis', '13.00-15.00', '10.1.04'),
(8, 10, 6, 'Pendidikan Teknologi Informasi', '2024', 'B', 'Jumat', '11.000-13.00', '10.1.03'),
(9, 11, 6, 'Pendidikan Teknologi Informasi', '2024', 'B', 'Selasa', '08.00-10.000', '10.1.01'),
(10, 8, 9, 'Pendidikan Teknologi Informasi', '2026', 'A', 'Senin', '09.00-10.00', 'online'),
(11, 11, 6, 'Teknik Informatika', '2026', 'A', 'Senin', '09.00-10.00', 'online'),
(12, 6, 8, 'Teknik Informatika', '2021', 'C', 'Senin', '09.00-10.00', 'online'),
(13, 11, 6, 'Pendidikan Teknologi Informasi', '2024', 'E', 'Kamis', '09.00-10.00', 'A10.0.0.0'),
(14, 11, 6, 'Pendidikan Teknologi Informasi', '2024', 'B', 'Jumat', '09.00-10.00', 'A10.0.0.0'),
(15, 11, 6, 'Pendidikan Teknologi Informasi', '2024', 'A', 'Senin', '09.00-10.00', 'A10.0.0.0'),
(16, 6, 7, 'Sistem Informasi', '2024', 'E', 'Rabu', '09.00-10.00', 'online'),
(17, 10, 7, 'Sistem Informasi', '2024', 'C', 'Selasa', '09.00-10.00', 'online'),
(18, 6, 7, 'Sistem Informasi', '2023', 'C', 'Senin', '09.00-10.00', 'online'),
(19, 11, 7, 'Pendidikan Teknologi Informasi', '2024', 'A', 'Senin', '09.00-10.00', 'online'),
(20, 8, 7, 'Teknik Informatika', '2021', 'C', 'Selasa', '09.00-10.00', 'A10.0.0.0'),
(21, 9, 9, 'Pendidikan Teknologi Informasi', '2024', 'B', 'Jumat', '09.00-10.00', 'online');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `prodi` varchar(100) DEFAULT NULL,
  `kelas` varchar(5) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `prodi`, `kelas`, `email`, `telp`, `alamat`, `foto`) VALUES
(9, '24050974001', 'Aditya Pratama', 'Pendidikan Teknologi Informasi', 'A', '24050974001@mhs.unesa.ac.id', '081230210953', 'Kediri', NULL),
(10, '24050974002', 'Bagas Setiawan', 'Pendidikan Teknologi Informasi', 'A', '24050974002@mhs.unesa.ac.id', '081230210900', 'Bojonegoro', NULL),
(11, '24050974003', 'Clarissa Amadea', 'Pendidikan Teknologi Informasi', 'A', '24050974003@mhs.unesa.ac.id', '081230210978', 'Surabaya', ''),
(13, '24050974011', 'Alyssa Putri', 'Pendidikan Teknologi Informasi', 'B', '24050974011@mhs.unesa.ac.id', '081230210951', 'Mojokerto', NULL),
(14, '24050974012', 'Deva Mahendra', 'Pendidikan Teknologi Informasi', 'B', '24050974012@mhs.unesa.ac.id', '081230210956', 'Mojokerto', NULL),
(15, '24050974013', 'Farrel Ibrahim', 'Pendidikan Teknologi Informasi', 'B', '24050974013@mhs.unesa.ac.id', '081230210900', 'Gresik', NULL),
(16, '24050974014', 'Rizky Fauzi', 'Pendidikan Teknologi Informasi', 'B', '24050974014@mhs.unesa.ac.id', '081230210978', 'Mojokerto', NULL),
(17, '23050974001', 'Ahmad Zaki', 'Sistem Informasi', 'D', '23050974001@mhs.unesa.ac.id', '081230210900', 'Lamongan', NULL),
(18, '23050974002', 'Bianka Larasati', 'Sistem Informasi', 'D', '23050974002@mhs.unesa.ac.id', '081230210951', 'Bojonegoro', NULL),
(19, '23050974003', 'Candra Wijaya', 'Sistem Informasi', 'D', '23050974003@mhs.unesa.ac.id', '081230210956', 'Gresik', NULL),
(21, '21050974001', 'Erico Sanjaya', 'Teknik Informatika', 'C', '21050974001@mhs.unesa.ac.id', '081230210953', 'Sidoarjo', NULL),
(22, '21050974002', 'Fatika Sari', 'Teknik Informatika', 'C', '21050974002@mhs.unesa.ac.id', '081230210978', 'Sidoarjo', NULL),
(23, '21050974003', 'Galih Rakasiwi', 'Teknik Informatika', 'C', '21050974003@mhs.unesa.ac.id', '081230210951', 'Mojokerto', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `matkul`
--

CREATE TABLE `matkul` (
  `id` int(11) NOT NULL,
  `kode_matkul` varchar(20) NOT NULL,
  `nama_matkul` varchar(100) NOT NULL,
  `sks` int(11) NOT NULL,
  `prodi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `matkul`
--

INSERT INTO `matkul` (`id`, `kode_matkul`, `nama_matkul`, `sks`, `prodi`) VALUES
(6, '0121', 'Algoritma dan Struktur Data', 3, 'Pendidikan Teknologi Informasi'),
(7, '0122', 'Dasar Pemrograman', 3, 'Pendidikan Teknologi Informasi'),
(8, '0123', 'Arsitektur dan Organisasi Komputer', 2, 'Pendidikan Teknologi Informasi'),
(9, '0124', 'Basis Data', 3, 'Pendidikan Teknologi Informasi'),
(10, '0124', 'Jaringan Komputer dan Komunikasi Data', 2, 'Pendidikan Teknologi Informasi'),
(11, '0126', 'Pemrograman Web', 3, 'Pendidikan Teknologi Informasi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`) VALUES
(15, 'admin@gmail.com', 'admin123', 'admin'),
(16, '24050974001@mhs.unesa.ac.id', '24050974001', 'mahasiswa'),
(17, '24050974002@mhs.unesa.ac.id', '24050974002', 'mahasiswa'),
(18, '24050974003@mhs.unesa.ac.id', '24050974003', 'mahasiswa'),
(19, '24050974004@mhs.unesa.ac.id', '24050974004', 'mahasiswa'),
(20, '24050974011@mhs.unesa.ac.id', '24050974011', 'mahasiswa'),
(21, '24050974012@mhs.unesa.ac.id', '24050974012', 'mahasiswa'),
(22, '24050974013@mhs.unesa.ac.id', '24050974013', 'mahasiswa'),
(23, '24050974014@mhs.unesa.ac.id', '24050974014', 'mahasiswa'),
(24, '23050974001@mhs.unesa.ac.id', '23050974001', 'mahasiswa'),
(25, '23050974002@mhs.unesa.ac.id', '23050974002', 'mahasiswa'),
(26, '23050974003@mhs.unesa.ac.id', '23050974003', 'mahasiswa'),
(27, '23050974004@mhs.unesa.ac.id', '23050974004', 'mahasiswa'),
(28, '21050974001@mhs.unesa.ac.id', '21050974001', 'mahasiswa'),
(32, '0023087203@unesa.ac.id', '0023087203', 'dosen'),
(33, '0628039201@unesa.ac.id', '0628039201', 'dosen'),
(34, '0702108703@unesa.ac.id', '0702108703', 'dosen'),
(36, '0311098104@gmail.com', '0311098104', 'dosen');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `matkul`
--
ALTER TABLE `matkul`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `matkul`
--
ALTER TABLE `matkul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
