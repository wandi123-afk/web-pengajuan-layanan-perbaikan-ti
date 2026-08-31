<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$query_user = "SELECT profile_image FROM users WHERE id = ?";
$stmt_user = $koneksi->prepare($query_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

// Jika tidak ada data pengguna
if (!$user) {
    die("Pengguna tidak ditemukan.");
}

// Proses form pengajuan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Validasi input
    if (empty($title) || empty($description)) {
        echo "<script>alert('Semua field harus diisi!');</script>";
    } else {
        // Insert data ke tabel requests
        $query_insert = "INSERT INTO requests (user_id, title, description, status, created_at) VALUES (?, ?, ?, 'Pending', NOW())";
        $stmt_insert = $koneksi->prepare($query_insert);
        $stmt_insert->bind_param("iss", $user_id, $title, $description);

        if ($stmt_insert->execute()) {
            echo "<script>alert('Pengajuan berhasil dikirim!'); window.location.href = 'riwayat_pengajuan.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan, coba lagi nanti.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Layanan</title>
    <link rel="stylesheet" href="styles-form.css">
    <style>
        /* Gaya CSS untuk Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #34495e;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            box-sizing: border-box;
        }

        .navbar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            width: 100%;
            padding: 15px 20px;
            text-align: left;
            transition: background-color 0.3s;
            box-sizing: border-box;
        }

        .navbar a:hover,
        .navbar a.active {
            background-color: #2c3e50;
        }

        .navbar .logout-btn {
            margin-top: auto;
            margin-bottom: 20px;
            background-color: #c0392b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 80%;
            box-sizing: border-box;
        }

        .navbar .logout-btn:hover {
            background-color: #e74c3c;
        }

        /* Gaya CSS untuk Konten Utama */
        .content {
            margin-left: 250px;
            padding: 20px;
            box-sizing: border-box;
        }

        .content h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        /* Gaya CSS untuk Formulir */
        form {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
        }

        form input[type="text"],
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form textarea {
            resize: vertical;
            height: 150px;
        }

        form button {
            padding: 10px 20px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        form button:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <img src="<?= $user['profile_image'] ?: 'default-profile.png' ?>" alt="Profile Picture">
        <a href="user.php">Dashboard</a>
        <a href="ajukan_layanan.php">Ajukan Layanan</a>
        <a href="riwayat_pengajuan.php">Riwayat Pengajuan</a>
        <a href="profil-user.php">Profil</a>
        <a href="index.php">logout</a>
       
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Form Pengajuan Layanan</h2>
        <form method="POST" action="">
            <input type="text" name="title" placeholder="Judul Pengajuan" required>
            <textarea name="description" placeholder="Deskripsi Pengajuan" required></textarea>
            <button type="submit">Ajukan</button>
        </form>
    </div>
</body>
</html>
