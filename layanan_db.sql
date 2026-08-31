-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Jan 2025 pada 04.31
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `layanan_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `requests`
--

INSERT INTO `requests` (`id`, `user_id`, `title`, `description`, `status`, `response`, `created_at`) VALUES
(1, 4, 'kabel rusak', 'tidak bisah nyambung ', 'Rejected', 'kemarin lah dibeneri', '2024-11-13 09:17:49'),
(2, 4, 'upgrade', 'pembaruan', 'Pending', 'ok', '2024-11-29 02:06:11'),
(3, 4, 'lolo', 'kabel acur', 'Pending', '+', '2025-01-19 20:04:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `profile_image`, `address`, `phone`, `gender`) VALUES
(2, 'user1', '6ad14ba9986e3615423dfca256d04e3f', 'user', NULL, '', '', ''),
(3, 'admin', '$2y$10$HYnpGIVlLx4ygCev/BhggueV9J75nW5Jx3/HKmfYZQ.wyU/EomBz2', 'admin', NULL, '', '', ''),
(4, 'user', '$2y$10$UP58LgjwExW3wQpJzw8xLO1DDuNZ3B2srG6QRb2t4AK7QCbtbyNBa', 'user', 'uploads/169158iD472E9D0179439E9.jpg', 'lolo', '32136435', 'Laki-laki'),
(5, 'admin', '$2y$10$examplehashedpassword', 'admin', 'profile.jpg', '', '', ''),
(6, 'user1', '$2y$10$examplehashedpassword2', 'user', NULL, '', '', ''),
(7, 'admin2', '$2y$10$A2G0XfIsgLGNQmHf8Cy9f.Qq1WaxFLtw0/NjEw8reK8coHZ8qofGe', 'admin', 'uploads/24104b0a4d54aef2bf25244d5a38708d.jpg', 'pipy', '64545345', 'Laki-laki'),
(8, 'redo', '$2y$10$2f1dmnfE08XIsZ6nqbOvRuJTyVnvBdJ3sYmGTGx/zzoi4y/Fbnb1K', 'admin', NULL, '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `fk_user_request` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
