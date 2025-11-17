-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Nov 2025 pada 01.52
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

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
-- Struktur dari tabel `data_medis_mahasiswa`
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
-- Dumping data untuk tabel `data_medis_mahasiswa`
--

INSERT INTO `data_medis_mahasiswa` (`id_data_medis`, `nim`, `alergi`, `gol_dar`, `riwayat_penyakit`, `tinggi_badan`, `berat_badan`, `alat_bantu`, `kontak_darurat`) VALUES
(1, '2023573010012', 'tidak ada', 'B', 'tidak ada', 165, 55, 'tidak ada', '0812345610012'),
(2, '2023573010013', 'tidak ada', 'O', 'tidak ada', 172, 68, 'tidak ada', '0812345610013'),
(3, '2023573010014', 'tidak ada', 'AB', 'tidak ada', 160, 50, 'tidak ada', '0812345610014'),
(4, '2023573010015', 'tidak ada', 'A', 'tidak ada', 175, 70, 'tidak ada', '0812345610015'),
(5, '2023573010016', 'tidak ada', 'B', 'tidak ada', 168, 58, 'tidak ada', '0812345610016'),
(6, '2023573010017', 'tidak ada', 'O', 'tidak ada', 180, 80, 'tidak ada', '0812345610017'),
(7, '2023573010018', 'tidak ada', 'AB', 'tidak ada', 158, 48, 'tidak ada', '0812345610018'),
(9, '2023573010020', 'tidak ada', 'B', 'tidak ada', 170, 60, 'tidak ada', '0812345610020'),
(10, '2023573010021', 'tidak ada', 'O', 'tidak ada', 173, 66, 'tidak ada', '0812345610021'),
(11, '2023573010022', 'tidak ada', 'AB', 'tidak ada', 162, 52, 'tidak ada', '0812345610022'),
(12, '2023573010024', 'tidak ada', 'B', 'tidak ada', 177, 74, 'tidak ada', '0812345610024'),
(13, '2023573010025', 'tidak ada', 'O', 'tidak ada', 160, 51, 'tidak ada', '0812345610025'),
(14, '2023573010026', 'tidak ada', 'AB', 'tidak ada', 169, 63, 'tidak ada', '0812345610026'),
(15, '2023573010027', 'tidak ada', 'A', 'tidak ada', 174, 72, 'tidak ada', '0812345610027'),
(16, '2023573010028', 'tidak ada', 'B', 'tidak ada', 167, 56, 'tidak ada', '0812345610028'),
(17, '2023573010029', 'tidak ada', 'O', 'tidak ada', 171, 61, 'tidak ada', '0812345610029'),
(18, '2023573010030', 'tidak ada', 'AB', 'tidak ada', 163, 54, 'tidak ada', '0812345610030'),
(19, '2023573010031', 'tidak ada', 'A', 'tidak ada', 176, 75, 'tidak ada', '0812345610031'),
(20, '2023573010032', 'tidak ada', 'B', 'tidak ada', 164, 53, 'tidak ada', '0812345610032'),
(21, '2023573010033', 'tidak ada', 'O', 'tidak ada', 179, 78, 'tidak ada', '0812345610033'),
(22, '2023573010034', 'tidak ada', 'AB', 'tidak ada', 157, 47, 'tidak ada', '0812345610034'),
(23, '2023573010035', 'tidak ada', 'A', 'tidak ada', 172, 67, 'tidak ada', '0812345610035'),
(24, '2023573010011', 'udang', 'A', 'Tidak ada', 170, 50, 'Tidak ada', '085277147850'),
(25, '2023573010040', 'Tidak Ada', 'AB', 'Lambung', 162, 48, 'Tidak ada', '085277147850');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_petugas_klinik`
--

CREATE TABLE `jadwal_petugas_klinik` (
  `id_jadwal` int(11) NOT NULL,
  `id_petugas` varchar(50) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal_petugas_klinik`
--

