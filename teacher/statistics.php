<?php
session_start();
if ($_SESSION['role'] == 'Teacher') {
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Test Page</title>
        <link rel="stylesheet" href="../css/template.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="stylesheet" href="../https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

        <?php
        require('../templates/teacherHeader.inc.php');
        ?>

    </head>
    <body style="background-color: #080710">
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="post">
        <label for="dif">You wont to see statistics by:</label>
        <select name="dif" id="dif" required>
            <option hidden disabled selected value="">Please choose an option</option>
            <option <?php if (isset($_SESSION['stat']) and $_SESSION['stat']== 'difficulty') echo 'selected';?>  value="difficulty">Difficulty</option>
            <option <?php if (isset($_SESSION['stat']) and $_SESSION['stat']== 'course') echo 'selected';?> value="course">Course</option>
            <option <?php if (isset($_SESSION['stat']) and $_SESSION['stat']== 'question') echo 'selected';?> value="question">Question</option>
        </select>

            <label for="input_time">Insert date for statistic:</label>
            <input type='date' name='input_time' min='2023-01-17' required>

        <button type="submit">Next</button>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_SESSION['stat'] = $_POST['dif'];

            $_SESSION['date'] = $_POST['input_time'];

            header("Location: statistic2.php");
        }

        ?>

    </form>
    </body>
    </html>

    <?php
} else {
    echo '<h4>401, Unauthorized</h4>';
}
?>

