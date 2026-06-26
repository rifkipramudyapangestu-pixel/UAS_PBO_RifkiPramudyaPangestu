-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 26, 2026 at 01:02 AM
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
-- Database: `db_uas_pbo_trpl1a_rifkipramudyapangestu`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_mahasiswa`
--

CREATE TABLE `tabel_mahasiswa` (
  `id_mahasiswa` int NOT NULL,
  `nama_mahasiswa` varchar(100) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `semester` int NOT NULL,
  `tarif_ukt_nominal` decimal(12,2) NOT NULL,
  `jenis_pembayaran` enum('Mandiri','Bidikmisi','Prestasi') NOT NULL,
  `golongan_ukt` varchar(20) DEFAULT NULL,
  `nama_wali` varchar(100) DEFAULT NULL,
  `nomor_kip_kuliah` varchar(30) DEFAULT NULL,
  `dana_saku_subsidi` decimal(12,2) DEFAULT NULL,
  `nama_instansi_beasiswa` varchar(100) DEFAULT NULL,
  `minimal_ipk_syarat` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_mahasiswa`
--

INSERT INTO `tabel_mahasiswa` (`id_mahasiswa`, `nama_mahasiswa`, `nim`, `semester`, `tarif_ukt_nominal`, `jenis_pembayaran`, `golongan_ukt`, `nama_wali`, `nomor_kip_kuliah`, `dana_saku_subsidi`, `nama_instansi_beasiswa`, `minimal_ipk_syarat`) VALUES
(1, 'Rifki Pramudya Pangestu', '2401001', 2, '3500000.00', 'Mandiri', 'Golongan 3', 'Budi Pangestu', NULL, NULL, NULL, NULL),
(2, 'Ahmad Fauzan', '2401002', 2, '4000000.00', 'Mandiri', 'Golongan 4', 'Sutrisno', NULL, NULL, NULL, NULL),
(3, 'Dimas Saputra', '2401003', 4, '3000000.00', 'Mandiri', 'Golongan 2', 'Hendra Saputra', NULL, NULL, NULL, NULL),
(4, 'Fajar Ramadhan', '2401004', 4, '4500000.00', 'Mandiri', 'Golongan 5', 'Rahmat', NULL, NULL, NULL, NULL),
(5, 'Naufal Rizky', '2401005', 6, '3500000.00', 'Mandiri', 'Golongan 3', 'Agus Salim', NULL, NULL, NULL, NULL),
(6, 'Raka Pratama', '2401006', 6, '5000000.00', 'Mandiri', 'Golongan 6', 'Yanto Pratama', NULL, NULL, NULL, NULL),
(7, 'Ilham Maulana', '2401007', 8, '4000000.00', 'Mandiri', 'Golongan 4', 'Mahmud', NULL, NULL, NULL, NULL),
(8, 'Siti Nurhaliza', '2401008', 2, '0.00', 'Bidikmisi', NULL, NULL, 'KIP2024001', '700000.00', NULL, NULL),
(9, 'Aulia Rahma', '2401009', 2, '0.00', 'Bidikmisi', NULL, NULL, 'KIP2024002', '750000.00', NULL, NULL),
(10, 'Putri Lestari', '2401010', 4, '0.00', 'Bidikmisi', NULL, NULL, 'KIP2024003', '700000.00', NULL, NULL),
(11, 'Maya Salsabila', '2401011', 4, '0.00', 'Bidikmisi', NULL, NULL, 'KIP2024004', '800000.00', NULL, NULL),
(12, 'Rani Anggraini', '2401012', 6, '0.00', 'Bidikmisi', NULL, NULL, 'KIP2024005', '750000.00', NULL, NULL),
(13, 'Dewi Kartika', '2401013', 6, '0.00', 'Bidikmisi', NULL, NULL, 'KIP2024006', '700000.00', NULL, NULL),
(14, 'Lina Marlina', '2401014', 8, '0.00', 'Bidikmisi', NULL, NULL, 'KIP2024007', '850000.00', NULL, NULL),
(15, 'Bagas Aditya', '2401015', 2, '1000000.00', 'Prestasi', NULL, NULL, NULL, NULL, 'Beasiswa Akademik Kampus', '3.50'),
(16, 'Yoga Firmansyah', '2401016', 2, '1500000.00', 'Prestasi', NULL, NULL, NULL, NULL, 'Beasiswa Yayasan Pendidikan', '3.60'),
(17, 'Nadia Putri', '2401017', 4, '1000000.00', 'Prestasi', NULL, NULL, NULL, NULL, 'Beasiswa Prestasi Nasional', '3.75'),
(18, 'Kevin Saputra', '2401018', 4, '2000000.00', 'Prestasi', NULL, NULL, NULL, NULL, 'Beasiswa Mitra Industri', '3.40'),
(19, 'Intan Permata', '2401019', 6, '1500000.00', 'Prestasi', NULL, NULL, NULL, NULL, 'Beasiswa Bank Indonesia', '3.50'),
(20, 'Rizky Maulana', '2401020', 8, '1000000.00', 'Prestasi', NULL, NULL, NULL, NULL, 'Beasiswa Unggulan Kampus', '3.70');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_mahasiswa`
--
ALTER TABLE `tabel_mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `nim` (`nim`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_mahasiswa`
--
ALTER TABLE `tabel_mahasiswa`
  MODIFY `id_mahasiswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
