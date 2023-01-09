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

    ?>


    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="post" action="">


        <p style="text-align: left">The Questions for the course <?php echo $_SESSION['lesson'] ?> are:</p>

        <?php
        try {
            $log = $conn->prepare("select Question from php_db.Question q inner join php_db.Question_Lesson ql on q.Question_ID = ql.Question_ID
inner join php_db.Lesson l on ql.Lesson_ID = l.Lesson_ID where l.Lesson_ID = (select Lesson_ID from php_db.Lesson where Lesson.Lesson_Name = :lesson)");

            $log->bindParam(':lesson', $_SESSION['lesson']);
            $log->execute();

            $row = $log->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['row']= $row;


        } catch (PDOException $e) {

            echo 'Something bad happened';
        }


        if ($log->rowCount() == 0) {
            echo '<h3>No questions on this course</h3>';
        } else {

        ?>

        <table>
            <tr class="nr">
                <th>Question</th>
                <th></th>
                <th></th>
            </tr>

            <?php

            for ($i = 0; $i < $log->rowCount(); $i++) {
                ?>
                <tr onclick="<?php echo 'myFunction(this)'; $_SESSION['id'] = $_COOKIE['id'] - 1;
                 ?>">
                    <td>
                        <?php echo $row[$i]['Question'] ?>
                    </td>
                    <td>
                        <input type="submit" name="edit" value="edit" />

                    </td>
                    <td>
                        <input type="submit" name="delete" value="delete" formaction="/sphy140/project%20php/teacher/delete.php"/>
                    </td>
                </tr>
                <?php
            }
            }
            ?>

            <tr>
                <td>
                    <button name="add" value="add" formaction="/sphy140/project%20php/teacher/add.php">Add a new Question</button>
                </td>

            </tr>

        </table>
        <script src="myFunction.js" >

        </script>
        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_COOKIE['id'] = null;

            ?>

            <?php
            if (isset($_POST['edit'])) {
                echo $_SESSION['id'];


                header("Location: edit.php");

            }
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

