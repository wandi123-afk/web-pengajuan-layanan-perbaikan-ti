<?php
include 'koneksi.php';

$id = $_GET['id'];

$query = "SELECT * FROM requests WHERE id=?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();

$data = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pengajuan</title>
<style>
.error-message{
    background:#f8d7da;
    color:#721c24;
    padding:10px;
    margin-bottom:15px;
    border:1px solid #f5c6cb;
    border-radius:4px;
}
</style>

</head>
<body>

<h2>Edit Pengajuan</h2>

<?php if(isset($_GET['error']) && $_GET['error'] == 'title'): ?>
    <div class="error-message">
        The name field is required.
    </div>
<?php endif; ?>

<?php if(isset($_GET['error']) && $_GET['error'] == 'description'): ?>
    <div class="error-message">
        The description field is required.
    </div>
<?php endif; ?>

<form action="update_pengajuan.php" method="POST">

    <input type="hidden" name="id"
           value="<?= $data['id']; ?>">

    <label>Judul</label><br>
    <input type="text"
           name="title"
           value="<?= $data['title']; ?>"><br><br>

    <label>Deskripsi</label><br>
    <textarea name="description"><?= $data['description']; ?></textarea><br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="Pending"
            <?= $data['status']=='Pending'?'selected':'' ?>>
            Pending
        </option>

        <option value="Accepted"
            <?= $data['status']=='Accepted'?'selected':'' ?>>
            Accepted
        </option>

        <option value="Rejected"
            <?= $data['status']=='Rejected'?'selected':'' ?>>
            Rejected
        </option>
    </select>

    <br><br>

    <button type="submit">
        submit
    </button>

</form>

</body>
</html>
