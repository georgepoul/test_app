<?php
session_start();
if ($_SESSION['role'] == 'Student') {
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
        require('../templates/studentHeader.inc.php');
        ?>

    </head>
    <body style="background-color: #080710">

    <?php

    include('../database/db_connection.php');

    try {
        $log = $conn->prepare("select Lesson_Name  from php_db.Lesson");
        $log->execute();

        $row = $log->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {

        echo 'Something bad happened';
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['lesson'])) {
            $_SESSION['lesson'] = $_POST['lesson'];
            header("Location: test.php");
        }
    }
    ?>


    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="post">

        <p style="text-align: left">The courses are: </p>

        <?php
        if ($log->rowCount() == 0) {

            echo "<p> 'There are no courses'</p>";

        } else {

            print '<ul>';
            for ($i = 0; $i < sizeof($row); $i++) {

                ?>
                <li> <?php print $row[$i]['Lesson_Name'] ?></li>
                <?php
            }

            print '</ul>';
        }
        $conn = null;
        ?>
        <p style="text-align: left">Please choose a Course to write your Test!</p>
        <select name="lesson">
            <?php
            for ($i = 0; $i < sizeof($row); $i++) {
                ?>
                <option value="<?php echo $row[$i]['Lesson_Name']; ?>"><?php echo $row[$i]['Lesson_Name']; ?></option>

                <?php
            }
            ?>
        </select>
        <button type="submit"> Submit</button>

    </form>
    </body>
    </html>

    <?php
} else {
    echo '<h4>401, Unauthorized</h4>';
}
?>
