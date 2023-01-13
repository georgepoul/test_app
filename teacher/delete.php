<?php
session_start();
include('../database/db_connection.php');


try {
    $rowId = $_GET['id'] - 1;

    $idConf = $conn->prepare("select Question.Question_ID as id from php_db.Question  where php_db.Question.Question = :question");

    $idConf->bindParam(':question', $_SESSION['row'][$rowId]['Question']);
    $idConf->execute();

    $row = $idConf->fetchAll(PDO::FETCH_ASSOC);


    $idC = $conn->prepare("select Lesson_ID as id from php_db.Lesson  where Lesson_Name = :name ");

    $idC->bindParam(':name', $_SESSION['lesson']);
    $idC->execute();

    $rowLe = $idC->fetchAll(PDO::FETCH_ASSOC);

    $_SESSION['id'] = null;

    $del = $conn->prepare("delete from  php_db.Question_Lesson
        where Question_Lesson.Question_ID = :id and Question_Lesson.Lesson_ID = :lid");

    $del->bindParam(':id', $row[0]['id']);
    $del->bindParam(':lid', $rowLe[0]['id']);

    $del->execute();


    $del1 = $conn->prepare("delete from php_db.Right_Answer where Question_ID not in (select Question_Lesson.Question_ID from php_db.Question_Lesson)");

    $del1->execute();



    $del = $conn->prepare("delete Answer from  php_db.Answer
        where Answer.Question_ID not in (select Question_Lesson.Question_ID from php_db.Question_Lesson) ");

    $del->execute();




    $del = $conn->prepare("delete from php_db.Question where Question.Question_ID not in (select Question_Lesson.Question_ID from php_db.Question_Lesson)");

    $del->execute();


    $conn = null;

    header("Location: questions.php");

} catch (PDOException $e) {

    echo $e->getMessage();
}

