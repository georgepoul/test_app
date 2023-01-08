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

try {

    include('../database/db_connection.php');

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
}catch (PDOException $e){
    echo 'Something bad happen';
}
?>

    <form method="post">

        <h3>Edit Question</h3>

        <label for="question">Question:</label>
        <input type="text" name="question" placeholder="Wright your Question here" id="question" required value= "<?php echo $rowQuestion[0]['Question']; ?>">

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
        for ($i = 1; $i <= $stm1->rowCount(); $i++) {

            echo '<label for="answer">Answer ', $i, '</label>';
            echo '<input type="text" name="answer', $i, '" value="', $rowAnswers[$i-1]['Answer'], '">';
        }

        ?>

        <label for="right_answer">Right Answer is the Answer :</label>
        <select type="text" name="right_answer" id="right_answer" required>
            <?php

            $selected = null;
            for ($i = 1; $i <= $stm->rowCount(); $i++) {

                if ($rowAnswers[$i-1]['Answer'] = $rowQuestion[0]['Right_Answer'] ){
                    $selected = 'selected';
                }

                echo '<option value="', $i, '" ',$selected ,'">', $i, ' </option>';
            }
            ?>
        </select>

        <?php
                        //to do save after submit, add or delete a answer, right answer to be the answer of the question and not same other tha is the same
//        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//
//            if (isset($_POST['nm'])) {
//                $_SESSION['question'] = $_POST['question'];
//                $_SESSION['nque'] = $_POST['nm'];
//
//
//                <?
//                php
//
//            }
//
//        }
        ?>

        <button>Submit</button>
    </form>
</body>
</html>

<?php
} else {
    echo '<h4>401, Unauthorized</h4>';
}
?>