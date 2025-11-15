-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 01:58 PM
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
  `id_data_medis` bigint(20) NOT NULL,
  `nim` varchar(50) DEFAULT NULL,
  `alergi` text DEFAULT NULL,
  `gol_dar` varchar(5) DEFAULT NULL,
  `riwayat_penyakit` text DEFAULT NULL,
  `tinggi_badan` float DEFAULT NULL,
  `berat_badan` float DEFAULT NULL,
  `alat_bantu` varchar(100) DEFAULT NULL,
  `kontak_darurat` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_medis_mahasiswa`
--

INSERT INTO `data_medis_mahasiswa` (`id_data_medis`, `nim`, `alergi`, `gol_dar`, `riwayat_penyakit`, `tinggi_badan`, `berat_badan`, `alat_bantu`, `kontak_darurat`) VALUES
(0, '2023573010011', 'udang', 'A', 'Lambung', 170, 50, 'kacamata', '085277147850');

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id_konsultasi` bigint(20) NOT NULL,
  `tgl_konsultasi` datetime DEFAULT current_timestamp(),
  `keluhan_utama` text DEFAULT NULL,
  `lama_keluhan` text DEFAULT NULL,
  `riwayat_pengobatan_sendiri` text DEFAULT NULL,
  `catatan_mahasiswa` text DEFAULT NULL,
  `nim` varchar(50) DEFAULT NULL,
  `id_petugas` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nim` varchar(50) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `prodi` varchar(100) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`nim`, `nama`, `jurusan`, `prodi`, `tgl_lahir`, `jenis_kelamin`, `alamat`, `no_hp`) VALUES
('2023573010011', 'Udin', 'tik', 'ti', '2003-01-28', 'Laki-laki', 'punteunt', '085562626262');

-- --------------------------------------------------------

--
-- Table structure for table `petugas_klinik`
--

CREATE TABLE `petugas_klinik` (
  `id_petugas` varchar(50) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `spesialis` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petugas_klinik`
--

INSERT INTO `petugas_klinik` (`id_petugas`, `nama`, `jenis_kelamin`, `spesialis`, `no_hp`, `alamat`) VALUES
('2023573010023', 'Amira', 'Perempuan', 'perawat', '085562626262', 'lhokseumawe');

-- --------------------------------------------------------

--
-- Table structure for table `tindak_lanjut`
--

CREATE TABLE `tindak_lanjut` (
  `id_tindak_lanjut` bigint(20) NOT NULL,
  `tgl_tindak_lanjut` datetime DEFAULT current_timestamp(),
  `diagnosa` text DEFAULT NULL,
  `resep_obat` text DEFAULT NULL,
  `saran_perawatan` text DEFAULT NULL,
  `id_konsultasi` bigint(20) DEFAULT NULL,
  `nim` varchar(50) DEFAULT NULL,
  `id_petugas` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','petugas klinik','mahasiswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `role`) VALUES
('2023573010011', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010023', '5f4dcc3b5aa765d61d8327deb882cf99', 'petugas klinik'),
('2023573010099', '6843b4ceb6136f35bc53db8bc20b6620', 'admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
