-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2025 at 01:33 AM
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
-- Database: `simbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(5) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `tanggal_input` date NOT NULL DEFAULT current_timestamp(),
  `gambar` varchar(100) NOT NULL,
  `id_kategori` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `genre`, `penulis`, `penerbit`, `tanggal_input`, `gambar`, `id_kategori`) VALUES
(10, 'Secret of The Lost World', 'Fantasi', 'Tori Kate', 'Fiksio', '2025-11-28', '_Secret of The Lost World.jpeg', 1),
(11, 'Soleil the Superhero', 'Fantasi', 'Olivia Zapo', 'Fiksio', '2025-11-28', 'b.jpeg', 1),
(13, 'Gassy Cassie', 'Slice of Life', 'Alicia Acosta', 'Dimdim', '2025-11-29', 'Gassy Cassie.jpg', 5),
(14, 'Tonya and Her Perfect Tea', 'Fantasi', 'Alina Slyshik', 'Dimdim', '2025-11-29', 'Tonya and Her Perfect Tea.jpg', 6),
(15, 'Castle in the Air', 'Romantis', 'Diana Wyne Jones', 'Fiobit', '2025-11-29', 'Castle in the Air.jpg', 6),
(16, 'The Land of Roar', 'Fantasi', 'Jenny Mclachlan', 'Dudu', '2025-11-29', 'The Land of Roar.jpg', 6);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(5) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `tanggal_input_kategori` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `tanggal_input_kategori`) VALUES
(1, 'Fiksi', '2025-11-29'),
(2, 'Non Fiksi', '2025-11-29'),
(3, 'Komik', '2025-11-29'),
(5, 'Buku Anak', '2025-11-29'),
(6, 'Novel', '2025-11-29');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(5) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `password`) VALUES
(4, 'mei', 'mei@gmail.com', '$2y$10$jAS1aXEDw9LP1rPLhmbEIOd3XH8upoN36vzDXCyWHxo4JGndvB.OW'),
(5, 'manta', 'man@gmail.com', '$2y$10$khpIP5j88W4L0aO7SfGz..Zu0N3lts56gD0uEJWZKb5n0z0Bw.J5S'),
(6, 'febri', 'feb@gmail.com', '$2y$10$K0nD0fLiirn5HJJdy2rBFecWktm9WNv/1y07iCPDqgxl2eZA2IXYe');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
