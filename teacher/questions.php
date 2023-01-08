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
    <form method="post">


        <p style="text-align: left">The Questions for the course <?php echo $_SESSION['lesson'] ?> are:</p>

        <?php
        try {
            $log = $conn->prepare("select Question from php_db.Question q inner join php_db.Question_Lesson ql on q.Question_ID = ql.Question_ID
inner join php_db.Lesson l on ql.Lesson_ID = l.Lesson_ID where l.Lesson_ID = (select Lesson_ID from php_db.Lesson where Lesson.Lesson_Name = :lesson)");

            $log->bindParam(':lesson', $_SESSION['lesson']);
            $log->execute();

            $row = $log->fetchAll(PDO::FETCH_ASSOC);


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
                <tr onclick="<?php $_SESSION['id'] = $_COOKIE['id'] - 1;
                echo 'myFunction(this)' ?>">
                    <td>
                        <?php echo $row[$i]['Question'] ?>
                    </td>
                    <td>
                        <input type="submit" name="edit" value="edit"/>

                    </td>
                    <td>
                        <input type="submit" name="delete" value="delete"/>
                    </td>
                </tr>
                <?php
            }
            }
            ?>

            <tr>
                <td>
                    <button name="add" value="add">Add a new Question</button>
                </td>

            </tr>

        </table>

        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_COOKIE['id'] = null;

            ?>
            <script>
                function myFunction(x) {
                    document.cookie = 'id = ' + x.rowIndex;
                    console.log(document.cookie);
                }
            </script>
            <?php

            if (isset($_POST['edit'])) {
                echo $_SESSION['id'];

            } elseif (isset($_POST['delete'])) {
                delete($row);
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

function delete($row)
{

    try {

        include('../database/db_connection.php');

        $idConf = $conn->prepare("select Question.Question_ID as id from php_db.Question  where php_db.Question.Question = :question");

        $idConf->bindParam(':question', $row[$_SESSION['id']]['Question']);
        $idConf->execute();

        $row = $idConf->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['id'] = null;

        $del1 = $conn->prepare("delete from php_db.Right_Answer where Right_Answer_ID = (select Question.Right_Answer_ID from php_db.Question where Question_ID = :id)");
        $del1->bindParam(':id', $row[0]['id']);

        $del1->execute();

        $del = $conn->prepare("delete Question, Question_Lesson, Answer from php_db.Question inner join php_db.Question_Lesson 
        on Question.Question_ID = Question_Lesson.Question_ID inner join php_db.Answer on Question.Right_Answer_ID = Answer_ID where Question.Question_ID = :id");

        $del->bindParam(':id', $row[0]['id']);

        $del->execute();


    } catch (PDOException $e) {
        print_r($e->getMessage());
    }

    header("Location: questions.php");

}

?>

