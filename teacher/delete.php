<?php
session_start();
include('../database/db_connection.php');


try {
    $rowId = $_GET['id'] -1;

    $idConf = $conn->prepare("select Question.Question_ID as id from php_db.Question  where php_db.Question.Question = :question");

    $idConf->bindParam(':question', $_SESSION['row'][$rowId]['Question']);
    $idConf->execute();

    $row = $idConf->fetchAll(PDO::FETCH_ASSOC);

    print_r($row);

        $_SESSION['id'] = null;

    $del1 = $conn->prepare("delete from php_db.Right_Answer where Question_ID = :id");
        $del1->bindParam(':id', $row[0]['id']);

        $del1->execute();



        $del = $conn->prepare("delete  Question_Lesson, Answer from php_db.Question inner join php_db.Question_Lesson
        on Question.Question_ID = Question_Lesson.Question_ID inner join php_db.Answer on Question.Question_ID = Answer.Question_ID where Question.Question_ID = :id");

        $del->bindParam(':id', $row[0]['id']);

        $del->execute();


    $del = $conn->prepare("delete from php_db.Question where Question.Question_ID = :id");

    $del->bindParam(':id', $row[0]['id']);

    $del->execute();


    $conn = null;

        header("Location: questions.php");

} catch (PDOException $e) {
    echo $e->getMessage();
}

