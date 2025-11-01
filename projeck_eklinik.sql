-- =========================================================
-- 💉 Database: projeck_eklinik (Versi Super Stabil - Final)
-- =========================================================
CREATE DATABASE IF NOT EXISTS projeck_eklinik;
USE projeck_eklinik;

-- =======================
-- Tabel: user
-- =======================
CREATE TABLE `user` (
  id_user BIGINT NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(100) NOT NULL,
  role ENUM('admin','petugas klinik','mahasiswa') NOT NULL,
  PRIMARY KEY (id_user),
  UNIQUE KEY (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =======================
-- Tabel: mahasiswa
-- =======================
CREATE TABLE mahasiswa (
  nim BIGINT NOT NULL,
  nama VARCHAR(100),
  jurusan VARCHAR(100),
  prodi VARCHAR(100),
  tgl_lahir DATE,
  jenis_kelamin ENUM('Laki-laki','Perempuan'),
  alamat TEXT,
  no_hp VARCHAR(20),
  id_user BIGINT,
  PRIMARY KEY (nim),
  INDEX (id_user),
  CONSTRAINT fk_mahasiswa_user FOREIGN KEY (id_user)
    REFERENCES `user`(id_user)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =======================
-- Tabel: petugas_klinik
-- =======================
CREATE TABLE petugas_klinik (
  id_petugas BIGINT NOT NULL,
  nama VARCHAR(100),
  jenis_kelamin ENUM('Laki-laki','Perempuan'),
  spesialis VARCHAR(100),
  no_hp VARCHAR(20),
  alamat TEXT,
  id_user BIGINT,
  PRIMARY KEY (id_petugas),
  INDEX (id_user),
  CONSTRAINT fk_petugas_user FOREIGN KEY (id_user)
    REFERENCES `user`(id_user)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =======================
-- Tabel: data_medis_mahasiswa
-- =======================
CREATE TABLE data_medis_mahasiswa (
  id_data_medis BIGINT NOT NULL AUTO_INCREMENT,
  nim BIGINT,
  alergi TEXT,
  gol_dar VARCHAR(5),
  riwayat_penyakit TEXT,
  tinggi_badan FLOAT,
  berat_badan FLOAT,
  alat_bantu VARCHAR(100),
  kontak_darurat VARCHAR(100),
  PRIMARY KEY (id_data_medis),
  INDEX (nim),
  CONSTRAINT fk_data_medis_mahasiswa FOREIGN KEY (nim)
    REFERENCES mahasiswa(nim)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =======================
-- Tabel: konsultasi
-- =======================
CREATE TABLE konsultasi (
  id_konsultasi BIGINT NOT NULL AUTO_INCREMENT,
  tgl_konsultasi DATETIME DEFAULT CURRENT_TIMESTAMP,
  keluhan_utama TEXT,
  lama_keluhan TEXT,
  riwayat_pengobatan_sendiri TEXT,
  catatan_mahasiswa TEXT,
  nim BIGINT,
  id_petugas BIGINT,
  PRIMARY KEY (id_konsultasi),
  INDEX (nim),
  INDEX (id_petugas),
  CONSTRAINT fk_konsultasi_mahasiswa FOREIGN KEY (nim)
    REFERENCES mahasiswa(nim)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_konsultasi_petugas FOREIGN KEY (id_petugas)
    REFERENCES petugas_klinik(id_petugas)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =======================
-- Tabel: tindak_lanjut
-- =======================
CREATE TABLE tindak_lanjut (
  id_tindak_lanjut BIGINT NOT NULL AUTO_INCREMENT,
  tgl_tindak_lanjut DATETIME DEFAULT CURRENT_TIMESTAMP,
  diagnosa TEXT,
  resep_obat TEXT,
  saran_perawatan TEXT,
  id_konsultasi BIGINT,
  nim BIGINT,
  id_petugas BIGINT,
  PRIMARY KEY (id_tindak_lanjut),
  INDEX (id_konsultasi),
  INDEX (nim),
  INDEX (id_petugas),
  CONSTRAINT fk_tindak_lanjut_konsultasi FOREIGN KEY (id_konsultasi)
    REFERENCES konsultasi(id_konsultasi)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_tindak_lanjut_mahasiswa FOREIGN KEY (nim)
    REFERENCES mahasiswa(nim)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_tindak_lanjut_petugas FOREIGN KEY (id_petugas)
    REFERENCES petugas_klinik(id_petugas)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =======================
-- Trigger: Tambah otomatis mahasiswa/petugas setelah user dibuat
-- =======================
DELIMITER $$

CREATE TRIGGER after_user_insert
AFTER INSERT ON `user`
FOR EACH ROW
BEGIN
    -- Jika role = mahasiswa, otomatis buat data di tabel mahasiswa
    IF NEW.role = 'mahasiswa' THEN
        INSERT INTO mahasiswa (nim, id_user)
        VALUES (CAST(NEW.username AS UNSIGNED), NEW.id_user);
    END IF;

    -- Jika role = petugas klinik, otomatis buat data di tabel petugas_klinik
    IF NEW.role = 'petugas klinik' THEN
        INSERT INTO petugas_klinik (id_petugas, id_user)
        VALUES (NEW.username, NEW.id_user);
    END IF;
END$$

DELIMITER ;

-- =======================
-- Data Awal User
-- =======================
INSERT INTO `user` (username, password, role) VALUES
('2023573010099', '5f4dcc3b5aa765d61d8327deb882cf99', 'admin'),
('2023573010011', '5f4dcc3b5aa765d61d8327deb882cf99', 'petugas klinik'),
('2023573010012', '5f4dcc3b5aa765d61d8327deb882cf99', 'mahasiswa');

-- =========================================================
-- ✅ Semua relasi, tipe data, dan trigger sudah stabil & optimal
-- =========================================================
