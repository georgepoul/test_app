<?php
session_start();
include('../database/db_connection.php');


try {
    $rowId = $_GET['id'] - 1;


    $Que = $conn->prepare("select Lesson_ID from php_db.Lesson where Lesson_Name = :name");

    $Que->bindParam(':name', $_SESSION['lessons'][$rowId]['Lesson_Name']);

    $Que->execute();

    $LessonsID = $Que->fetchAll(PDO::FETCH_ASSOC);



    $del1 = $conn->prepare("delete  
        from php_db.Question_Lesson
        where Lesson_ID = :id");

    $del1->bindParam(':id', $LessonsID[0]['Lesson_ID']);

    $del1->execute();

    $del2 = $conn->prepare("delete  
        from php_db.Lesson
        where php_db.Lesson.Lesson_ID = :id");

    $del2->bindParam(':id', $LessonsID[0]['Lesson_ID']);

    $del2->execute();



    $del3 = $conn->prepare("delete php_db.Right_Answer, php_db.Answer 
        from php_db.Right_Answer inner join php_db.Answer on Answer.Question_ID = Right_Answer.Question_ID  
        where Right_Answer.Question_ID not in (select Question_Lesson.Question_ID from php_db.Question_Lesson)");

    $del3->execute();

    $del4 = $conn->prepare("delete php_db.Question from php_db.Question
        where Question_ID not in (select Question_Lesson.Question_ID from php_db.Question_Lesson)");

    $del4->execute();


    header("Location: editCourses.php");

} catch (PDOException $e) {
    echo $e->getMessage();
}

