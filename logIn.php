<!DOCTYPE html>
<html lang="en">
<head>
    <title>Log in</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

    <?php
    require('templates/header.inc.php');
    ?>

</head>
<body style="background-color: #080710">
<div class="background">
    <div class="shape"></div>
    <div class="shape"></div>
</div>
<form>
    <h3>Login Here</h3>

    <label for="username">Username</label>
    <input type="text" placeholder="Username or email" id="username" required>
    <label for="password">Password</label>
    <input type="password" placeholder="Password" id="password" required>

    <button>Log In</button>

</form>
</body>
</html>
<?php
$user = filter_input(INPUT_POST, 'username');
$pass = filter_input(INPUT_POST, 'password');


?>
