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

    <?php


    if (isset($_POST['answer1'])) {
        ?>
        <form>
        <?php
        try {

            include('../database/db_connection.php');



            $RAns = $conn->prepare("insert into php_db.Right_Answer (Right_Answer) values (:Right)");

            $var =  'answer' . (string)$_POST['right_answer'];

            $RAns->bindParam(':Right', $_POST[$var]);
            $RAns->execute();


            $Que = $conn->prepare("insert into php_db.Question (Question, Right_Answer_ID, Difficulty_ID)
                values (:question, (select Right_Answer.Right_Answer_ID from php_db.Right_Answer where Right_Answer = :Right), :diff)");
            $Que->bindParam(':question', $_POST['question']);
            $Que->bindParam(':Right', $_POST['right_answer']);
            $Que->bindParam(':diff', $_POST['difficulty']);

            $Que->execute();



            for ($i = 1; $i <= $_SESSION['nque']; $i++) {
                $ans = $conn->prepare("insert into php_db.Answer (Answer, Question_ID)
                values (:answer, (select Question_ID from php_db.Question where Question = :que))");

                $value = 'answer' . (string)$i;

                $ans->bindParam(':answer', $_POST[$value]);
                $ans->bindParam(':que', $_POST['question']);

                $ans->execute();
            }

            $queAns = $conn->prepare("insert into php_db.Question_Lesson (Lesson_ID, Question_ID)
                values ((select Lesson.Lesson_ID from php_db.Lesson where Lesson.Lesson_Name = :lname), (select Question_ID from php_db.Question where Question = :que))");

            $queAns->bindParam(':lname', $_SESSION['lesson']);
            $queAns->bindParam(':que', $_POST['question']);


            $queAns->execute();



            echo '<h3>Your Answer saved Successfully!</h3>';
            ?>
            <button><a href="add.php" style="color: white">Add a new question</a></button>
            <button><a href="questions.php" style="color: white">See all the questions for the
                    course: <?php echo $_SESSION['lesson'] ?></a></button>
            </form>
            <?php

            unset($_SESSION['question']);
            unset($_SESSION['nque']);
            exit();

        } catch (PDOException $e) {

            echo $e->getMessage();
            echo 'Something bad happened';
        }

    }
    ?>
    <form method="post">

        <h3>New Question for course: <?php echo $_SESSION['lesson']; ?></h3>

        <label for="question">Question:</label>
        <input type="text" name="question" placeholder="Wright your Question here" id="question" required
            <?php if (isset($_POST['question'])) echo 'value="', $_POST['question'], '"'; ?>>

        <label for="difficulty">Difficulty</label>
        <select type="text" name="difficulty" id="difficulty" required>
            <option value="" disabled selected hidden>Please choose the Questions difficulty</option>
            <option value="1" <?php if (isset($_POST['difficulty']) and !strcmp($_POST['difficulty'], "1")) echo 'selected'; ?>>
                easy
            </option>
            <option value="2" <?php if (isset($_POST['difficulty']) and !strcmp($_POST['difficulty'], "2")) echo 'selected'; ?>>
                medium
            </option>
            <option value="3" <?php if (isset($_POST['difficulty']) and !strcmp($_POST['difficulty'], "3")) echo 'selected'; ?>>
                hard
            </option>
        </select>

        <label for="nm">Number of Answers:</label>
        <select type="text" id="nm" name="nm">>
            <option value="" disabled selected hidden>Please choose how many answers do you wont to add in your
                question
            </option>
            <?php
            for ($i = 1; $i <= 10; $i++) {
                echo '<option value="', $i, '">', $i, ' </option>';
            }
            ?>
        </select>
        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['nm'])) {
                $_SESSION['question'] = $_POST['question'];
                $_SESSION['nque'] = $_POST['nm'];

                for ($i = 1; $i <= $_POST['nm']; $i++) {

                    echo '<label for="answer">Answer ', $i, '</label>';
                    echo '<input type="text" name="answer', $i, '" placeholder="Wright the answer here" id="answer" required>';
                }
                ?>
                <label for="right_answer">Right Answer is the Answer :</label>
                <select type="text" name="right_answer" id="right_answer" required>
                    <option value="" disabled selected hidden>Please choose which of the added Answer is Right
                    </option>
                    <?php

                    for ($i = 1; $i <= $_SESSION['nque']; $i++) {

                        echo '<option value="', $i, '">', $i, ' </option>';

                    }
                    ?>
                </select>

                <?php

            }

        }
        ?>

        <button>Next</button>
    </form>
    </body>
    </html>

    <?php
} else {
    echo '<h4>401, Unauthorized</h4>';
}
?>

