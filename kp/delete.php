<?php
include 'koneksi.php';

$id = $_GET['id'];

$query = "DELETE FROM requests WHERE id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);

if($stmt->execute()){
    header("Location: admin.php?success=delete");
} else {
    header("Location: admin.php?error=delete");
}
exit;
?>