-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jun 2026 pada 11.46
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agenhendi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barangs`
--

CREATE TABLE `barangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL,
  `stok_minimum` int(11) NOT NULL DEFAULT 5,
  `harga_jual` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `row_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barangs`
--

INSERT INTO `barangs` (`id`, `supplier_id`, `nama_barang`, `stok`, `stok_minimum`, `harga_jual`, `created_at`, `updated_at`, `row_status`) VALUES
(1, 1, 'Garam 100G', 20, 5, 2000.00, 1, '2026-06-09 02:05:32', '2026-06-09 02:42:57', 0),
(2, 2, 'Rokok Filter 12 Batang', 50, 5, 16000.00, 0, '2026-06-09 02:08:19', '2026-06-09 02:08:19', 1),
(3, 3, 'Kopi ABC Susu', 20, 10, 2000.00, 1, '2026-06-09 02:18:15', '2026-06-09 02:42:43', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_24_200533_create_suppliers_table', 1),
(5, '2025_11_24_202741_create_barangs_table', 1),
(6, '2025_11_25_000001_create_stock_ins_table', 1),
(7, '2025_11_25_000002_create_stock_outs_table', 1),
(8, '2025_11_25_090000_update_fifo_structure', 1),
(9, '2025_11_25_100000_add_sisa_to_stock_ins', 1),
(10, '2025_11_25_101000_fill_sisa_values', 1),
(11, '2025_12_04_000000_add_soft_delete_to_suppliers_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock_ins`
--

CREATE TABLE `stock_ins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `barang_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_masuk` int(11) NOT NULL,
  `sisa` int(11) NOT NULL DEFAULT 0 COMMENT 'Sisa qty yang belum digunakan (untuk FIFO)',
  `keterangan` text DEFAULT NULL,
  `tanggal_kedaluwarsa` date DEFAULT NULL,
  `nomor_lot` varchar(255) DEFAULT NULL COMMENT 'Lot/Batch number dari supplier',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock_outs`
--

CREATE TABLE `stock_outs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `barang_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_terjual` int(11) NOT NULL,
  `product_name_snapshot` varchar(255) NOT NULL,
  `supplier_name_snapshot` varchar(255) NOT NULL,
  `price_snapshot` decimal(12,2) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `status_pembayaran` enum('lunas','belum_lunas') NOT NULL DEFAULT 'belum_lunas',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_supplier` varchar(255) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `row_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `suppliers`
--

INSERT INTO `suppliers` (`id`, `nama_supplier`, `alamat`, `no_hp`, `created_at`, `updated_at`, `deleted_at`, `row_status`) VALUES
(1, 'Cv Sasa Garam', 'Jl. Terus', '021 555 4250', '2026-06-08 23:48:20', '2026-06-08 23:48:20', NULL, 1),
(2, 'PT. Gudang Garam', 'Jl. selamat sampoerna', '021 555 4560', '2026-06-08 23:49:54', '2026-06-08 23:49:54', NULL, 1),
(3, 'PT. ABC', 'Jl. Daan Mogot', '08123456789', '2026-06-09 00:06:35', '2026-06-09 00:06:35', NULL, 1),
(4, 'test', 'test', '08123456789', '2026-06-09 00:07:33', '2026-06-09 02:03:24', '2026-06-09 02:03:24', 0),
(5, 'test', 'Jl. selamat sampoerna', '08123456789', '2026-06-09 00:32:43', '2026-06-09 02:03:30', '2026-06-09 02:03:30', 0),
(6, 'test lagi', 'Jl. selamat sampoerna', '08123456789', '2026-06-09 01:12:58', '2026-06-09 02:02:58', '2026-06-09 02:02:58', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2026-06-08 23:07:59', '$2y$12$KCVk7ToKiT.g3ss6019A4e7YRy2oQ7YO1ai3QJ26qO44ZnCWo9eL.', 'DNsi4AF1kS', '2026-06-08 23:07:59', '2026-06-08 23:07:59'),
(4, 'Egit', 'edsgphotoworks@gmail.com', NULL, '$2y$12$4341IHtJH.qacYTQU5K8bOnNhrDc9pLOqWdFlIrpHAU/4arm.Hr1e', NULL, '2026-06-08 23:34:20', '2026-06-08 23:34:20'),
(5, 'user1', 'test@gmail.com', NULL, '$2y$12$ZP2JhGUUS/E.gqIVZW66Y.TcV7REW0wcMzOz5jREZzAOw1uTbpVJa', NULL, '2026-06-08 23:42:50', '2026-06-08 23:42:50'),
(6, 'user2', 'test1@gmail.com', NULL, '$2y$12$9KO5pR8Drm9NNaHcAEKNAOnxwYb8GfumcAIM3NoSiN1NYQuZXIYSi', NULL, '2026-06-09 00:31:44', '2026-06-09 00:31:44');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barangs`
--
ALTER TABLE `barangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barangs_supplier_id_foreign` (`supplier_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `stock_ins`
--
ALTER TABLE `stock_ins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_ins_barang_id_foreign` (`barang_id`),
  ADD KEY `stock_ins_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `stock_outs`
--
ALTER TABLE `stock_outs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_outs_barang_id_foreign` (`barang_id`),
  ADD KEY `stock_outs_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barangs`
--
ALTER TABLE `barangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `stock_ins`
--
ALTER TABLE `stock_ins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `stock_outs`
--
ALTER TABLE `stock_outs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barangs`
--
ALTER TABLE `barangs`
  ADD CONSTRAINT `barangs_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `stock_ins`
--
ALTER TABLE `stock_ins`
  ADD CONSTRAINT `stock_ins_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_ins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `stock_outs`
--
ALTER TABLE `stock_outs`
  ADD CONSTRAINT `stock_outs_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_outs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
