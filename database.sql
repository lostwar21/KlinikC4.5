-- Database: db_klinik_c45
-- Berdasarkan MASTER BLUEPRINT

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- CREATE DATABASE IF NOT EXISTS
CREATE DATABASE IF NOT EXISTS `db_klinik_c45`;
USE `db_klinik_c45`;

-- 1. Tabel pengguna
CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `level` enum('admin','petugas','pemilik') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pengguna`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabel pasien
CREATE TABLE `pasien` (
  `id_pasien` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_rm` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `usia` int(11) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat` text DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pasien`),
  UNIQUE KEY `nomor_rm` (`nomor_rm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabel rekam_medis
CREATE TABLE `rekam_medis` (
  `id_rm` int(11) NOT NULL AUTO_INCREMENT,
  `id_pasien` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `keluhan_utama` text DEFAULT NULL,
  `riwayat_penyakit` text DEFAULT NULL,
  `hasil_pemeriksaan` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `tindakan` varchar(100) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_rm`),
  KEY `id_pasien` (`id_pasien`),
  KEY `id_pengguna` (`id_pengguna`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabel dataset
CREATE TABLE `dataset` (
  `id_dataset` int(11) NOT NULL AUTO_INCREMENT,
  `nama_dataset` varchar(100) NOT NULL,
  `sumber` varchar(255) DEFAULT NULL,
  `jumlah_record` int(11) DEFAULT 0,
  `jumlah_atribut` int(11) DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dataset`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabel atribut
CREATE TABLE `atribut` (
  `id_atribut` int(11) NOT NULL AUTO_INCREMENT,
  `id_dataset` int(11) NOT NULL,
  `nama_atribut` varchar(100) NOT NULL,
  `tipe_data` enum('numerik','kategorikal') NOT NULL,
  `nilai_mungkin` text DEFAULT NULL,
  `is_target` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_atribut`),
  KEY `id_dataset` (`id_dataset`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Tabel data_latih
CREATE TABLE `data_latih` (
  `id_data_latih` int(11) NOT NULL AUTO_INCREMENT,
  `id_dataset` int(11) NOT NULL,
  `id_pasien` int(11) DEFAULT NULL,
  `nilai_atribut_json` json NOT NULL,
  `kelas_target` varchar(50) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_data_latih`),
  KEY `id_dataset` (`id_dataset`),
  KEY `id_pasien` (`id_pasien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Tabel data_uji
CREATE TABLE `data_uji` (
  `id_data_uji` int(11) NOT NULL AUTO_INCREMENT,
  `id_dataset` int(11) NOT NULL,
  `id_pasien` int(11) DEFAULT NULL,
  `nilai_atribut_json` json NOT NULL,
  `kelas_aktual` varchar(50) DEFAULT NULL,
  `kelas_prediksi` varchar(50) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_data_uji`),
  KEY `id_dataset` (`id_dataset`),
  KEY `id_pasien` (`id_pasien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Tabel model_klasifikasi
CREATE TABLE `model_klasifikasi` (
  `id_model` int(11) NOT NULL AUTO_INCREMENT,
  `id_dataset` int(11) NOT NULL,
  `nama_model` varchar(100) NOT NULL,
  `algoritma` varchar(50) DEFAULT 'C4.5',
  `parameter` text DEFAULT NULL,
  `akurasi` float DEFAULT NULL,
  `presisi` json DEFAULT NULL,
  `recall` json DEFAULT NULL,
  `f1_score` json DEFAULT NULL,
  `confusion_matrix_json` json DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_model`),
  KEY `id_dataset` (`id_dataset`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Tabel pohon_keputusan
CREATE TABLE `pohon_keputusan` (
  `id_pohon` int(11) NOT NULL AUTO_INCREMENT,
  `id_model` int(11) NOT NULL,
  `struktur_pohon` longblob DEFAULT NULL,
  `total_node` int(11) DEFAULT 0,
  `kedalaman_maks` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pohon`),
  KEY `id_model` (`id_model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Tabel node_pohon
CREATE TABLE `node_pohon` (
  `id_node` int(11) NOT NULL AUTO_INCREMENT,
  `id_pohon` int(11) NOT NULL,
  `id_parent` int(11) DEFAULT NULL,
  `tipe_node` enum('akar','internal','daun') NOT NULL,
  `atribut` varchar(100) DEFAULT NULL,
  `nilai_penghubung` varchar(255) DEFAULT NULL,
  `label_kelas` varchar(50) DEFAULT NULL,
  `entropy` float DEFAULT NULL,
  `gain` float DEFAULT NULL,
  `jumlah_kasus` int(11) DEFAULT NULL,
  `distribusi_kelas` json DEFAULT NULL,
  PRIMARY KEY (`id_node`),
  KEY `id_pohon` (`id_pohon`),
  KEY `id_parent` (`id_parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Tabel aturan_klasifikasi
CREATE TABLE `aturan_klasifikasi` (
  `id_aturan` int(11) NOT NULL AUTO_INCREMENT,
  `id_model` int(11) NOT NULL,
  `aturan_teks` text DEFAULT NULL,
  `label_tindakan` varchar(50) DEFAULT NULL,
  `confidence` float DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_aturan`),
  KEY `id_model` (`id_model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 12. Tabel hasil_klasifikasi
CREATE TABLE `hasil_klasifikasi` (
  `id_hasil` int(11) NOT NULL AUTO_INCREMENT,
  `id_pasien` int(11) NOT NULL,
  `id_model` int(11) NOT NULL,
  `usia_pasien` varchar(50) DEFAULT NULL,
  `jenis_kelamin` varchar(10) DEFAULT NULL,
  `keluhan_pasien` varchar(100) DEFAULT NULL,
  `riwayat_pasien` varchar(100) DEFAULT NULL,
  `hasil_prediksi` varchar(50) DEFAULT NULL,
  `tindakan_aktual` varchar(50) DEFAULT NULL,
  `validasi` enum('sesuai','tidak sesuai') DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_hasil`),
  KEY `id_pasien` (`id_pasien`),
  KEY `id_model` (`id_model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 13. Tabel log_aktivitas
CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengguna` int(11) DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `endpoint` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `id_pengguna` (`id_pengguna`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- DATA DUMMY AWAL
INSERT INTO `pengguna` (`username`, `password`, `nama_lengkap`, `level`, `status`) VALUES
('admin', '$2y$10$8LgKx8W/p8T8e6m6e6e6e6u.v/Y6v6v6v6v6v6v6v6v6v6v6v6v6v', 'Administrator Sistem', 'admin', 'aktif'),
('petugas', '$2y$10$8LgKx8W/p8T8e6m6e6e6e6u.v/Y6v6v6v6v6v6v6v6v6v6v6v6v6v', 'Petugas Rekam Medis', 'petugas', 'aktif');

INSERT INTO `dataset` (`nama_dataset`, `sumber`, `jumlah_record`, `jumlah_atribut`, `deskripsi`) VALUES
('Dataset Klasifikasi Gigi v1', 'Praktik Gigi Mandiri', 100, 4, 'Data pasien gigi 2024-2025 untuk algoritma C4.5');

INSERT INTO `atribut` (`id_dataset`, `nama_atribut`, `tipe_data`, `nilai_mungkin`, `is_target`) VALUES
(1, 'Usia', 'kategorikal', 'Anak,Remaja,Dewasa,Lansia', 0),
(1, 'Jenis_Kelamin', 'kategorikal', 'L,P', 0),
(1, 'Keluhan_Utama', 'kategorikal', 'K1,K2,K3,K4,K5', 0),
(1, 'Riwayat_Penyakit', 'kategorikal', 'Ada,Tidak Ada', 0),
(1, 'Tindakan_Perawatan', 'kategorikal', 'T1,T2,T3,T4,T5', 1);

-- DATA DUMMY PASIEN (100) & DATA LATIH (100)
-- Disederhanakan untuk efisiensi penulisan namun tetap logis medis.
-- Keluhan: K1=Karies, K2=Nyeri, K3=Gusi, K4=Karang, K5=Ngilu
-- Tindakan: T1=Penambalan, T2=Pencabutan, T3=Scaling, T4=Medikamentosa, T5=Rujukan

INSERT INTO `pasien` (`id_pasien`, `nomor_rm`, `nama`, `usia`, `jenis_kelamin`, `alamat`) VALUES
(1, 'RM-001', 'Budi Santoso', 10, 'L', 'Alamat 1'),
(2, 'RM-002', 'Siti Aminah', 15, 'P', 'Alamat 2'),
(3, 'RM-003', 'Andi Wijaya', 35, 'L', 'Alamat 3'),
(4, 'RM-004', 'Lani Marlina', 65, 'P', 'Alamat 4'),
(5, 'RM-005', 'Dedi Kurniawan', 12, 'L', 'Alamat 5'),
(6, 'RM-006', 'Rina Fitriani', 40, 'P', 'Alamat 6'),
(7, 'RM-007', 'Eko Prasetyo', 20, 'L', 'Alamat 7'),
(8, 'RM-008', 'Maya Sari', 55, 'P', 'Alamat 8'),
(9, 'RM-009', 'Hendra Kusuma', 70, 'L', 'Alamat 9'),
(10, 'RM-010', 'Dewi Lestari', 25, 'P', 'Alamat 10'),
(11, 'RM-011', 'Pasien 11', 8, 'L', 'Alamat 11'),
(12, 'RM-012', 'Pasien 12', 14, 'P', 'Alamat 12'),
(13, 'RM-013', 'Pasien 13', 32, 'L', 'Alamat 13'),
(14, 'RM-014', 'Pasien 14', 62, 'P', 'Alamat 14'),
(15, 'RM-015', 'Pasien 15', 11, 'L', 'Alamat 15'),
(16, 'RM-016', 'Pasien 16', 42, 'P', 'Alamat 16'),
(17, 'RM-017', 'Pasien 17', 19, 'L', 'Alamat 17'),
(18, 'RM-018', 'Pasien 18', 50, 'P', 'Alamat 18'),
(19, 'RM-019', 'Pasien 19', 75, 'L', 'Alamat 19'),
(20, 'RM-020', 'Pasien 20', 28, 'P', 'Alamat 20'),
(21, 'RM-021', 'Pasien 21', 9, 'L', 'Alamat 21'),
(22, 'RM-022', 'Pasien 22', 16, 'P', 'Alamat 22'),
(23, 'RM-023', 'Pasien 23', 38, 'L', 'Alamat 23'),
(24, 'RM-024', 'Pasien 24', 68, 'P', 'Alamat 24'),
(25, 'RM-025', 'Pasien 25', 13, 'L', 'Alamat 25'),
(26, 'RM-026', 'Pasien 26', 44, 'P', 'Alamat 26'),
(27, 'RM-027', 'Pasien 27', 22, 'L', 'Alamat 27'),
(28, 'RM-028', 'Pasien 28', 58, 'P', 'Alamat 28'),
(29, 'RM-029', 'Pasien 29', 72, 'L', 'Alamat 29'),
(30, 'RM-030', 'Pasien 30', 26, 'P', 'Alamat 30'),
(31, 'RM-031', 'Pasien 31', 7, 'L', 'Alamat 31'),
(32, 'RM-032', 'Pasien 32', 17, 'P', 'Alamat 32'),
(33, 'RM-033', 'Pasien 33', 31, 'L', 'Alamat 33'),
(34, 'RM-034', 'Pasien 34', 61, 'P', 'Alamat 34'),
(35, 'RM-035', 'Pasien 35', 12, 'L', 'Alamat 35'),
(36, 'RM-036', 'Pasien 36', 41, 'P', 'Alamat 36'),
(37, 'RM-037', 'Pasien 37', 21, 'L', 'Alamat 37'),
(38, 'RM-038', 'Pasien 38', 52, 'P', 'Alamat 38'),
(39, 'RM-039', 'Pasien 39', 74, 'L', 'Alamat 39'),
(40, 'RM-040', 'Pasien 40', 29, 'P', 'Alamat 40'),
(41, 'RM-041', 'Pasien 41', 10, 'L', 'Alamat 41'),
(42, 'RM-042', 'Pasien 42', 18, 'P', 'Alamat 42'),
(43, 'RM-043', 'Pasien 43', 33, 'L', 'Alamat 43'),
(44, 'RM-044', 'Pasien 44', 63, 'P', 'Alamat 44'),
(45, 'RM-045', 'Pasien 45', 12, 'L', 'Alamat 45'),
(46, 'RM-046', 'Pasien 46', 43, 'P', 'Alamat 46'),
(47, 'RM-047', 'Pasien 47', 23, 'L', 'Alamat 47'),
(48, 'RM-048', 'Pasien 48', 53, 'P', 'Alamat 48'),
(49, 'RM-049', 'Pasien 49', 73, 'L', 'Alamat 49'),
(50, 'RM-050', 'Pasien 50', 27, 'P', 'Alamat 50'),
(51, 'RM-051', 'Pasien 51', 11, 'L', 'Alamat 51'),
(52, 'RM-052', 'Pasien 52', 19, 'P', 'Alamat 52'),
(53, 'RM-053', 'Pasien 53', 34, 'L', 'Alamat 53'),
(54, 'RM-054', 'Pasien 54', 64, 'P', 'Alamat 54'),
(55, 'RM-055', 'Pasien 55', 13, 'L', 'Alamat 55'),
(56, 'RM-056', 'Pasien 56', 44, 'P', 'Alamat 56'),
(57, 'RM-057', 'Pasien 57', 24, 'L', 'Alamat 57'),
(58, 'RM-058', 'Pasien 58', 54, 'P', 'Alamat 58'),
(59, 'RM-059', 'Pasien 59', 74, 'L', 'Alamat 59'),
(60, 'RM-060', 'Pasien 60', 28, 'P', 'Alamat 60'),
(61, 'RM-061', 'Pasien 61', 10, 'L', 'Alamat 61'),
(62, 'RM-062', 'Pasien 62', 16, 'P', 'Alamat 62'),
(63, 'RM-063', 'Pasien 63', 35, 'L', 'Alamat 63'),
(64, 'RM-064', 'Pasien 64', 65, 'P', 'Alamat 64'),
(65, 'RM-065', 'Pasien 65', 12, 'L', 'Alamat 65'),
(66, 'RM-066', 'Pasien 66', 45, 'P', 'Alamat 66'),
(67, 'RM-067', 'Pasien 67', 22, 'L', 'Alamat 67'),
(68, 'RM-068', 'Pasien 68', 55, 'P', 'Alamat 68'),
(69, 'RM-069', 'Pasien 69', 71, 'L', 'Alamat 69'),
(70, 'RM-070', 'Pasien 70', 26, 'P', 'Alamat 70'),
(71, 'RM-071', 'Pasien 71', 8, 'L', 'Alamat 71'),
(72, 'RM-072', 'Pasien 72', 14, 'P', 'Alamat 72'),
(73, 'RM-073', 'Pasien 73', 33, 'L', 'Alamat 73'),
(74, 'RM-074', 'Pasien 74', 66, 'P', 'Alamat 74'),
(75, 'RM-075', 'Pasien 75', 12, 'L', 'Alamat 75'),
(76, 'RM-076', 'Pasien 76', 42, 'P', 'Alamat 76'),
(77, 'RM-077', 'Pasien 77', 20, 'L', 'Alamat 77'),
(78, 'RM-078', 'Pasien 78', 52, 'P', 'Alamat 78'),
(79, 'RM-079', 'Pasien 79', 76, 'L', 'Alamat 79'),
(80, 'RM-080', 'Pasien 80', 29, 'P', 'Alamat 80'),
(81, 'RM-081', 'Pasien 81', 9, 'L', 'Alamat 81'),
(82, 'RM-082', 'Pasien 82', 13, 'P', 'Alamat 82'),
(83, 'RM-083', 'Pasien 83', 39, 'L', 'Alamat 83'),
(84, 'RM-084', 'Pasien 84', 68, 'P', 'Alamat 84'),
(85, 'RM-085', 'Pasien 85', 11, 'L', 'Alamat 85'),
(86, 'RM-086', 'Pasien 86', 44, 'P', 'Alamat 86'),
(87, 'RM-087', 'Pasien 87', 24, 'L', 'Alamat 87'),
(88, 'RM-088', 'Pasien 88', 53, 'P', 'Alamat 88'),
(89, 'RM-089', 'Pasien 89', 78, 'L', 'Alamat 89'),
(90, 'RM-090', 'Pasien 90', 25, 'P', 'Alamat 90'),
(91, 'RM-091', 'Pasien 91', 10, 'L', 'Alamat 91'),
(92, 'RM-092', 'Pasien 92', 15, 'P', 'Alamat 92'),
(93, 'RM-093', 'Pasien 93', 37, 'L', 'Alamat 93'),
(94, 'RM-094', 'Pasien 94', 65, 'P', 'Alamat 94'),
(95, 'RM-095', 'Pasien 95', 12, 'L', 'Alamat 95'),
(96, 'RM-096', 'Pasien 96', 40, 'P', 'Alamat 96'),
(97, 'RM-097', 'Pasien 97', 21, 'L', 'Alamat 97'),
(98, 'RM-098', 'Pasien 98', 56, 'P', 'Alamat 98'),
(99, 'RM-099', 'Pasien 99', 74, 'L', 'Alamat 99'),
(100, 'RM-100', 'Pasien 100', 26, 'P', 'Alamat 100');

-- DATA LATIH (100 BARIS - LOGIS)
-- Pola: (K1, Anak/Remaja) -> T1, (K1, Dewasa/Lansia) -> T2, K2 -> T4, K3/K4 -> T3, K5 -> T4/T5
INSERT INTO `data_latih` (`id_dataset`, `id_pasien`, `nilai_atribut_json`, `kelas_target`) VALUES
(1, 1, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T1'),
(1, 2, '{"Usia": "Remaja", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T1'),
(1, 3, '{"Usia": "Dewasa", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T2'),
(1, 4, '{"Usia": "Lansia", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T2'),
(1, 5, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 6, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Ada"}', 'T4'),
(1, 7, '{"Usia": "Remaja", "Jenis_Kelamin": "L", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 8, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 9, '{"Usia": "Lansia", "Jenis_Kelamin": "L", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 10, '{"Usia": "Remaja", "Jenis_Kelamin": "P", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 11, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 12, '{"Usia": "Remaja", "Jenis_Kelamin": "P", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Ada"}', 'T5'),
(1, 13, '{"Usia": "Dewasa", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T1'),
(1, 14, '{"Usia": "Lansia", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T2'),
(1, 15, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T1'),
(1, 16, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 17, '{"Usia": "Remaja", "Jenis_Kelamin": "L", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Ada"}', 'T4'),
(1, 18, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 19, '{"Usia": "Lansia", "Jenis_Kelamin": "L", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 20, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 21, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 22, '{"Usia": "Remaja", "Jenis_Kelamin": "P", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 23, '{"Usia": "Dewasa", "Jenis_Kelamin": "L", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Ada"}', 'T5'),
(1, 24, '{"Usia": "Lansia", "Jenis_Kelamin": "P", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Ada"}', 'T5'),
(1, 25, '{"Usia": "Remaja", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T1'),
(1, 26, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T1'),
(1, 27, '{"Usia": "Remaja", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T2'),
(1, 28, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T2'),
(1, 29, '{"Usia": "Lansia", "Jenis_Kelamin": "L", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 30, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Ada"}', 'T4'),
(1, 31, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 32, '{"Usia": "Remaja", "Jenis_Kelamin": "P", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 33, '{"Usia": "Dewasa", "Jenis_Kelamin": "L", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 34, '{"Usia": "Lansia", "Jenis_Kelamin": "P", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 35, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Ada"}', 'T5'),
(1, 36, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 37, '{"Usia": "Remaja", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T1'),
(1, 38, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T2'),
(1, 39, '{"Usia": "Lansia", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T2'),
(1, 40, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 41, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Ada"}', 'T4'),
(1, 42, '{"Usia": "Remaja", "Jenis_Kelamin": "P", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 43, '{"Usia": "Dewasa", "Jenis_Kelamin": "L", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 44, '{"Usia": "Lansia", "Jenis_Kelamin": "P", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 45, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 46, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Ada"}', 'T5'),
(1, 47, '{"Usia": "Remaja", "Jenis_Kelamin": "L", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 48, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T1'),
(1, 49, '{"Usia": "Lansia", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T2'),
(1, 50, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T2'),
(1, 51, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 52, '{"Usia": "Remaja", "Jenis_Kelamin": "P", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Ada"}', 'T4'),
(1, 53, '{"Usia": "Dewasa", "Jenis_Kelamin": "L", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 54, '{"Usia": "Lansia", "Jenis_Kelamin": "P", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 55, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 56, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 57, '{"Usia": "Remaja", "Jenis_Kelamin": "L", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Ada"}', 'T5'),
(1, 58, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 59, '{"Usia": "Lansia", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T2'),
(1, 60, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T2'),
(1, 61, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T1'),
(1, 62, '{"Usia": "Remaja", "Jenis_Kelamin": "P", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 63, '{"Usia": "Dewasa", "Jenis_Kelamin": "L", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Ada"}', 'T4'),
(1, 64, '{"Usia": "Lansia", "Jenis_Kelamin": "P", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 65, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 66, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 67, '{"Usia": "Remaja", "Jenis_Kelamin": "L", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 68, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Ada"}', 'T5'),
(1, 69, '{"Usia": "Lansia", "Jenis_Kelamin": "L", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 70, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T1'),
(1, 71, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T1'),
(1, 72, '{"Usia": "Remaja", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T1'),
(1, 73, '{"Usia": "Dewasa", "Jenis_Kelamin": "L", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 74, '{"Usia": "Lansia", "Jenis_Kelamin": "P", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Ada"}', 'T4'),
(1, 75, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 76, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 77, '{"Usia": "Remaja", "Jenis_Kelamin": "L", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 78, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 79, '{"Usia": "Lansia", "Jenis_Kelamin": "L", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Ada"}', 'T5'),
(1, 80, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 81, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Tidak Ada"}', 'T1'),
(1, 82, '{"Usia": "Remaja", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T1'),
(1, 83, '{"Usia": "Dewasa", "Jenis_Kelamin": "L", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Ada"}', 'T4'),
(1, 84, '{"Usia": "Lansia", "Jenis_Kelamin": "P", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 85, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 86, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 87, '{"Usia": "Remaja", "Jenis_Kelamin": "L", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 88, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 89, '{"Usia": "Lansia", "Jenis_Kelamin": "L", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Ada"}', 'T5'),
(1, 90, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 91, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T1'),
(1, 92, '{"Usia": "Remaja", "Jenis_Kelamin": "P", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 93, '{"Usia": "Dewasa", "Jenis_Kelamin": "L", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 94, '{"Usia": "Lansia", "Jenis_Kelamin": "P", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Tidak Ada"}', 'T3'),
(1, 95, '{"Usia": "Anak", "Jenis_Kelamin": "L", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Ada"}', 'T5'),
(1, 96, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K1", "Riwayat_Penyakit": "Ada"}', 'T2'),
(1, 97, '{"Usia": "Remaja", "Jenis_Kelamin": "L", "Keluhan_Utama": "K2", "Riwayat_Penyakit": "Tidak Ada"}', 'T4'),
(1, 98, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K3", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 99, '{"Usia": "Lansia", "Jenis_Kelamin": "L", "Keluhan_Utama": "K4", "Riwayat_Penyakit": "Ada"}', 'T3'),
(1, 100, '{"Usia": "Dewasa", "Jenis_Kelamin": "P", "Keluhan_Utama": "K5", "Riwayat_Penyakit": "Tidak Ada"}', 'T4');

COMMIT;
