-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2025 at 06:06 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab_pplg`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guru','siswa') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Admin Lab', 'admin', '0192023a7bbd73250516f069df18b500', 'admin', '2025-11-18 03:25:17'),
(2, 'Ibni Yubia', 'Ibni', '5e8667a439c68f5145dd2fcbecf02209', 'siswa', '2025-11-18 04:36:35'),
(3, 'Pak Syahid', 'Syahid', 'e10adc3949ba59abbe56e057f20f883e', 'guru', '2025-11-18 05:15:29');

-- --------------------------------------------------------

--
-- Table structure for table `alat`
--

CREATE TABLE `alat` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `jumlah` int NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `alat`
--

INSERT INTO `alat` (`id`, `nama`, `kategori`, `jumlah`, `deskripsi`, `created_at`) VALUES
(1, 'Laptop ASUS', 'Elektronik', 10, 'Laptop untuk praktikum programming', '2025-11-18 03:25:17'),
(2, 'Mouse Logitech', 'Elektronik', 15, 'Mouse wireless untuk komputer lab', '2025-11-18 03:25:17'),
(3, 'Router TP-Link', 'Jaringan', 5, 'Router untuk praktikum jaringan', '2025-11-18 03:25:17'),
(4, 'Kabel LAN', 'Jaringan', 20, 'Kabel LAN UTP kategori 6', '2025-11-18 03:25:17'),
(5, 'Multimeter', 'Elektronik', 8, 'Alat ukur listrik digital', '2025-11-18 03:25:17'),
(6, 'Keyboard', 'Elektronik', 5, '', '2025-11-18 03:59:05');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `alat_id` int NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `jumlah` int NOT NULL,
  `keperluan` text NOT NULL,
  `status` enum('pending','approved','dipinjam','dikembalikan','ditolak') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `user_id`, `alat_id`, `tanggal_pinjam`, `tanggal_kembali`, `jumlah`, `keperluan`, `status`, `created_at`) VALUES
(1, 2, 1, '2025-11-18', '2026-01-01', 1, 'UUK', 'dikembalikan', '2025-11-18 04:43:55'),
(2, 2, 1, '2025-11-18', '2025-11-29', 1, 'wads', 'dikembalikan', '2025-11-18 05:02:24'),
(3, 2, 4, '2025-11-18', '2025-11-18', 2, 'Keperluan Net untuk pembelajaran', 'dikembalikan', '2025-11-18 05:44:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `alat_id` (`alat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `alat`
--
ALTER TABLE `alat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `akun` (`id`),
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`alat_id`) REFERENCES `alat` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
