<?php

include 'koneksi.php';

/* Password default untuk testing */
$password = password_hash('admin123', PASSWORD_DEFAULT);

/* Reset akun admin */
$sql = "
UPDATE users
SET
    password = '$password',
    role = 'admin'
WHERE username = 'admin'
";

if(mysqli_query($koneksi, $sql)){
    echo "Database reset berhasil";
}else{
    echo "Error: " . mysqli_error($koneksi);
}
?>