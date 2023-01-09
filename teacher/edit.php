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
        $idConf = $conn->prepare("select Question.Question_ID as id from php_db.Question  where php_db.Question.Question = :question");

        $idConf->bindParam(':question', $_SESSION['row'][$_SESSION['id']]['Question']);
        $idConf->execute();


        $row = $idConf->fetchAll(PDO::FETCH_ASSOC);


        $stm1 = $conn->prepare("select Answer  from php_db.Answer where Question_ID = :id");
        $stm1->bindParam(':id', $row[0]['id']);
        $stm1->execute();

        $rowAnswers = $stm1->fetchAll(PDO::FETCH_ASSOC);


        $stm = $conn->prepare("select Question, Right_Answer, Difficulty  from php_db.Question 
            inner join php_db.Dificulty on Question.Difficulty_ID = Dificulty.Difficulty_Id
            inner join php_db.Right_Answer on Question.Right_Answer_ID = Right_Answer.Right_Answer_ID
            where Question_ID = :id");

        $stm->bindParam(':id', $row[0]['id']);
        $stm->execute();

        $rowQuestion = $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Something bad happen';
    }
    ?>

    <form method="post">


        <h3>Edit Question</h3>

        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        try {
        if (isset($_POST['question'])) {
            $updQue = $conn->prepare("update php_db.Question 
             set Question = :question, Difficulty_ID = :difficulty where Question_ID = :id");

            $updQue->bindParam(':question', $_POST['question']);
            $updQue->bindParam(':difficulty', $_POST['difficulty']);
            $updQue->bindParam(':id', $row[0]['id']);

            $updQue->execute();

        }

        $var = 'answer' . $_POST['right_answer'];

        if (isset($_POST[$var])) {
            $updRA = $conn->prepare("update php_db.Right_Answer inner join php_db.Question on Question.Right_Answer_ID = Right_Answer.Right_Answer_ID
             set Right_Answer = :rightAn where Question_ID = :id");

            $updRA->bindParam(':rightAn', $_POST[$var]);
            $updRA->bindParam(':id', $row[0]['id']);

            $updRA->execute();

        }

        for ($w = 1; $w <= $stm1->rowCount(); $w++) {

            $var = 'answer' . $w;

            if (isset($_POST[$var])) {

                $updRA = $conn->prepare("update php_db.Answer set Answer = :answer where Question_ID = :id and Answer = :old");

                $updRA->bindParam(':answer', $_POST[$var]);
                $updRA->bindParam(':id', $row[0]['id']);
                $updRA->bindParam(':old', $rowAnswers[$w - 1]['Answer']);

                $updRA->execute();
            }
        }

        echo '<h3>Your Question edited Successfully!</h3>';
        ?>
        <button><a href="questions.php" style="color: white">See all the questions for the
                course: <?php echo $_SESSION['lesson'] ?></a></button>
    </form>
    <?php

    exit();

    } catch (PDOException $e) {
        echo 'Something bad happened';
    }
    }

    ?>

    <label for="question">Question:</label>
    <input type="text" name="question" placeholder="Wright your Question here" id="question" required
           value="<?php echo $rowQuestion[0]['Question']; ?>">

    <label for="difficulty">Difficulty</label>
    <select type="text" name="difficulty" id="difficulty" required>
        <option value="" disabled selected hidden>Please choose the Questions difficulty</option>
        <option value="1" <?php if (isset($rowQuestion[0]['Difficulty']) and !strcmp($rowQuestion[0]['Difficulty'], "easy")) echo 'selected'; ?>>
            easy
        </option>
        <option value="2" <?php if (isset($rowQuestion[0]['Difficulty']) and !strcmp($rowQuestion[0]['Difficulty'], "medium")) echo 'selected'; ?>>
            medium
        </option>
        <option value="3" <?php if (isset($rowQuestion[0]['Difficulty']) and !strcmp($rowQuestion[0]['Difficulty'], "hard")) echo 'selected'; ?>>
            hard
        </option>
    </select>

    <?php
    for ($q = 1; $q <= $stm1->rowCount(); $q++) {

        echo '<label for="answer">Answer ', $q, '</label>';
        echo '<input type="text" required name="answer', $q, '" value="', $rowAnswers[$q - 1]['Answer'], '">';


    }


    ?>

    <label for="right_answer">Right Answer is the Answer :</label>
    <select type="text" name="right_answer" id="right_answer" required>
        <?php

        for ($j = 1; $j <= $stm1->rowCount(); $j++) {
            $selected = "";
            if (!strcmp($rowAnswers[$j - 1]['Answer'], $rowQuestion[0]['Right_Answer'])) {
                $selected = 'selected';
            }

            echo '<option value="', $j, '" ', $selected, '>', $j, ' </option>';
        }
        ?>
    </select>
    <button type="submit">Submit</button>
    </form>
    </body>
    </html>

    <?php
} else {

    echo '<h4>401, Unauthorized</h4>';
}
?>
