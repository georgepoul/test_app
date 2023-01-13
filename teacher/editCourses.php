<?php
session_start();
if ($_SESSION['role'] == 'Teacher') {
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Test Page</title>
        <link rel="stylesheet" href="../css/question.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="stylesheet" href="../https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

        <?php
        require('../templates/teacherHeader.inc.php');
        ?>

    </head>
    <body style="background-color: #080710">

    <?php

    include('../database/db_connection.php');

    try {
        $log = $conn->prepare("select Lesson_Name  from php_db.Lesson");
        $log->execute();

        $row = $log->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['lessons'] = $row;

    } catch (PDOException $e) {

        echo 'Something bad happened';
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['lesson'])) {
            $_SESSION['lesson'] = @$_POST['lesson'];
            header("Location: questions.php");
        }
    }
    ?>


    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="post">

        <p style="text-align: left">The courses are: </p>
<table >
            <tr class="nr">
                <th>Courses</th>
                <th></th>

            </tr>

            <?php

            for ($i = 0; $i < $log->rowCount(); $i++) {
                ?>
                <tr>
                    <td >
                        <?php echo $row[$i]['Lesson_Name'] ?>
                    </td>

                    <td><a style="color: white">
                            Delete
                        </a>
                    </td>
                </tr>
                <?php
            }

            ?>

            <tr>
                <td>
                    <button name="add" value="add" formaction="/sphy140/project%20php/teacher/add.php">Add a new
                        Question
                    </button>
                </td>

            </tr>

        </table>

    </form>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script src="EditDelViewCourses.js"></script>

    </body>
    </html>

    <?php
} else {
    echo '<h4>401, Unauthorized</h4>';
}
?>

