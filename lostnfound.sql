-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2026 at 08:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lostnfound`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Elektronik'),
(2, 'Dokumen Penting'),
(3, 'Dompet & Kartu'),
(4, 'Kunci'),
(5, 'Aksesoris'),
(6, 'Tas & Pakaian'),
(7, 'Perlengkapan Ibadah'),
(8, 'Lain-lain');

-- --------------------------------------------------------

--
-- Table structure for table `claims`
--

CREATE TABLE `claims` (
  `id_klaim` int(11) NOT NULL,
  `id_item` int(11) DEFAULT NULL,
  `id_user_pemilik` int(11) DEFAULT NULL,
  `bukti_kepemilikan` text DEFAULT NULL,
  `tanggal_klaim` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_klaim` enum('proses','disetujui','ditolak') DEFAULT 'proses'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `claims`
--

INSERT INTO `claims` (`id_klaim`, `id_item`, `id_user_pemilik`, `bukti_kepemilikan`, `tanggal_klaim`, `status_klaim`) VALUES
(4, 9, 2, '695a6a7250668.jpg', '2026-01-04 07:26:10', ''),
(5, 3, 2, '695a735b90778.jpeg', '2026-01-04 08:04:11', ''),
(6, 8, 3, '695a8e38e5cf0.jpg', '2026-01-04 09:58:48', ''),
(7, 9, 3, '695a92dd9dddc.jpg', '2026-01-04 10:18:37', '');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id_item` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto_barang` varchar(255) DEFAULT NULL,
  `lokasi_temuan` varchar(255) DEFAULT NULL,
  `tanggal_temuan` date DEFAULT NULL,
  `status` enum('pending','tersedia','diklaim','selesai') DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cp` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id_item`, `id_user`, `id_kategori`, `nama_barang`, `deskripsi`, `foto_barang`, `lokasi_temuan`, `tanggal_temuan`, `status`, `created_at`, `cp`) VALUES
(3, 5, 3, 'Dompet', 'Warna pink dengan stiker badut', 'Dompet.jpeg', 'Ruang Kelas PSTI 3', '2026-01-01', 'selesai', '2026-01-04 03:10:38', ''),
(8, 2, 5, 'Jam', 'Coklat tua bergerigi', '6959f0a512757.jpeg', 'Masjid', '2026-01-02', 'selesai', '2026-01-04 04:46:29', ''),
(9, 3, 5, 'Gelang', 'Beads hitam', '6959fc533998d.jpeg', 'Ruang Kelas PSTI 3', '2026-01-01', 'selesai', '2026-01-04 05:36:19', ''),
(16, 2, 8, 'Botol MInum', 'Warna navy bahan stainles', '695a5c6e2353c.jpeg', 'Masjid', '2026-01-02', 'tersedia', '2026-01-04 12:26:22', ''),
(17, 3, 5, 'Kacamata', 'Lensa dan frame hitam', '695a8d4c998cb.jpg', 'Kantin', '2026-01-01', 'tersedia', '2026-01-04 15:54:52', '6281295055033'),
(18, 7, 5, 'Cincin', 'Perak', '695aba91a9ee0.jpeg', 'Taman', '2026-01-03', 'tersedia', '2026-01-04 19:08:01', '6287657988908'),
(19, 7, 8, 'Helm', 'Berwarna putih', '695abaf27a5e6.jpeg', 'Parkiran', '2026-01-05', 'tersedia', '2026-01-04 19:09:38', '6289098765709');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `whatsapp` varchar(30) NOT NULL,
  `foto_profil` varchar(255) DEFAULT 'default.png',
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) NOT NULL,
  `bio` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `whatsapp`, `foto_profil`, `role`, `created_at`, `email`, `bio`) VALUES
(2, 'admin', '$2y$10$EWqE.Vsn1k5ryI5n0aJQVec2RR6xd1uQ41ZunD1fnK6DvkRfs2fBi', '', '695a61d647988.jpeg', 'admin', '2026-01-03 18:14:12', 'irtup15@upi.edu', ''),
(3, 'maria', '$2y$10$PWBjzofE0hsbQr6.cm84/.aygjloFeTjCgvXjESzyA7oR/j.nt3ay', '', '695a3fa8da1c7.jpg', 'user', '2026-01-03 19:08:20', 'maria@gmail.com', 'Suka bercocok tanam'),
(4, 'relon', '$2y$10$xg.cQ.gfeFCb.K62tVmYRe/.kW5YkElKbZDWXcZZXRRcAUbAleWnC', '62345676576', 'default.png', 'user', '2026-01-04 02:07:49', 'relon@gmail.com', ''),
(5, 'user', '$2y$10$9fUBU.wTf9EMZOD37jKQiekCJGcnshPSfINWj.ix9mj.v/0dafG/q', '623456543345', 'default.png', 'user', '2026-01-04 02:11:33', 'user@gmail.com', ''),
(6, 'suki', '$2y$10$4xtG95ne3VolzOTjFY2NauDbkpeeEL3A3Tkt3hN.0IHHxJ67.PsqW', '', '695a4c6c92568.jpg', 'user', '2026-01-04 11:08:55', 'suki@gmail.com', ''),
(7, 'michel', '$2y$10$79ob7ts8NHcO0yinyK7JoOMfW9N47HbeOF7dfej4NOLRiue3heur2', '', '695ab96eae540.jpg', 'user', '2026-01-04 19:01:15', 'mic@gmail.com', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `claims`
--
ALTER TABLE `claims`
  ADD PRIMARY KEY (`id_klaim`),
  ADD KEY `id_item` (`id_item`),
  ADD KEY `id_user_pemilik` (`id_user_pemilik`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `claims`
--
ALTER TABLE `claims`
  MODIFY `id_klaim` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `claims`
--
ALTER TABLE `claims`
  ADD CONSTRAINT `claims_ibfk_1` FOREIGN KEY (`id_item`) REFERENCES `items` (`id_item`) ON DELETE CASCADE,
  ADD CONSTRAINT `claims_ibfk_2` FOREIGN KEY (`id_user_pemilik`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `categories` (`id_kategori`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
