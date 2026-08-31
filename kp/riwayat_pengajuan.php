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

// Query untuk mendapatkan riwayat pengajuan oleh user saat ini
$query_requests = "SELECT * FROM requests WHERE user_id = ? ORDER BY created_at DESC";
$stmt_requests = $koneksi->prepare($query_requests);
$stmt_requests->bind_param("i", $user_id);
$stmt_requests->execute();
$result_requests = $stmt_requests->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengajuan</title>
    <link rel="stylesheet" href="styles-form.css">
    <style>
        /* Navbar Styles */
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

        /* Main Content Styles */
        .content {
            margin-left: 250px;
            padding: 20px;
            box-sizing: border-box;
        }

        .content h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
            word-wrap: break-word;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        

        table th {
            background-color: #34495e;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }

        table td {
            vertical-align: top;
        }

        /* Responsive Design for Tables */
        @media (max-width: 768px) {
            .navbar {
                width: 200px;
            }
            .content {
                margin-left: 200px;
            }

            table th, table td {
                padding: 8px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <img src="<?= $user['profile_image'] ?: 'default-profile.png' ?>" alt="Profile Picture">
        <a href="user.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="ajukan_layanan.php"><i class="fas fa-file-alt"></i> Ajukan Layanan</a>
        <a href="riwayat_pengajuan.php" class="active"><i class="fas fa-history"></i> Riwayat Pengajuan</a>
        <a href="profil-user.php"><i class="fas fa-user"></i> Profil</a>
        <a href="index.php"><i class="fas fa-user"></i> logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Riwayat Pengajuan Anda</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Respon</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_requests->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result_requests->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td><?= htmlspecialchars($row['response']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Belum ada riwayat pengajuan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
