<?php
session_start();
include 'koneksi.php';

// Periksa apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

// Ambil data pengguna untuk profil sidebar
$user_id = $_SESSION['user_id'];
$query_user = "SELECT username, profile_image FROM users WHERE id = ?";
$stmt_user = $koneksi->prepare($query_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

// Proses update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_request'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    $response = $_POST['response'];

    // Validasi input
    if (!empty($request_id) && !empty($status)) {
        $query = "UPDATE requests SET status=?, response=? WHERE id=?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("ssi", $status, $response, $request_id);
        if ($stmt->execute()) {
            $message = "Pengajuan berhasil diperbarui!";
        } else {
            $message = "Gagal memperbarui pengajuan.";
        }
    } else {
        $message = "Status dan balasan tidak boleh kosong.";
    }
}

// Query untuk mengambil semua data request
$query = "SELECT requests.*, users.username FROM requests JOIN users ON requests.user_id = users.id";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fa;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2c3e50;
            color: #fff;
            padding: 10px 25px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo {
            font-size: 26px;
            font-weight: 600;
        }

        .navbar .user-info {
            display: flex;
            align-items: center;
            font-size: 16px;
        }

        .navbar .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #34495e;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 60px;
            left: 0;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            box-shadow: 2px 0 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar img.profile {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 10px auto;
            object-fit: cover;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 20px;
            margin-top: 20px;
            color: #fff;
            font-weight: 600;
        }

        .sidebar a {
            color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
            font-size: 16px;
            transition: background-color 0.3s ease, padding-left 0.3s ease;
            border-bottom: 1px solid #34495e;
        }

        .sidebar a:hover {
            background-color: #1abc9c;
            padding-left: 25px;
        }

        /* Content */
        .content {
            margin-left: 250px;
            padding: 30px;
            margin-top: 60px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            min-height: calc(100vh - 120px);
        }

        h2 {
            font-size: 24px;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 20px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            font-size: 16px;
            color: #333;
        }

        table th {
            background-color: #34495e;
            color: #fff;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        table td {
            text-align: left;
        }

        /* Message */
        .message {
            margin-bottom: 20px;
            padding: 10px;
            color: #ffffff;
            background-color: #27ae60;
            border-radius: 5px;
        }

        /* Form */
        form select,
        form textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            background-color: #1abc9c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        form button:hover {
            background-color: #16a085;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px;
            }

            .sidebar {
                width: 200px;
                top: 70px;
            }

            .content {
                margin-left: 200px;
            }

            .sidebar a {
                font-size: 14px;
                padding: 12px;
            }

            .navbar .logo {
                font-size: 20px;
            }

            h2 {
                font-size: 20px;
            }

            table th,
            table td {
                padding: 10px 12px;
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                width: 100%;
                top: 70px;
                position: relative;
            }

            .content {
                margin-left: 0;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .sidebar a {
                padding: 10px;
                text-align: center;
            }

            .sidebar h2 {
                font-size: 18px;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">Admin</div>
        <div class="user-info">
            <span><?= htmlspecialchars($user['username']) ?></span>
            <img src="<?= htmlspecialchars($user['profile_image'] ?: 'images/default-profile.png') ?>" alt="Profile Picture">
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="<?= htmlspecialchars($user['profile_image'] ?: 'images/default-profile.png') ?>" 
             alt="Profile Picture" class="profile">
        <h2>Admin</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="requests.php">Daftar Pengajuan</a>
        <a href="tambah_pengguna.php">Tambah Pengguna</a>
        <a href="profil-admin.php">Profil</a>
        <a href="index.php">Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Daftar Pengajuan</h2>

        <?php if (isset($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <table>
            <tr>
                <th>User</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Balasan</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['response']) ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                            <select name="status">
                                <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Accepted" <?= $row['status'] == 'Accepted' ? 'selected' : '' ?>>Accepted</option>
                                <option value="Rejected" <?= $row['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select><br>
                            <textarea name="response" placeholder="Berikan balasan"><?= htmlspecialchars($row['response']) ?></textarea><br>
                            <button type="submit" name="update_request">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