INSERT INTO `jadwal_petugas_klinik` (`id_jadwal`, `id_petugas`, `hari`, `jam_mulai`, `jam_selesai`) VALUES
(12, '2023573010000', 'Senin', '08:00:00', '17:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konsultasi`
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
-- Struktur dari tabel `mahasiswa`
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
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`nim`, `nama`, `jurusan`, `prodi`, `tgl_lahir`, `jenis_kelamin`, `alamat`, `no_hp`) VALUES
('2023573010011', 'Udin', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2003-01-28', 'Laki-laki', 'punteunt', '085562626262'),
('2023573010012', 'Siti Aisyah', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2004-03-12', 'Perempuan', 'Cibinong', '081234567890'),
('20235730100123', 'amin', 'Teknik Sipil', 'D3 Teknologi Konstruksi Jalan dan Jembatan', '2025-11-04', 'Laki-laki', 'punteet', '085434321234'),
('2023573010013', 'Rizky Ramadhan', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2003-07-25', 'Laki-laki', 'Sukaraja', '087812345678'),
('2023573010014', 'Nadila Putri', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2004-11-05', 'Perempuan', 'Cimahpar', '089612345678'),
('2023573010015', 'Budi Santoso', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2003-09-14', 'Laki-laki', 'Bogor Tengah', '082134567891'),
('2023573010016', 'Alya Rahma', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2004-02-20', 'Perempuan', 'Dramaga', '083812345679'),
('2023573010017', 'Fajar Pratama', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2003-05-10', 'Laki-laki', 'Meuraxa', '085712345671'),
('2023573010018', 'Intan Marlina', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2004-08-03', 'Perempuan', 'Banda Sakti', '081298765432'),
('2023573010020', 'Dewi Lestari', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2004-06-18', 'Perempuan', 'Muara Satu', '082212345676'),
('2023573010021', 'Hendra Wijaya', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2003-02-27', 'Laki-laki', 'Keude Punteut', '085998877665'),
('2023573010022', 'Maya Sari', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2004-04-09', 'Perempuan', 'Cot Girek', '081377788899'),
('2023573010024', 'Rina Marlina', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2004-01-15', 'Perempuan', 'Kuta Alam', '089900112233'),
('2023573010025', 'Yusuf Haris', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2003-03-30', 'Laki-laki', 'Lueng Bata', '085533221100'),
('2023573010026', 'Nina Putri', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2004-07-07', 'Perempuan', 'Kuala Simpang', '082299887766'),
('2023573010027', 'Dedi Kurnia', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2003-11-11', 'Laki-laki', 'Pante Ceureumen', '085411223344'),
('2023573010028', 'Fitri Andriani', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2004-05-21', 'Perempuan', 'Samalanga', '081455667788'),
('2023573010029', 'Rian Maulana', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2003-08-29', 'Laki-laki', 'Lhokseumawe Tengah', '087799001122'),
('2023573010030', 'Sari Dewi', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2004-09-02', 'Perempuan', 'Paya Bujok', '082233445566'),
('2023573010031', 'Arief Hidayat', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2003-06-06', 'Laki-laki', 'Muara Dua', '085600112233'),
('2023573010032', 'Lina Kartika', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2004-12-12', 'Perempuan', 'Seuneubok', '081366554433'),
('2023573010033', 'Tommy Setiawan', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2003-04-04', 'Laki-laki', 'Pintu Air', '089911223344'),
('2023573010034', 'Vina Pratiwi', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2004-10-10', 'Perempuan', 'Penjeng', '082211334455'),
('2023573010035', 'Andi Prasetyo', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2003-05-05', 'Laki-laki', 'Lhoknga', '085622334455'),
('2023573010040', 'nadila arsyifa', 'Teknologi Informasi dan Komputer', 'D4 Teknik Informatika', '2001-02-28', 'Perempuan', 'bayu', '085562626262'),
('2023573010111', 'arsyi dandelion', 'Teknik Kimia', 'D4 Teknologi Pengolahan Minyak Dan Gas', '2025-11-20', '', 'jakarta', '085434321234');

-- --------------------------------------------------------

--
-- Struktur dari tabel `petugas_klinik`
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
-- Dumping data untuk tabel `petugas_klinik`
--

INSERT INTO `petugas_klinik` (`id_petugas`, `nama`, `jenis_kelamin`, `spesialis`, `no_hp`, `alamat`) VALUES
('10003', 'Aminah', 'Perempuan', 'Dokter Umum', '087654321234', 'Punteut'),
('2023573010000', 'Annisa', 'Perempuan', 'Dokter Umum', '085434321234', 'bayu');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tindak_lanjut`
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
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','petugas klinik','mahasiswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`username`, `password`, `role`) VALUES
('10003', '5f4dcc3b5aa765d61d8327deb882cf99', 'petugas klinik'),
('2023573010000', '5f4dcc3b5aa765d61d8327deb882cf99', 'petugas klinik'),
('2023573010011', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010012', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('20235730100123', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010013', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010014', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010015', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010016', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010017', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010018', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010020', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010021', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010022', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010024', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010025', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010026', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010027', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010028', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010029', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010030', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010031', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010032', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010033', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010034', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010035', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010040', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa'),
('2023573010099', '6843b4ceb6136f35bc53db8bc20b6620', 'admin'),
('2023573010111', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `data_medis_mahasiswa`
--
ALTER TABLE `data_medis_mahasiswa`
  ADD PRIMARY KEY (`id_data_medis`),
  ADD KEY `nim` (`nim`);

--
-- Indeks untuk tabel `jadwal_petugas_klinik`
--
ALTER TABLE `jadwal_petugas_klinik`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indeks untuk tabel `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id_konsultasi`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nim`);

--
-- Indeks untuk tabel `petugas_klinik`
--
ALTER TABLE `petugas_klinik`
  ADD PRIMARY KEY (`id_petugas`);

--
-- Indeks untuk tabel `tindak_lanjut`
--
ALTER TABLE `tindak_lanjut`
  ADD PRIMARY KEY (`id_tindak_lanjut`),
  ADD KEY `id_konsultasi` (`id_konsultasi`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `data_medis_mahasiswa`
--
ALTER TABLE `data_medis_mahasiswa`
  MODIFY `id_data_medis` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `jadwal_petugas_klinik`
--
ALTER TABLE `jadwal_petugas_klinik`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id_konsultasi` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tindak_lanjut`
--
ALTER TABLE `tindak_lanjut`
  MODIFY `id_tindak_lanjut` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `data_medis_mahasiswa`
--
ALTER TABLE `data_medis_mahasiswa`
  ADD CONSTRAINT `fk_data_medis_mahasiswa` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_petugas_klinik`
--
ALTER TABLE `jadwal_petugas_klinik`
  ADD CONSTRAINT `jadwal_petugas_klinik_ibfk_1` FOREIGN KEY (`id_petugas`) REFERENCES `petugas_klinik` (`id_petugas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD CONSTRAINT `fk_konsultasi_mahasiswa` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_konsultasi_petugas` FOREIGN KEY (`id_petugas`) REFERENCES `petugas_klinik` (`id_petugas`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `fk_mahasiswa_user` FOREIGN KEY (`nim`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `petugas_klinik`
--
ALTER TABLE `petugas_klinik`
  ADD CONSTRAINT `fk_petugas_user` FOREIGN KEY (`id_petugas`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tindak_lanjut`
--
ALTER TABLE `tindak_lanjut`
  ADD CONSTRAINT `fk_tindak_lanjut_konsultasi` FOREIGN KEY (`id_konsultasi`) REFERENCES `konsultasi` (`id_konsultasi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tindak_lanjut_mahasiswa` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tindak_lanjut_petugas` FOREIGN KEY (`id_petugas`) REFERENCES `petugas_klinik` (`id_petugas`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
