<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/signUp.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <!--Stylesheet-->

    <?php
    require('templates/header.inc.php');
    ?>

</head>
<body style="background-color: #080710">
<div class="background">
    <div class="shape"></div>
    <div class="shape"></div>
</div>


<form method="post" style="height: auto">

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $password = $_POST['password'];
        $confPassword = $_POST['confPassword'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $role = $_POST['role'];

        include('database/db_connection.php');

        try {
            $UserExists = $conn->prepare("select username from php_db.user where username = :username");
            $UserExists->bindParam(':username', $username);
            $UserExists->execute();

            $EmailExists = $conn->prepare('select email from php_db.user where email = :email');
            $EmailExists->bindParam(':email', $email);
            $EmailExists->execute();

        } catch (PDOException $e) {

            echo 'Something bad happened';
        }

        if ($password != $confPassword) {

            echo "<p style='color: red; font-size: small; text-align: center' > 
                            Oops! Password did not match! Try again.
                      </p> ";

        } elseif ($UserExists->rowCount() > 0) {

            echo "<p style='color: red; font-size: small; text-align: center' > 
                            The username already exist.
                      </p> ";

        } elseif ($EmailExists->rowCount() > 0) {

            echo "<p style='color: red; font-size: small; text-align: center' > 
                            The email already exist.
                      </p> ";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            try {

                $stm = $conn->prepare("insert into php_db.user(username, email, role, password)
                    values (:username, :email, :role, :hashedPassword)");


                $stm->bindParam(':username', $username);
                $stm->bindParam(':email', $email);
                $stm->bindParam(':role', $role);
                $stm->bindParam(':hashedPassword', $hashedPassword);

                $stm->execute();

                echo " <h3> The sign up was successful now you can log in </h3>";
                $conn = null;
                exit();

            } catch (PDOException $e) {

                echo 'Something bad happened';
            }

        }

    }
    ?>
    <h3>Sign Up Here</h3>

    <label for="email">Email</label>
    <input type="email" name="email" placeholder="email" id="email" required
           value="<?php if (isset($email)) {
               echo $email;
           } ?>">
    <label for="username">Username</label>
    <input type="text" name="username" placeholder="username" id="username" required
           value="<?php if (isset($username)) {
               echo $username;
           } ?>">
    <label for="password" style="font-size: small; color: #ff512f">Password: The password mast contain one upper case
        and one lower case
        character
        a symbol and a number. The length mast be more than 10 characters.</label>
    <input type="password" name="password" placeholder="Password" id="password" required minlength="10"
           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,24}$">
    <label for="confPassword">Confirm Password</label>
    <input type="password" name="confPassword" placeholder="Confirm Password" id="confPassword" required minlength="10"
           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,24}$">
    <label for="role">Role:</label>
    <select type="text" id="role" name="role" required>
        <option value="" disabled selected hidden>Please choose a Role</option>
        <option value="Student" <?php if (isset($role) and $role = "Student") {
            echo 'selected';
        } ?>>Student
        </option>
        <option value="Teacher" <?php if (isset($role) and $role = "Teacher") {
            echo 'selected';
        } ?>>Teacher
        </option>
    </select>

    <button>Sign Up</button>
</form>
</body>
</html>
