<?php
include 'koneksi.php';

$id = $_POST['id'];
$title = trim($_POST['title']);
$description = trim($_POST['description']);
$status = $_POST['status'];

if(empty($title)){
    header("Location: edit_pengajuan.php?id=$id&error=title");
    exit;
}
if(empty($description)){
    header("Location: edit_pengajuan.php?id=$id&error=description");
    exit;
}

$query = "UPDATE requests
          SET title=?,
              description=?,
              status=?
          WHERE id=?";

$stmt = $koneksi->prepare($query);
$stmt->bind_param(
    "sssi",
    $title,
    $description,
    $status,
    $id
);

if($stmt->execute()){
    header("Location: admin.php?update=sukses");
} else {
    header("Location: edit_pengajuan.php?id=$id&error=gagal");
}

exit;
?>