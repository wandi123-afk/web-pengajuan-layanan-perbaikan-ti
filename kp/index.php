<?php
session_start();
error_reporting(E_ALL);

ini_set('display_errors', 1);
include 'koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username)) {
        $error_username = "Username harus diisi";
    }
    if (empty($password)) {
        $error_password = "Password harus diisi"    ;
    }
    
if (empty($error_username) && empty($error_password)) {
    
$query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = $koneksi->query($query);

    
   if (mysqli_num_rows($result) == 1) {

    $user = mysqli_fetch_assoc($result);
  
        
 if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php"); 
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username atau password salah!";
    }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles-login.css">
    <title>Login Page</title>
</head>
<body>
    <!-- Background Image -->
    <img src="hero.jpg" alt="Background Image">

    <h2>Login </h2> width="20"
    <form method="POST" action="">
        <input
    type="text"
    name="username"
    data-id="username"
    placeholder="Username">

        <?php
        if(isset($error_username)){
           echo "<p class='error'>$error_username</p>";   
        }
        ?>

        <input
    type="password"
    name="password"
    data-id="password"
    placeholder="Password">

        <?php
        if(isset($error_password)){
           echo "<p class='error'>$error_password</p>";   
        }
        ?>
        
        <button type="submit" data-id="submit">
    Login
</button>
       
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    </form>
</body>
</html>
