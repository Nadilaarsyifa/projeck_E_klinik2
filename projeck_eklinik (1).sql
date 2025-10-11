-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2025 at 02:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projeck_eklinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_medis_mahasiswa`
--

CREATE TABLE `data_medis_mahasiswa` (
  `id_data_medis` int(11) NOT NULL,
  `nim` bigint(15) DEFAULT NULL,
  `alergi` text DEFAULT NULL,
  `gol_dar` varchar(5) DEFAULT NULL,
  `riwayat_penyakit` text DEFAULT NULL,
  `tinggi_badan` float DEFAULT NULL,
  `berat_badan` float DEFAULT NULL,
  `alat_bantu` varchar(100) DEFAULT NULL,
  `kontak_darurat` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id_konsultasi` int(11) NOT NULL,
  `tgl_konsultasi` datetime DEFAULT current_timestamp(),
  `keluhan_utama` text DEFAULT NULL,
  `lama_keluhan` text DEFAULT NULL,
  `riwayat_pengobatan_sendiri` text DEFAULT NULL,
  `catatan_mahasiswa` text DEFAULT NULL,
  `nim` bigint(15) DEFAULT NULL,
  `id_petugas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nim` bigint(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `prodi` varchar(100) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `petugas_klinik`
--

CREATE TABLE `petugas_klinik` (
  `id_petugas` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `spesialis` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tindak_lanjut`
--

CREATE TABLE `tindak_lanjut` (
  `id_tindak_lanjut` int(11) NOT NULL,
  `tgl_tindak_lanjut` datetime DEFAULT current_timestamp(),
  `diagnosa` text DEFAULT NULL,
  `resep_obat` text DEFAULT NULL,
  `saran_perawatan` text DEFAULT NULL,
  `id_konsultasi` int(11) DEFAULT NULL,
  `nim` bigint(15) DEFAULT NULL,
  `id_petugas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `role` enum('admin','petugas klinik','mahasiswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `role`) VALUES
(1, '2023573010099', '5f4dcc3b5aa765d61d8327deb882cf99', 'Administrator Klinik', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_medis_mahasiswa`
--
ALTER TABLE `data_medis_mahasiswa`
  ADD PRIMARY KEY (`id_data_medis`),
  ADD KEY `nim` (`nim`);

--
-- Indexes for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id_konsultasi`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nim`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `petugas_klinik`
--
ALTER TABLE `petugas_klinik`
  ADD PRIMARY KEY (`id_petugas`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tindak_lanjut`
--
ALTER TABLE `tindak_lanjut`
  ADD PRIMARY KEY (`id_tindak_lanjut`),
  ADD KEY `id_konsultasi` (`id_konsultasi`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_medis_mahasiswa`
--
ALTER TABLE `data_medis_mahasiswa`
  MODIFY `id_data_medis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id_konsultasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `petugas_klinik`
--
ALTER TABLE `petugas_klinik`
  MODIFY `id_petugas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tindak_lanjut`
--
ALTER TABLE `tindak_lanjut`
  MODIFY `id_tindak_lanjut` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_medis_mahasiswa`
--
ALTER TABLE `data_medis_mahasiswa`
  ADD CONSTRAINT `data_medis_mahasiswa_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD CONSTRAINT `konsultasi_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `konsultasi_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `petugas_klinik` (`id_petugas`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `petugas_klinik`
--
ALTER TABLE `petugas_klinik`
  ADD CONSTRAINT `petugas_klinik_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tindak_lanjut`
--
ALTER TABLE `tindak_lanjut`
  ADD CONSTRAINT `tindak_lanjut_ibfk_1` FOREIGN KEY (`id_konsultasi`) REFERENCES `konsultasi` (`id_konsultasi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tindak_lanjut_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tindak_lanjut_ibfk_3` FOREIGN KEY (`id_petugas`) REFERENCES `petugas_klinik` (`id_petugas`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
