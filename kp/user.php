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
$query_requests = "SELECT status, COUNT(*) as status_count FROM requests WHERE user_id = ? GROUP BY status";
$stmt_requests = $koneksi->prepare($query_requests);
$stmt_requests->bind_param("i", $user_id);
$stmt_requests->execute();
$result_requests = $stmt_requests->get_result();

// Data untuk grafik
$status_data = [
    'Pending' => 0,
    'Diterima' => 0,
    'Ditolak' => 0
];

while ($row = $result_requests->fetch_assoc()) {
    $status_data[$row['status']] = $row['status_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="styles-form.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }

        /* Navbar Styling */
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
            transition: all 0.3s ease;
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
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }

        .navbar a:hover {
            background-color: #2c3e50;
        }

        .navbar a i {
            margin-right: 15px;
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
            text-align: center;
        }

        .navbar .logout-btn:hover {
            background-color: #e74c3c;
        }

        /* Main Content */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        /* Chart Styling */
        .chart-container {
            width: 100%;
            max-width: 1800px;
            margin: 60px auto;
        }

        canvas {
            max-height: 800px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <!-- Gambar profil -->
        <img src="<?= $user['profile_image'] ?: 'default-profile.png' ?>" alt="Profile Picture">
        
        <!-- Navigasi menu -->
        <a href="user.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="ajukan_layanan.php"><i class="fas fa-file-alt"></i> Ajukan Layanan</a>
        <a href="riwayat_pengajuan.php" class="active"><i class="fas fa-history"></i> Riwayat Pengajuan</a>
        <a href="profil-user.php"><i class="fas fa-user"></i> Profil</a>
        <a href="index.php"><i class="fas fa-user"></i> logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Grafik Pengajuan Anda</h2>

        <!-- Grafik Penjumlahan Pengajuan -->
        <div class="chart-container">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <script>
        // Data untuk grafik
        const statusData = {
            labels: ['Pending', 'Diterima', 'Ditolak'],
            datasets: [{
                label: 'Jumlah Pengajuan Berdasarkan Status',
                data: [<?= $status_data['Pending'] ?>, <?= $status_data['Diterima'] ?>, <?= $status_data['Ditolak'] ?>],
                backgroundColor: ['#3498db', '#2ecc71', '#e74c3c'],
                hoverOffset: 4
            }]
        };

        // Konfigurasi untuk pie chart
        const config = {
            type: 'pie',
            data: statusData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                let percentage = ((context.raw / total) * 100).toFixed(2);
                                return `${context.label}: ${context.raw} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        };

        // Render grafik
        const statusChart = new Chart(
            document.getElementById('statusChart'),
            config
        );
    </script>
</body>
</html>