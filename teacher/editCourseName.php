<?php
session_start();
if ($_SESSION['role'] == 'Teacher') {

    $rowId = $_GET['id'] - 1;
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Test Page</title>
        <link rel="stylesheet" href="../css/add.css">
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
        <?php

        include('../database/db_connection.php');

        try {

            $updRA = $conn->prepare("select Lesson_ID from php_db.Lesson where Lesson_Name = :name");

            $updRA->bindParam(':name', $_SESSION['lessons'][$rowId]['Lesson_Name']);

            $updRA->execute();

            $row = $updRA->fetchAll(PDO::FETCH_ASSOC);



        }catch (PDOException $e){

            echo 'Something bad happened';
        }
        ?>

        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['course'])) {

                try {

                    $name = strip_tags($_POST['course']);

                    $updRA = $conn->prepare("update php_db.Lesson set Lesson_Name = :lesson where Lesson_ID = :id ");

                    $updRA->bindParam(':lesson', $name);
                    $updRA->bindParam(':id', $row[0]['Lesson_ID']);

                    $updRA->execute();



                    ?>
                    <h3>Your Course saved Successfully!</h3>
                    <p>Your Course is : <?php echo $_POST['course'] ?></p>


                    <button formaction="http://localhost:8080/sphy140/project%20php/teacher/editCourses.php"><a style="color: white">See all the Courses.</a></button>

                    <?php

                    exit();

                }catch (PDOException $e){

                    echo 'Something bad happened';
                }
            }

        }
        ?>

        <h3>New Course</h3>


        <label for="question">Course:</label>
        <input type="text" name="course" placeholder="Write your Course here" id="course" required
            <?php if (isset($_SESSION['lessons'][$rowId]['Lesson_Name'])) echo 'value="', $_SESSION['lessons'][$rowId]['Lesson_Name'], '"'; ?>>


        <button>Submit</button>
    </form>
    </body>
    </html>

    <?php
} else {
    echo '<h4>401, Unauthorized</h4>';
}
?>

