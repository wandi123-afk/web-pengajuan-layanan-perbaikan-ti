-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Sep 2026 pada 01.32
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

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
(177, 232, 'Sinyal eror', 'Sinyal tiba-tiba hilang', 'Accepted', 'Approve', '2026-06-16 00:43:33'),
(179, 339, 'kabel Wifi Putus', 'tiba-tiba kabel mengeluarkan asap', 'Pending', 'Sedang Perbaikan', '2026-07-30 00:24:49'),
(180, 339, 'Perbaikan Cpu dan update windows', 'Perbaikan berkala dan penggantian alat di cpu', 'Pending', 'Sedang Perbaikan', '2026-07-30 00:25:32');

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
(231, 'admin', '$2y$10$iQYwY3dcnQxZ5VZ8z1hcqeNujudqu1opTE1YUDeTu3h7wE.A01ax6', 'admin', 'uploads/logo1.jpg', 'jl khm asyik', '0895621598019', 'Laki-laki'),
(232, 'baru', '$2y$10$Gy/43NkHH0tCXn30eitnGewIEUoA5SO/1j/aCFSVStP6q5O21PS9y', 'user', NULL, '', '', ''),
(233, 'baru1', '$2y$10$PFaWIkI5HioTwzSNYeF37OCPOtW7nceJXE9Ke7CX41QXBYu/KGLGe', 'user', NULL, '', '', ''),
(338, 'baru8', '$2y$10$wGnDO1q0oxwYc53H2I7Li.Ne1VQgT6XC7B0gNiSO4ctJGgtXLbboW', 'user', NULL, '', '', ''),
(339, 'baru2', '$2y$10$dtQ8xtrJYoRgU4DYJE..Z.MJB3OAFqOB3dPwmjmny/YE2NAMIRKD.', 'user', NULL, '', '', ''),
(340, 'baru2', '$2y$10$Pbc423l7RL7W/e4ErColbO//k9LSnJcsJWGL/uGKWGyD7o5S04V86', 'user', NULL, '', '', ''),
(341, 'baru3', '$2y$10$lKFYNosXRBwoM2CO4uAlL.fU.ZZdpxoaBM8L34KjYbWkQ8Yc8z.8O', 'user', NULL, '', '', ''),
(342, 'baru3', '$2y$10$SSz84MR4nir0PJsYbKQ8TOGb4GFnygShj0jtKLHI1/kO46LD3G9hW', 'user', NULL, '', '', ''),
(343, 'baru2', '$2y$10$dPi8Na/yaNS2u3kZ8FXLVuJ7gEWnWphgDX80nFd7vschuxxWBnNT6', 'user', NULL, '', '', ''),
(344, 'baru2', '$2y$10$kIiBFTy/SwFH0xZwpVr8aeN1m3ydPtwNwVyqgbpyejwHWnzAwmeUO', 'user', NULL, '', '', ''),
(345, 'baru2', '$2y$10$2eBIByOJVtKw/BCy5qzdEu7rqQG0ft3f6MuqkFL9za0eyOSnCZKsy', 'user', NULL, '', '', ''),
(346, 'baru2', '$2y$10$CksW6K.gWXtHlkUJ4WpwPeEZFkHnrBPc2obNdQRwybo4i9yIKuWD6', 'user', NULL, '', '', ''),
(347, 'baru2', '$2y$10$EWGwTQFSUhin1swZ1Rba6OBjo7KNunTgUFtca3m1HPX6vwdHVQ4B.', 'user', NULL, '', '', ''),
(348, 'baru2', '$2y$10$wXPQFsXieXESKTaSl3DvnuWsr6Hr3ORpoIQVyIsC9OM2EfFz2/HTm', 'user', NULL, '', '', ''),
(349, 'baru2', '$2y$10$hkVLpToQVzpK1PAFXD2LWO9.rwKP.E6tKaDj471EGj.9ul2YNq276', 'user', NULL, '', '', ''),
(350, 'baru2', '$2y$10$V7rCpRekygIUaYCFIMRSVeQIJcb30t035J.govVlBmR165a8TOjdK', 'user', NULL, '', '', ''),
(351, 'baru2', '$2y$10$4Yq8DP2Qiu8b9QrIcV6iH.KiCq7DDaSZEY9B5ojcjIjDJHwnVbw4G', 'user', NULL, '', '', ''),
(352, 'baru2', '$2y$10$kdGKnQ9gvslJBXq7i54kbOQarqk0LTu2WBAbrYD1Xz2pguTM9wh..', 'user', NULL, '', '', ''),
(353, 'baru2', '$2y$10$etN1Gup3XVqX5B.MwuYJo.57/fiZ0OX/el6RHGt.x2WqR9Gip7G9q', 'user', NULL, '', '', ''),
(354, 'baru2', '$2y$10$4d5550ItKpisuPCayIkaIuLXuMgvoqwzabU7JvcBkbkb8.ONG9u.G', 'user', NULL, '', '', ''),
(355, 'baru2', '$2y$10$bbT4zdkzDarR/18MrMu45ey2atWEs0Q9qKJ9c9/6ptB0.doWpVP.y', 'user', NULL, '', '', ''),
(356, 'baru1', '$2y$10$zQNWkT/S8djMwiHZqvY/N.8KlW4xAsqEU2yIGc.x4QzR6Xp8mjkHW', 'user', NULL, '', '', ''),
(357, 'baru2', '$2y$10$WGGDqI3Oa3fIUlvsjZlyzu7SGfVHk.pgKROsR4YW4h.hmL1OG8DLW', 'user', NULL, '', '', '');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=358;

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
