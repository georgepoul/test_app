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


    $Answers = $conn->prepare("select Answer, Answer_ID, Question  from php_db.Answer inner join php_db.Question
                                    on Answer.Question_ID = Question.Question_ID  where php_db.Answer.Question_ID = :id");

    $Answers->bindParam(':id', $_SESSION['qId']);
    $Answers->execute();

    $row = $Answers->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {

    echo 'Something bad happen';
}

?>

<form method="post">

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['right_answer'])){

    try {

        $Que = $conn->prepare("delete from php_db.Question_Lesson where Question_ID= :Qid ");

        $Que->bindParam(':Qid', $_SESSION['qId']);

        $Que->execute();

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

            $Que->bindParam(':QID', $_SESSION['qId']);
            $Que->bindParam(':LID', $AddedLe[$i]);

            $Que->execute();
        }

    $idConf = $conn->prepare("delete from php_db.Right_Answer  where Question_ID = :queid ");

    $idConf->bindParam(':queid', $_SESSION['qId']);

    $idConf->execute();


    $RAnswer = $_POST['right_answer'];

    for ($i=0;$i < count($RAnswer);$i++) {
        $idConf = $conn->prepare("insert into php_db.Right_Answer(Right_Answer, Question_ID) values (:answer , :id)");

        $idConf->bindParam(':answer', $RAnswer[$i]);
        $idConf->bindParam(':id', $_SESSION['qId']);

        $idConf->execute();

    }


    echo '<h3>Your Question edited Successfully!</h3>';

    echo '<h3 style="text-align: center"> Your answer is:</h3>';

        $idConf = $conn->prepare("select Question.Question_ID, Question, Difficulty_ID, Answer 
            from php_db.Question inner join php_db.Answer on Answer.Question_ID = Question.Question_ID 
            where php_db.Question.Question_ID = :id");

        $idConf->bindParam(':id', $_SESSION['qId']);
        $idConf->execute();

        $row3 = $idConf->fetchAll(PDO::FETCH_ASSOC);

        $idConf2 = $conn->prepare("select Right_Answer 
            from php_db.Right_Answer
            where php_db.Right_Answer.Question_ID = :id");

        $idConf2->bindParam(':id', $row3[0]['Question_ID']);
        $idConf2->execute();

        $row4 = $idConf2->fetchAll(PDO::FETCH_ASSOC);

        $ans = null;

        for ($i=0;$i<$idConf2->rowCount();$i++){
            $ans[$i]= $row4[$i]['Right_Answer'];
        }
        if (!strcmp($row3[0]['Difficulty_ID'], '1')) {
            $dif = 'easy';
            $col = 'style="color: green"';

        }

        if (!strcmp($row3[0]['Difficulty_ID'], '2')) {
            $dif = 'medium';
            $col = 'style="color: orange"';

        }
        if (!strcmp($row3[0]['Difficulty_ID'], '3')) {
            $dif = 'hard';
            $col = 'style="color: red"';

        }
    ?>


    <p style="text-align: left;color: #74cbe8">Question: <?php echo $row3[0]['Question'] ?> </p>


    <ul>

        <li style="color: #74cbe8;list-style-type: none">Answers:</li>
        <?php
        for ($i = 0; $i < $idConf->rowCount(); $i++) {
            $green = null;
            if (in_array($row3[$i]['Answer'], $ans)) {
                $green = 'style="color: green"';
            }

            echo '<li ', $green, ' >', $row3[$i]['Answer'], '</li>';
        }
        ?>
        <br>
        <li style="color: #74cbe8;list-style-type: none">Difficulty:</li>
        <li <?php echo $col ?>><?php echo $dif ?></li>

        <li style="color: #74cbe8;list-style-type: none">Course/s</li>
        <?php
        foreach ($_POST['Lesson'] as $less){
            echo '<li>', $less, '</li>';
        }
        ?>
    </ul>

        <?php
    echo '<button formaction="http://localhost:8080/sphy140/project%20php/teacher/questions.php"><a style="color: white">See all the questions for the
            course:',$_SESSION['lesson'],'</a></button>';

    exit();

    }catch (PDOException $e){
        echo $e->getMessage();
    echo 'Something bad happened';
    }
    }

    }

    ?>

    <h3>Edit Question</h3>


    <label style="color: white" type="text" id="question"> Question :
        <?php echo $row[0]['Question'];?>
    </label>
    <label for="right_answer">Right Question is the Answer :</label>
    <p style="color: #ff512f">For multiple choice please push ctrl + click or cmd+click</p>

    <select style="color: white" type="text" name="right_answer[]" id="right_answer" required multiple>
        <?php

        for ($j = 0; $j < $Answers->rowCount(); $j++) {

            echo '<option style="color: white; vert" value="', $row[$j]['Answer'], '">', $row[$j]['Answer'], '</option>';
        }
        ?>
    </select>

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

    <input style="vertical-align: middle" type="submit"
           name="submit" id="submit" class="btn btn-info"
           value="Next"/>

</form>
</body>
<?php
} else {

    echo '<h4>401, Unauthorized</h4>';
}
?>
