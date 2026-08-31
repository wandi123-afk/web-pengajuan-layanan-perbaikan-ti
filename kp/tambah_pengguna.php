<?php
session_start();
include 'koneksi.php';

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Ambil data pengguna yang sedang login
$user_id = $_SESSION['user_id'];
$query_user = "SELECT * FROM users WHERE id = ?";
$stmt_user = $koneksi->prepare($query_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

// Jika data pengguna ditemukan
if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    // Jika tidak ada data pengguna, arahkan ke login
    header("Location: index.php");
    exit;
}

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Validasi input
    if (!empty($username) && !empty($password) && !empty($role)) {
        // Masukkan ke database
        $query = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("sss", $username, $password, $role);

        if ($stmt->execute()) {
            $success_message = "Pengguna baru berhasil ditambahkan.";
        } else {
            $error_message = "Terjadi kesalahan saat menambahkan pengguna.";
        }
    } else {
        $error_message = "Semua kolom harus diisi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna Baru</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
        }
        .sidebar {
            width: 250px;
            background-color: #34495e;
            color: white;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            transition: width 0.5s ease, background-color 0.3s ease, opacity 0.3s ease;
            z-index: 10;
        }
        .sidebar.collapsed {
            width: 80px;
            background-color: #2c3e50;
            opacity: 0.85;
        }
        .sidebar img.profile {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 10px auto;
            transition: opacity 0.5s ease-in-out;
        }
        .sidebar.collapsed img.profile {
            opacity: 0;
            visibility: hidden;
        }
        .sidebar a {
            color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: transform 0.3s ease-out, opacity 0.3s ease-out, padding 0.3s ease-out;
        }
        .sidebar.collapsed a {
            opacity: 0;
            visibility: hidden;
            transform: translateX(-20px);
        }
        .sidebar a:hover {
            background-color: #2c3e50;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-out, padding 0.3s ease-in-out;
            transform: scale(1.05);
            padding-left: 20px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            transition: margin-left 0.5s ease, width 0.5s ease;
        }
        .content.collapsed {
            margin-left: 80px;
            width: calc(100% - 80px);
        }
        .toggle-btn {
            position: absolute;
            top: 20px;
            left: 250px;
            font-size: 18px;
            cursor: pointer;
            z-index: 20;
            transition: left 0.3s ease-out;
        }
        .sidebar.collapsed + .toggle-btn {
            left: 80px;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        form:hover {
            transform: scale(1.02);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border 0.3s ease, background-color 0.3s ease;
        }
        form input:focus, form select:focus {
            border-color: #27ae60;
            background-color: #ecfdf3;
        }
        form button {
            background-color: #27ae60;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        form button:hover {
            background-color: #2ecc71;
            transform: scale(1.05);
        }
        .message {
            margin-bottom: 20px;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }
        .success {
            background-color: #27ae60;
            color: white;
        }
        .error {
            background-color: #e74c3c;
            color: white;
        }
    </style>
   
</head>
<body>
    <div class="sidebar">
        <!-- Profile Image or Default Image if empty -->
        <img src="<?= htmlspecialchars($user['profile_image'] ?: 'images/default-profile.png') ?>" 
             alt="Profile Picture" class="profile">
        <h2 style="text-align: center;">Admin</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="requests.php">Daftar Pengajuan</a>
        <a href="tambah_pengguna.php">Tambah Pengguna</a>
        <a href="profil-admin.php">Profil</a>
        <a href="index.php">Logout</a>
    </div>

    

    <div class="content">
        <h2>Tambah Pengguna Baru</h2>
        
        <!-- Success and Error Messages -->
        <?php if ($success_message): ?>
            <div class="message success"><?= $success_message ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="message error"><?= $error_message ?></div>
        <?php endif; ?>

        <!-- Form to Add New User -->
        <form method="POST">
            <label for="username">Nama Pengguna:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="role">Peran:</label>
            <select name="role" id="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Tambah Pengguna</button>
        </form>
    </div>
</body>
</html>
