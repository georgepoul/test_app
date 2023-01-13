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

    include('../database/db_connection.php');

    try {

        $Less = $conn->prepare("select Lesson_Name from php_db.Lesson");

        $Less->execute();

        $Lessons = $Less->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($Lessons); $i++) {
            $AllLessons[$i] = $Lessons[$i]['Lesson_Name'];
        }

    } catch (PDOException $e) {
        echo 'Something bad happened';
    }


    if (isset($_POST['answer1'])) {
        ?>
        <form>
        <?php
        try {


            $Que = $conn->prepare("insert into php_db.Question (Question, Difficulty_ID)
                values (:question, :diff)");

            $Que->bindParam(':question', $_POST['question']);
            $Que->bindParam(':diff', $_POST['difficulty']);

            $Que->execute();

            $QueIdStm = $conn->prepare("select Question_ID from php_db.Question where Question = :que");
            $QueIdStm->bindParam(':que', $_POST['question']);

            $QueIdStm->execute();

            $QueId = $QueIdStm->fetchAll(PDO::FETCH_ASSOC);

            $AddedLe = null;

            $selectedL = $_POST['Lesson'];

            for ($i = 0; $i < count($selectedL); $i++) {

                $Que = $conn->prepare("select Lesson_ID from php_db.Lesson where Lesson_Name = :name");

                $Que->bindParam(':name', $selectedL[$i]);

                $Que->execute();

                $LessonsID = $Que->fetchAll(PDO::FETCH_ASSOC);

                $AddedLe[$i] = $LessonsID[0]['Lesson_ID'];

            }

            for ($i = 0; $i < count($AddedLe); $i++) {

                $Que = $conn->prepare("insert into php_db.Question_Lesson (Question_ID, Lesson_ID)
                values (:QID, :LID)");

                $Que->bindParam(':QID', $QueId[0]['Question_ID']);
                $Que->bindParam(':LID', $AddedLe[$i]);

                $Que->execute();
            }


            $Ranswers = $_POST['right_answer'];


            for ($i = 1; $i <= $_SESSION['nque']; $i++) {

                $var = 'answer' . $i;

                $Answers[$i-1] = $_POST[$var];

                if (in_array($i, $Ranswers)) {

                    $RanswersEnd[$i] = $_POST[$var];
                    $RAns = $conn->prepare("insert into php_db.Right_Answer (Right_Answer, Question_ID) values (:Right, :que)");

                    $RAns->bindParam(':Right', $_POST[$var]);
                    $RAns->bindParam(':que', $QueId[0]['Question_ID']);

                    $RAns->execute();
                }

                $queAns = $conn->prepare("insert into php_db.Answer (Answer,Question_ID) values (:answer, :qid)");

                $queAns->bindParam(':answer', $_POST[$var]);
                $queAns->bindParam(':qid', $QueId[0]['Question_ID']);

                $queAns->execute();
            }


            echo '<h3>Your Question saved Successfully!</h3>';

            for ($i = 1; $i < 4; $i++) {
                if (!strcmp($_POST['difficulty'], '1')) {
                    $dif = 'easy';
                    $col = 'style="color: green"';
                }
                if (!strcmp($_POST['difficulty'], '2')) {
                    $dif = 'medium';
                    $col = 'style="color: orange"';

                }
                if (!strcmp($_POST['difficulty'], '3')) {
                    $dif = 'hard';
                    $col = 'style="color: red"';

                }
            }

            ?>

            <h3 style="text-align: center"> Your Question is:</h3>

            <p style="text-align: left;color: #74cbe8">Question: <?php echo $_POST['question'] ?></p>
            <ul>
                <li style="color: #74cbe8">Answers:</li>
                <?php
                for ($i = 0; $i < count($Answers); $i++) {
                    $green = null;

                    if (in_array($Answers[$i], $RanswersEnd)) {
                        $green = 'style="color: green"';
                    }

                    echo '<li ', $green, ' >', $Answers[$i], '</li>';
                }
                ?>
                <br>
                <li style="color: #74cbe8">Difficulty:</li>
                <li <?php echo $col ?> > <?php echo $dif ?></li>
                <br>
                <li style="color: #74cbe8">Course/s</li>
                <?php
                foreach ($_POST['Lesson'] as $less){
                    echo '<li ', $green, ' >', $less, '</li>';
                }
                ?>
            </ul>

            <button formaction="http://localhost/sphy140/project%20php/teacher/add.php"><a style="color: white">Add a new question</a></button><br>

            <button formaction="http://localhost/sphy140/project%20php/teacher/questions.php"><a style="color: white">See all the questions for the
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

        <h3>New Question</h3>


        <label for="question">Question:</label>
        <input type="text" name="question" placeholder="Write your Question here" id="question" required
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

        <label <?php if (isset($_POST['nm'])) echo 'hidden' ?> for="nm">Number of Answers:</label>
        <select <?php if (isset($_POST['nm'])) echo 'hidden' ?> type="text" id="nm" name="nm">>
            <option value="" disabled selected hidden>Please choose how many answers do you wont to add in your
                question
            </option>
            <?php
            for ($i = 2; $i <= 10; $i++) {
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

                    echo '<label for="answer', $i, '">Answer ', $i, '</label>';
                    echo '<input type="text" name="answer', $i, '" placeholder="Write the answer here" id="answer" required>';
                }
                ?>
                <label for="right_answer">Right Answer is the Answer :</label>
                <p style="color: #ff512f">For multiple choice please push ctrl + click or cmd+click</p>

                <select type="text" name="right_answer[]" id="right_answer" required multiple>
                    <option value="" disabled selected hidden>Please choose which of the added Answer is Right
                    </option>
                    <?php

                    for ($i = 1; $i <= $_SESSION['nque']; $i++) {

                        echo '<option value="', $i, '">', $i, ' </option>';

                    }
                    ?>
                </select>
                <label for="Lessons">Courses you wont the question to belong.</label>
                <p style="color: #ff512f">For multiple choice please push ctrl + click or cmd+click</p>
                <select type="text" name="Lesson[]" id="Lesson" required multiple>

                    <?php
                    for ($i = 0; $i < count($Lessons); $i++) {
                        echo '<option value="', $Lessons[$i]['Lesson_Name'], '">', $Lessons[$i]['Lesson_Name'], '</option>';
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

