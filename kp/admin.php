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
$user = $result_user->fetch_assoc();

// Query untuk mengambil data request (riwayat pengajuan)
$query_requests = "SELECT requests.id,
                          requests.title,
                          requests.description,
                          requests.status,
                          requests.response,
                          users.username
                   FROM requests
                   JOIN users ON requests.user_id = users.id";
$result_requests = $koneksi->query($query_requests);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Dashboard</title>

    <link rel="stylesheet" href="styles.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
            color: #333;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #34495e;
            color: #fff;
            padding: 15px 25px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo {
            font-size: 26px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .navbar .user-info {
            display: flex;
            align-items: center;
            font-size: 16px;
        }

        .navbar .user-info span {
            margin-right: 10px;
            font-weight: 500;
        }

        .navbar .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
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
            letter-spacing: 1px;
        }

        .sidebar a {
            color: #fff;
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
            table-layout: fixed;
        }

        table th,
        table td {
            padding: 12px 15px;
            text-align: left;
            font-size: 16px;
            color: #333;
            border: 1px solid #ddd;
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
            vertical-align: middle;
            word-wrap: break-word;
        }

        /* Lebar kolom */
        table th:nth-child(1),
        table td:nth-child(1) {
            width: 10%;
        }

        table th:nth-child(2),
        table td:nth-child(2) {
            width: 13%;
        }

        table th:nth-child(3),
        table td:nth-child(3) {
            width: 35%;
        }

        table th:nth-child(4),
        table td:nth-child(4) {
            width: 10%;
        }

        table th:nth-child(5),
        table td:nth-child(5) {
            width: 15%;
        }

        /* Kolom Aksi */
        table th:nth-child(6),
        table td:nth-child(6) {
            width: 17%;
            text-align: center;
            white-space: nowrap;
        }

        /* Tombol Edit */
        .btn-edit {
            display: inline-block;
            padding: 9px 14px;
            margin-right: 5px;
            background-color: #5bc0de;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn-edit:hover {
            background-color: #31b0d5;
        }

        /* Tombol Delete */
        .btn-delete {
            display: inline-block;
            padding: 9px 14px;
            background-color: #d9534f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: #c9302c;
        }

        /* Alert */
        .alert-success {
            padding: 12px 15px;
            margin-bottom: 15px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 5px;
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
                font-size: 14px;
            }

            .btn-edit,
            .btn-delete {
                padding: 7px 10px;
                font-size: 13px;
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

            <img
                src="<?= htmlspecialchars($user['profile_image'] ?: 'images/default-profile.png') ?>"
                alt="Profile"
            >
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">

        <img
            src="<?= htmlspecialchars($user['profile_image'] ?: 'images/default-profile.png') ?>"
            alt="Profile"
            class="profile"
        >

        <h2>Admin</h2>

        <a href="dashboard.php">Dashboard</a>
        <a href="requests.php">Daftar Pengajuan</a>
        <a href="tambah_pengguna.php">Tambah Pengguna</a>
        <a href="profil-admin.php">Profil</a>
        <a href="index.php">Logout</a>

    </div>

    <!-- Content -->
    <div class="content">

        <h2>Riwayat Pengajuan</h2>

        <?php if(isset($_GET['hapus']) && $_GET['hapus'] == 'sukses'): ?>

            <div class="alert-success">
                User Deleted Successfully
            </div>

        <?php endif; ?>


        <?php if(isset($_GET['update']) && $_GET['update'] == 'sukses'): ?>

            <div class="alert-success">
                User Berhasil Diupdate
            </div>

        <?php endif; ?>


        <table>

            <thead>
                <tr>
                    <th>User</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Balasan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php while ($row = $result_requests->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($row['username']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['title']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['description']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['status']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['response']) ?>
                        </td>

                        <!-- KOLOM AKSI -->
                        <td>

                            <a
                                href="edit_pengajuan.php?id=<?= $row['id']; ?>"
                                class="btn-edit"
                            >
                                Edit
                            </a>

                            <a
                                href="#"
                                class="btn-delete"
                                onclick="hapusData(<?= $row['id']; ?>); return false;"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>


    <!-- SweetAlert Delete -->
    <script>

        function hapusData(id) {

            Swal.fire({
                title: 'Yakin?',
                text: 'Data akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {

                if (result.isConfirmed) {

                    window.location.href =
                        'hapus_pengajuan.php?id=' + id;

                }

            });

        }

    </script>


    <?php if(isset($_GET['success']) && $_GET['success'] == 'delete'): ?>

        <script>

            Swal.fire({
                title: 'Berhasil!',
                text: 'Data berhasil dihapus.',
                icon: 'success',
                confirmButtonText: 'OK'
            });

        </script>

    <?php endif; ?>

</body>
</html>