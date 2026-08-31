<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success_message = "";
$error_message = "";

// Proses update profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $profile_image = $_FILES['profile_image'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];

    // Proses upload gambar profil
    if ($profile_image['error'] == 0) {
        // Validasi tipe file gambar
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($profile_image['type'], $allowed_types)) {
            $target_dir = "uploads/";
            
            // Pastikan folder 'uploads/' ada dan bisa ditulis
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true); // Membuat folder jika belum ada
            }

            $target_file = $target_dir . basename($profile_image['name']);
            if (move_uploaded_file($profile_image['tmp_name'], $target_file)) {
                // Update gambar profil di database
                $query = "UPDATE users SET profile_image=? WHERE id=?";
                $stmt = $koneksi->prepare($query);
                $stmt->bind_param("si", $target_file, $user_id);
                $stmt->execute();
            } else {
                $error_message = "Gagal mengupload gambar.";
            }
        } else {
            $error_message = "Hanya file gambar (JPG, PNG, JPEG) yang diperbolehkan.";
        }
    }

    // Update data lainnya
    if ($password) {
        $query = "UPDATE users SET username=?, password=?, address=?, phone=?, gender=? WHERE id=?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("sssssi", $username, $password, $address, $phone, $gender, $user_id);
    } else {
        $query = "UPDATE users SET username=?, address=?, phone=?, gender=? WHERE id=?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("ssssi", $username, $address, $phone, $gender, $user_id);
    }
    
    if ($stmt->execute()) {
        $success_message = "Profil berhasil diperbarui.";
    } else {
        $error_message = "Gagal memperbarui profil.";
    }
}

// Ambil data pengguna
$query = "SELECT * FROM users WHERE id=?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Pastikan data pengguna ada
if (!$user) {
    $error_message = "Pengguna tidak ditemukan.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }

        .sidebar img.profile {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 10px auto;
        }

        .sidebar a {
            color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
        }

        .sidebar a:hover {
            background-color: #2980b9;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
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
        <img src="<?= $user['profile_image'] ? $user['profile_image'] : 'default-profile.png' ?>" alt="Profile Picture" class="profile">
        <h2 style="text-align: center;"><?= htmlspecialchars($user['username']) ?></h2>
        <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="ajukan_layanan.php"><i class="fas fa-file-alt"></i> Ajukan Layanan</a>
        <a href="riwayat_pengajuan.php" class="active"><i class="fas fa-history"></i> Riwayat Pengajuan</a>
        <a href="profil-user.php"><i class="fas fa-user"></i> Profil</a>
        <a href="index.php"><i class="fas fa-user"></i> log out</a>
    </div>

    <div class="content">
        <div class="profile-container">
            <h2>Profil Anda</h2>
            <?php if ($success_message): ?>
                <div class="message success"><?= $success_message ?></div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="message error"><?= $error_message ?></div>
            <?php endif; ?>

            <img src="<?= $user['profile_image'] ? $user['profile_image'] : 'default-profile.png' ?>" alt="Profile Image" width="150">

            <form method="POST" enctype="multipart/form-data">
                <label for="username">Nama:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>

                <label for="address">Alamat:</label>
                <textarea name="address" id="address" rows="3" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>

                <label for="phone">No HP:</label>
                <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>

                <label for="gender">Jenis Kelamin:</label>
                <select name="gender" id="gender" required>
                    <option value="Laki-laki" <?= ($user['gender'] ?? '') == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= ($user['gender'] ?? '') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                </select>

                <label for="password">Password (kosongkan jika tidak ingin mengubah):</label>
                <input type="password" name="password" id="password">

                <label for="profile_image">Gambar Profil:</label>
                <input type="file" name="profile_image" id="profile_image">

                <button type="submit">Update Profil</button>
            </form>
        </div>
    </div>
</body>
</html>
