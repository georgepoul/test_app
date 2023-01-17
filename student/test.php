<?php
session_start();
if ($_SESSION['role'] == 'Student') {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title> <?php echo $_SESSION['lesson'] ?> Test</title>

    <?php
    require('../templates/studentHeader.inc.php');
    ?>

</head>
<body style="background-color: #080710">

<?php

$_SESSION['saved'] = 0;

$_SESSION['DoneTest'] = 0;

list($questions, $Answers, $RAnswers) = TestDB();

$_SESSION['questions'] = $questions;

$_SESSION['Answers'] = $Answers;

$_SESSION['RAnswers'] = $RAnswers;

$_SESSION['ab'] = array(0=>'a',1=>'b',2=>'c',3=>'d',4=>'e',5=>'f',6=>'g',7=>'h',8=>'i',9=>'j');

header("Location: page1.php");


} else {
    echo '<h4>401, Unauthorized</h4>';
}

function TestDB()
{

    include('../database/db_connection.php');

    try {

        $QEasy = $conn->prepare("select Question.Question_ID , Question  from php_db.Question 
        inner join php_db.Question_Lesson on Question.Question_ID = Question_Lesson.Question_ID
        inner join php_db.Lesson on Question_Lesson.Lesson_ID = Lesson.Lesson_ID
        where Lesson_Name = :name and Difficulty_ID = 1
        order by rand()
        limit 4");

        $QEasy->bindParam(':name', $_SESSION['lesson']);

        $QEasy->execute();

        $QrowEasy = $QEasy->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($QrowEasy); $i++) {
            $questions[$i] = $QrowEasy[$i];
        }

        unset($QEasy);
        unset($QrowEasy);

        $QMid = $conn->prepare("select Question.Question_ID , Question  from php_db.Question 
        inner join php_db.Question_Lesson on Question.Question_ID = Question_Lesson.Question_ID
        inner join php_db.Lesson on Question_Lesson.Lesson_ID = Lesson.Lesson_ID
        where Lesson_Name = :name and Difficulty_ID = 2
        order by rand()
        limit 4");

        $QMid->bindParam(':name', $_SESSION['lesson']);

        $QMid->execute();

        $QrowMid = $QMid->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($QrowMid); $i++) {
            $questions[$i + 4] = $QrowMid[$i];
        }

        unset($QMid);
        unset($QrowMid);

        $QHard = $conn->prepare("select Question.Question_ID , Question  from php_db.Question 
        inner join php_db.Question_Lesson on Question.Question_ID = Question_Lesson.Question_ID
        inner join php_db.Lesson on Question_Lesson.Lesson_ID = Lesson.Lesson_ID
        where Lesson_Name = :name and Difficulty_ID = 3
        order by rand()
        limit 4");

        $QHard->bindParam(':name', $_SESSION['lesson']);

        $QHard->execute();

        $QrowHard = $QHard->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($QrowHard); $i++) {
            $questions [$i + 8] = $QrowHard[$i];
        }

        unset($QHard);
        unset($QrowHard);

        $Answers = null;
        for ($i = 0; $i < count($questions); $i++) {

            $Ans = $conn->prepare("select Answer , Question_ID from php_db.Answer where Question_ID = :id order by rand()");

            $Ans->bindParam(':id', $questions[$i]['Question_ID']);

            $Ans->execute();

            $Answer = $Ans->fetchAll(PDO::FETCH_ASSOC);

            if (isset($Answers)) {

                $cnt = count($Answers);

            } else {

                $cnt = 0;
            }

            for ($k = $cnt; $k < (count($Answer) + $cnt); $k++) {

                $Answers[$k] = $Answer[$k - $cnt];
            }
        }


        $RAnswers = null;
        for ($i = 0; $i < count($questions); $i++) {

            $RAns = $conn->prepare("select Right_Answer , Question_ID from php_db.Right_Answer where Question_ID = :id");

            $RAns->bindParam(':id', $questions[$i]['Question_ID']);

            $RAns->execute();

            $RAnswer = $RAns->fetchAll(PDO::FETCH_ASSOC);

            if (isset($RAnswers)) {

                $cnt = count($RAnswers);

            } else {

                $cnt = 0;
            }

            for ($k = $cnt; $k < (count($RAnswer) + $cnt); $k++) {

                $RAnswers[$k] = $RAnswer[$k - $cnt];
            }
        }

        $LId = $conn->prepare("select Lesson_ID from php_db.Lesson where Lesson_Name = :name");

        $LId->bindParam(':name', $_SESSION['lesson']);

        $LId->execute();

        $LesId = $LId->fetchAll(PDO::FETCH_ASSOC);

        unset($LId);
        $_SESSION['LId'] = $LesId[0]['Lesson_ID'];
        unset($LesId);

        return array($questions, $Answers, $RAnswers);

    } catch (PDOException $e) {

        echo 'Something bad happened';
    }


}

?>

