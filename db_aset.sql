-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 07:53 AM
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
-- Database: `db_aset`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_aset`
--

CREATE TABLE `tb_aset` (
  `id` int(11) NOT NULL,
  `kd_aset` varchar(20) NOT NULL,
  `no_aset` varchar(20) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `merk` varchar(100) DEFAULT NULL,
  `kategori` varchar(10) DEFAULT NULL,
  `tgl_peroleh` date DEFAULT NULL,
  `harga_peroleh` double DEFAULT 0,
  `umur_pakai` int(11) DEFAULT 0,
  `lokasi` varchar(10) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'BAIK',
  `keterangan` text DEFAULT NULL,
  `nilai_sekarang` double DEFAULT 0,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `petugas` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_aset`
--
ALTER TABLE `tb_aset`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kd_aset` (`kd_aset`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_aset`
--
ALTER TABLE `tb_aset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
