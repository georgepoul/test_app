<?php
session_start();
if ($_SESSION['role'] == 'Teacher') {
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

        ?>
        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['course'])) {

                try {

                    $updRA = $conn->prepare("insert into php_db.Lesson(Lesson_Name) value (:lesson)");

                    $updRA->bindParam(':lesson', $_POST['course']);

                    $updRA->execute();



                    ?>
                    <h3>Your Course saved Successfully!</h3>
                    <p>Your Course is : <?php echo $_POST['course'] ?></p>

                    <button><a href="AddCourse.php" style="color: white">Add a new Course</a></button><br>

                    <button><a href="editCourses.php" style="color: white">See all the Courses.</a></button>

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
            <?php if (isset($_POST['course'])) echo 'value="', $_POST['course'], '"'; ?>>


        <button>Submit</button>
    </form>
    </body>
    </html>

    <?php
} else {
    echo '<h4>401, Unauthorized</h4>';
}
?>

