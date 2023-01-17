<?php
session_start();
if ($_SESSION['role'] == 'Student') {
include('../database/db_connection.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title> <?php echo $_SESSION['lesson'] ?> Test</title>
    <link rel="stylesheet" href="../css/test.css">


    <?php
    require('../templates/studentHeader.inc.php');


    ?>

</head>
<body style="background-color: #080710">

<?php


$_SESSION['DoneTest'] = 1;


$StuResult = 0;

for ($i = 1; $i <= 12; $i++) {

    $result = 1;
    $var = 'answer' . $i;

    if (is_array($_SESSION['RAnswers'][$i - 1]['Right_Answer']) and is_array($_SESSION['input'][$var])) {

        if (count($_SESSION['RAnswers'][$i - 1]['Right_Answer']) == count($_SESSION['input'][$var])) {

            foreach ($_SESSION['input'][$var] as $input) {

                if (!in_array($input, $_SESSION['RAnswers'][$i - 1]['Right_Answer'])) {

                    $result = 0;

                }
            }
        }
    } elseif (is_array($_SESSION['RAnswers'][$i - 1]['Right_Answer']) and count($_SESSION['input'][$var])==1) {

        $result = 0;

    } elseif (!is_array($_SESSION['RAnswers'][$i - 1]['Right_Answer']) and count($_SESSION['input'][$var])>1) {
        echo 'kati';

        $result = 0;

    } elseif (!is_array($_SESSION['RAnswers'][$i - 1]['Right_Answer']) and count($_SESSION['input'][$var])==1 and $_SESSION['RAnswers'][$i - 1]['Right_Answer'] != $_SESSION['input'][$var][0]) {

        $result = 0;
    }

    if ($i >= 1 and $i <= 4) {

        $dif = 1;

    } elseif ($i >= 5 and $i <= 8) {

        $dif = 2;

    } else {

        $dif = 3;
    }

    if ($result == 1) {

        $StuResult++;
    }

    if ($_SESSION['saved'] == 0) {

            $Statistic = $conn->prepare("insert into php_db.Statistics(Lesson_ID, Question_ID, User_ID, Difficulty_ID, timstamp, result)
            values (:lesId, :Qid, :Uid, :Did, now(), :res)");

            $Statistic->bindParam(':lesId', $_SESSION['LId']);
            $Statistic->bindParam(':Qid', $_SESSION['questions'][$i - 1]['Question_ID']);
            $Statistic->bindParam(':Uid', $_SESSION['user_id']);
            $Statistic->bindParam(':Did', $dif);
            $Statistic->bindParam(':res', $result);

            $Statistic->execute();
    }




}
echo $StuResult;


$_SESSION['saved'] = 1;

$_SESSION['DoneTest'] = 1;
unset($_SESSION['questions']);
unset($_SESSION['Answers']);
unset($_SESSION['RAnswers']);
unset($_SESSION['input']);


} else {
    echo '<h4>401, Unauthorized</h4>';
}

?>

