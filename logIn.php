<!DOCTYPE html>
<?php
session_start();
?>
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


<form method="post">
    <h3>Login Here</h3>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        include('database/db_connection.php');

        try {
            $log = $conn->prepare("select password, role  from php_db.user where username = :username");
            $log->bindParam(':username', $username);
            $log->execute();

            $row = $log->fetchAll(PDO::FETCH_ASSOC);

            if ($log->rowCount() > 0 and password_verify($password, $row[0] ['password'])) {

                $_SESSION['username'] = $username;
                $_SESSION['role'] = $row[0]['role'];
                $_SESSION['time'] = time();

                if ($_SESSION['role'] == 'Teacher'){

                    header("Location: teacher/teacher.php");

                }else{

                    header("Location: Student.php");
                }
                exit();

            } else {
                echo "<p style='color: red; font-size: small; text-align: center' > 
                            The username or password are incorrect.
                      </p> ";
            }
        } catch (PDOException $e) {

            echo 'Something bad happened';
        }
    }
    ?>

    <label for="username">Username</label>
    <input type="text" name="username" placeholder="Username or email" id="username" required>
    <label for="password">Password</label>
    <input type="password" name="password" placeholder="Password" id="password" required>

    <button>Log In</button>

</form>
</body>
</html>

