-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 14, 2026 at 10:09 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laporanpkl`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `peserta_pkl_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hadir',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `alasan` text COLLATE utf8mb4_unicode_ci,
  `bukti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `user_id`, `peserta_pkl_id`, `tanggal`, `jam_masuk`, `jam_pulang`, `status`, `keterangan`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`, `alasan`, `bukti`) VALUES
(1, 4, 1, '2026-06-14', '16:54:55', '17:02:20', 'hadir', NULL, 'absensi/absensi_1781430894.png', '-7.191177', '110.008831', '2026-06-14 09:54:55', '2026-06-14 10:02:20', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_divisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id`, `uuid`, `nama_divisi`, `created_at`, `updated_at`) VALUES
(1, '269243bf-0d1b-492a-8d01-df75792e0b55', 'TIK', '2026-06-12 03:39:12', '2026-06-14 06:08:10'),
(2, '294bfad7-f649-4717-8db4-597968cd30d9', 'IKP', '2026-06-12 03:39:12', '2026-06-14 06:08:10'),
(3, 'a9ca6f60-9548-49fa-b1c9-dfab9778e797', 'Sekretariat', '2026-06-12 03:39:12', '2026-06-14 06:08:10'),
(4, '787f5ea2-6e0d-43ee-8626-88a987d7fa85', 'Statistika', '2026-06-12 03:39:12', '2026-06-14 06:08:10');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history_divisi`
--

CREATE TABLE `history_divisi` (
  `id` bigint UNSIGNED NOT NULL,
  `peserta_pkl_id` bigint UNSIGNED NOT NULL,
  `divisi_id_lama` bigint UNSIGNED DEFAULT NULL,
  `divisi_id_baru` bigint UNSIGNED DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `tanggal_perubahan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `history_divisi`
--

INSERT INTO `history_divisi` (`id`, `peserta_pkl_id`, `divisi_id_lama`, `divisi_id_baru`, `keterangan`, `tanggal_perubahan`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 'Perubahan divisi oleh admin', '2026-06-14 06:25:05', '2026-06-14 06:25:05', '2026-06-14 06:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_dokumentasi`
--

CREATE TABLE `laporan_dokumentasi` (
  `id` bigint UNSIGNED NOT NULL,
  `laporan_harian_id` bigint UNSIGNED NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_harians`
--

CREATE TABLE `laporan_harians` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `peserta_pkl_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `hasil` text COLLATE utf8mb4_unicode_ci,
  `kendala` text COLLATE utf8mb4_unicode_ci,
  `status` enum('menunggu','disetujui','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan_harians`
--

INSERT INTO `laporan_harians` (`id`, `uuid`, `user_id`, `peserta_pkl_id`, `tanggal`, `kegiatan`, `hasil`, `kendala`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ce194e7a-1613-4256-a325-ad25a366dc78', 4, 1, '2026-06-14', 'test', 'test', 'test', 'disetujui', '2026-06-14 05:40:01', '2026-06-14 09:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `log_verifikasi`
--

CREATE TABLE `log_verifikasi` (
  `id` bigint UNSIGNED NOT NULL,
  `laporan_harian_id` bigint UNSIGNED NOT NULL,
  `pembimbing_id` bigint UNSIGNED NOT NULL,
  `status` enum('menunggu','disetujui','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_pembimbing` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_verifikasi`
--

INSERT INTO `log_verifikasi` (`id`, `laporan_harian_id`, `pembimbing_id`, `status`, `catatan_pembimbing`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'disetujui', NULL, '2026-06-14 09:52:23', '2026-06-14 09:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_02_074350_add_role_to_users_table', 1),
(5, '2026_02_02_074400_create_divisis_table', 1),
(6, '2026_02_02_075825_create_pembimbings_table', 1),
(7, '2026_02_04_014000_create_peserta_pkls_table', 1),
(8, '2026_02_04_014833_create_laporan_harians_table', 1),
(9, '2026_02_04_022733_create_log_verifikasis_table', 1),
(10, '2026_02_04_023450_create_penilaians_table', 1),
(11, '2026_02_04_024957_create_absensis_table', 1),
(12, '2026_02_04_030533_create_tugas_table', 1),
(13, '2026_02_05_021540_create_tugas_files_table', 1),
(14, '2026_03_06_005707_add_user_id_to_laporan_harians_tables', 1),
(15, '2026_03_06_010018_add_user_id_to_absensi_table', 1),
(16, '2026_03_11_014831_create_tugas_pengumpulans_table', 1),
(17, '2026_03_30_102852_create_laporan_dokumentasi_table', 1),
(18, '2026_04_06_090427_create_peserta_tugas_table', 1),
(19, '2026_04_08_132403_add_gps_to_absensi_table', 1),
(20, '2026_04_08_140224_add_bukti_to_absensi_table', 1),
(21, '2026_04_21_092102_add_file_to_tugas_table', 1),
(22, '2026_04_29_131846_add_is_active_to_users_table', 1),
(23, '2026_05_08_133102_add_selesai_status_to_peserta_pkls_table', 1),
(24, '2026_05_12_103430_remove_pembimbingsekolah_from_users_role_enum', 1),
(25, '2026_06_07_000000_create_history_divisi_table', 1),
(26, '2026_06_14_000001_add_uuid_to_peserta_pkls_table', 2),
(27, '2026_06_14_000002_add_uuid_to_laporan_harians_table', 3),
(28, '2026_06_14_000003_add_uuid_to_tugas_table', 4),
(29, '2026_06_14_000004_add_uuid_to_penilaians_table', 5),
(30, '2026_06_14_000004_add_uuid_to_pembimbings_table', 6),
(31, '2026_06_14_000005_add_uuid_to_users_table', 7),
(32, '2026_06_14_060500_add_uuid_to_users_table', 8),
(33, '2026_06_14_060600_add_uuid_to_divisi_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembimbings`
--

CREATE TABLE `pembimbings` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `divisi_id` bigint UNSIGNED DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembimbings`
--

INSERT INTO `pembimbings` (`id`, `uuid`, `user_id`, `divisi_id`, `nama`, `jabatan`, `created_at`, `updated_at`) VALUES
(1, 'ad824a5f-a4c1-43ac-9618-069815d0c6e6', 3, 1, 'Budi Santoso', 'Supervisor IT', '2026-06-12 03:39:11', '2026-06-14 06:24:44');

-- --------------------------------------------------------

--
-- Table structure for table `penilaians`
--

CREATE TABLE `penilaians` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `peserta_pkl_id` bigint UNSIGNED NOT NULL,
  `disiplin` int DEFAULT NULL,
  `tanggung_jawab` int DEFAULT NULL,
  `kerjasama` int DEFAULT NULL,
  `etika` int DEFAULT NULL,
  `inisiatif` int DEFAULT NULL,
  `nilai_akhir` double DEFAULT NULL,
  `predikat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penilaians`
--

INSERT INTO `penilaians` (`id`, `uuid`, `peserta_pkl_id`, `disiplin`, `tanggung_jawab`, `kerjasama`, `etika`, `inisiatif`, `nilai_akhir`, `predikat`, `catatan`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 90, 90, 90, 90, 90, 90, 'A', NULL, '2026-06-14 09:53:57', '2026-06-14 09:54:12');

-- --------------------------------------------------------

--
-- Table structure for table `peserta_pkls`
--

CREATE TABLE `peserta_pkls` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `pembimbing_id` bigint UNSIGNED DEFAULT NULL,
  `divisi_id` bigint UNSIGNED DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `jenis` enum('siswa','mahasiswa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `asal_institusi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jurusan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','aktif','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peserta_pkls`
--

INSERT INTO `peserta_pkls` (`id`, `uuid`, `user_id`, `pembimbing_id`, `divisi_id`, `tanggal_mulai`, `tanggal_selesai`, `jenis`, `asal_institusi`, `jurusan`, `no_hp`, `status`, `created_at`, `updated_at`) VALUES
(1, '253cf277-f06d-4f7b-ab03-9754746c2684', 4, 1, 1, '2026-06-01', '2026-12-31', 'siswa', 'SMK Negeri 1', 'RPL', NULL, 'pending', '2026-06-12 03:39:12', '2026-06-14 06:26:12');

-- --------------------------------------------------------

--
-- Table structure for table `peserta_tugas`
--

CREATE TABLE `peserta_tugas` (
  `id` bigint UNSIGNED NOT NULL,
  `tugas_id` bigint UNSIGNED NOT NULL,
  `peserta_pkl_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peserta_tugas`
--

INSERT INTO `peserta_tugas` (`id`, `tugas_id`, `peserta_pkl_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pembimbing_id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `deadline` date DEFAULT NULL,
  `status` enum('belum','sebagian','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id`, `uuid`, `pembimbing_id`, `judul`, `deskripsi`, `deadline`, `status`, `created_at`, `updated_at`, `file`) VALUES
(1, '6188dca0-4ab3-40f1-b108-8b8226c414d7', 1, 'test', 'test', '2026-06-21', 'selesai', '2026-06-14 09:53:16', '2026-06-14 09:59:40', 'tugas_file/tCfhaCdF8s8YhpBunRG9Itk9kCUt52I8vEIPFC4C.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `tugas_files`
--

CREATE TABLE `tugas_files` (
  `id` bigint UNSIGNED NOT NULL,
  `tugas_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugas_pengumpulans`
--

CREATE TABLE `tugas_pengumpulans` (
  `id` bigint UNSIGNED NOT NULL,
  `tugas_id` bigint UNSIGNED NOT NULL,
  `peserta_pkl_id` bigint UNSIGNED NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `tanggal_kumpul` timestamp NULL DEFAULT NULL,
  `status` enum('belum','dikumpulkan','terlambat','dinilai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `nilai` int DEFAULT NULL,
  `komentar_pembimbing` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tugas_pengumpulans`
--

INSERT INTO `tugas_pengumpulans` (`id`, `tugas_id`, `peserta_pkl_id`, `file`, `catatan`, `tanggal_kumpul`, `status`, `nilai`, `komentar_pembimbing`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'tugas/NfwRh6ii0nHU7gUXQiPLpCPLTyVmaz9lFxQaI6pZ.pdf', NULL, '2026-06-14 09:59:40', 'dikumpulkan', NULL, NULL, '2026-06-14 09:59:40', '2026-06-14 09:59:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','pesertapkl','pembimbing') COLLATE utf8mb4_unicode_ci DEFAULT 'pesertapkl',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, '603185f9-51e1-4f9f-9b1e-45baf7d58d48', 'Test User', 'test@example.com', '2026-06-12 03:39:02', '$2y$12$D/3gQY/fGOkH27opbRMMwOBUi/7f1RvhOz4riS5xaoGJl1xbTqCNa', 'pesertapkl', 0, 'mm5Vpp257Q', '2026-06-12 03:39:03', '2026-06-14 06:24:25'),
(2, 'eb4c58cf-1830-4b12-9677-eb1685658c71', 'Admin PKL', 'admin@gmail.com', NULL, '$2y$12$9IW1rs68Cta12EzfDVNMMel2bvNCr/9/UpZqw73YyJ1SYRhnxZkoe', 'admin', 1, NULL, '2026-06-12 03:39:10', '2026-06-12 03:39:10'),
(3, 'cb2d6d28-a383-4912-aa9d-fe8d0c1338b7', 'Budi Santoso', 'budisantoso@gmail.com', NULL, '$2y$12$nj/LAMCotOLua4JGvqGOceXj8Rh52IlthdjnIdNN/6WZED56w7UU.', 'pembimbing', 1, NULL, '2026-06-12 03:39:11', '2026-06-14 06:24:20'),
(4, 'c4eccd47-95a9-4868-ad0c-f4e3afe97cd1', 'Andi Pratama', 'andi@gmail.com', NULL, '$2y$12$8u3/IGuCUPm1CcbJicR5kuJQ2SDrgs9YXwJ.vQNmgiHsxaJnrT9qi', 'pesertapkl', 1, NULL, '2026-06-12 03:39:12', '2026-06-14 06:26:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensi_peserta_pkl_id_foreign` (`peserta_pkl_id`),
  ADD KEY `absensi_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `divisi_uuid_unique` (`uuid`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `history_divisi`
--
ALTER TABLE `history_divisi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `history_divisi_divisi_id_lama_foreign` (`divisi_id_lama`),
  ADD KEY `history_divisi_divisi_id_baru_foreign` (`divisi_id_baru`),
  ADD KEY `history_divisi_peserta_pkl_id_index` (`peserta_pkl_id`),
  ADD KEY `history_divisi_tanggal_perubahan_index` (`tanggal_perubahan`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan_dokumentasi`
--
ALTER TABLE `laporan_dokumentasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_dokumentasi_laporan_harian_id_foreign` (`laporan_harian_id`);

--
-- Indexes for table `laporan_harians`
--
ALTER TABLE `laporan_harians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `laporan_harians_uuid_unique` (`uuid`),
  ADD KEY `laporan_harians_peserta_pkl_id_foreign` (`peserta_pkl_id`),
  ADD KEY `laporan_harians_user_id_foreign` (`user_id`);

--
-- Indexes for table `log_verifikasi`
--
ALTER TABLE `log_verifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_verifikasi_laporan_harian_id_foreign` (`laporan_harian_id`),
  ADD KEY `log_verifikasi_pembimbing_id_foreign` (`pembimbing_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembimbings`
--
ALTER TABLE `pembimbings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pembimbings_uuid_unique` (`uuid`),
  ADD KEY `pembimbings_user_id_foreign` (`user_id`),
  ADD KEY `pembimbings_divisi_id_foreign` (`divisi_id`);

--
-- Indexes for table `penilaians`
--
ALTER TABLE `penilaians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `penilaians_uuid_unique` (`uuid`),
  ADD KEY `penilaians_peserta_pkl_id_foreign` (`peserta_pkl_id`);

--
-- Indexes for table `peserta_pkls`
--
ALTER TABLE `peserta_pkls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `peserta_pkls_uuid_unique` (`uuid`),
  ADD KEY `peserta_pkls_user_id_foreign` (`user_id`),
  ADD KEY `peserta_pkls_divisi_id_foreign` (`divisi_id`),
  ADD KEY `peserta_pkls_pembimbing_id_index` (`pembimbing_id`),
  ADD KEY `peserta_pkls_status_index` (`status`);

--
-- Indexes for table `peserta_tugas`
--
ALTER TABLE `peserta_tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peserta_tugas_tugas_id_foreign` (`tugas_id`),
  ADD KEY `peserta_tugas_peserta_pkl_id_foreign` (`peserta_pkl_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tugas_uuid_unique` (`uuid`),
  ADD KEY `tugas_pembimbing_id_foreign` (`pembimbing_id`);

--
-- Indexes for table `tugas_files`
--
ALTER TABLE `tugas_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_files_tugas_id_foreign` (`tugas_id`);

--
-- Indexes for table `tugas_pengumpulans`
--
ALTER TABLE `tugas_pengumpulans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_pengumpulans_tugas_id_foreign` (`tugas_id`),
  ADD KEY `tugas_pengumpulans_peserta_pkl_id_foreign` (`peserta_pkl_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_uuid_unique` (`uuid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_divisi`
--
ALTER TABLE `history_divisi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_dokumentasi`
--
ALTER TABLE `laporan_dokumentasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_harians`
--
ALTER TABLE `laporan_harians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `log_verifikasi`
--
ALTER TABLE `log_verifikasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `pembimbings`
--
ALTER TABLE `pembimbings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penilaians`
--
ALTER TABLE `penilaians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `peserta_pkls`
--
ALTER TABLE `peserta_pkls`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `peserta_tugas`
--
ALTER TABLE `peserta_tugas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tugas_files`
--
ALTER TABLE `tugas_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugas_pengumpulans`
--
ALTER TABLE `tugas_pengumpulans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_peserta_pkl_id_foreign` FOREIGN KEY (`peserta_pkl_id`) REFERENCES `peserta_pkls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `history_divisi`
--
ALTER TABLE `history_divisi`
  ADD CONSTRAINT `history_divisi_divisi_id_baru_foreign` FOREIGN KEY (`divisi_id_baru`) REFERENCES `divisi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `history_divisi_divisi_id_lama_foreign` FOREIGN KEY (`divisi_id_lama`) REFERENCES `divisi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `history_divisi_peserta_pkl_id_foreign` FOREIGN KEY (`peserta_pkl_id`) REFERENCES `peserta_pkls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laporan_dokumentasi`
--
ALTER TABLE `laporan_dokumentasi`
  ADD CONSTRAINT `laporan_dokumentasi_laporan_harian_id_foreign` FOREIGN KEY (`laporan_harian_id`) REFERENCES `laporan_harians` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laporan_harians`
--
ALTER TABLE `laporan_harians`
  ADD CONSTRAINT `laporan_harians_peserta_pkl_id_foreign` FOREIGN KEY (`peserta_pkl_id`) REFERENCES `peserta_pkls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporan_harians_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `log_verifikasi`
--
ALTER TABLE `log_verifikasi`
  ADD CONSTRAINT `log_verifikasi_laporan_harian_id_foreign` FOREIGN KEY (`laporan_harian_id`) REFERENCES `laporan_harians` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `log_verifikasi_pembimbing_id_foreign` FOREIGN KEY (`pembimbing_id`) REFERENCES `pembimbings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembimbings`
--
ALTER TABLE `pembimbings`
  ADD CONSTRAINT `pembimbings_divisi_id_foreign` FOREIGN KEY (`divisi_id`) REFERENCES `divisi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pembimbings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penilaians`
--
ALTER TABLE `penilaians`
  ADD CONSTRAINT `penilaians_peserta_pkl_id_foreign` FOREIGN KEY (`peserta_pkl_id`) REFERENCES `peserta_pkls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peserta_pkls`
--
ALTER TABLE `peserta_pkls`
  ADD CONSTRAINT `peserta_pkls_divisi_id_foreign` FOREIGN KEY (`divisi_id`) REFERENCES `divisi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `peserta_pkls_pembimbing_id_foreign` FOREIGN KEY (`pembimbing_id`) REFERENCES `pembimbings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `peserta_pkls_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peserta_tugas`
--
ALTER TABLE `peserta_tugas`
  ADD CONSTRAINT `peserta_tugas_peserta_pkl_id_foreign` FOREIGN KEY (`peserta_pkl_id`) REFERENCES `peserta_pkls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peserta_tugas_tugas_id_foreign` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_pembimbing_id_foreign` FOREIGN KEY (`pembimbing_id`) REFERENCES `pembimbings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tugas_files`
--
ALTER TABLE `tugas_files`
  ADD CONSTRAINT `tugas_files_tugas_id_foreign` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tugas_pengumpulans`
--
ALTER TABLE `tugas_pengumpulans`
  ADD CONSTRAINT `tugas_pengumpulans_peserta_pkl_id_foreign` FOREIGN KEY (`peserta_pkl_id`) REFERENCES `peserta_pkls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tugas_pengumpulans_tugas_id_foreign` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
