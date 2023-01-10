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
    $idConf = $conn->prepare("select Answer, Answer_ID, Question  from php_db.Answer inner join php_db.Question where php_db.Answer.Question_ID = :id");

    $idConf->bindParam(':id', $_SESSION['qId']);
    $idConf->execute();

    $row = $idConf->fetchAll(PDO::FETCH_ASSOC);



} catch (PDOException $e) {

    echo 'Something bad happen';
}

 ?>

<form method="post">

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['right_answer'])){

            try {

                $idConf = $conn->prepare("update php_db.Right_Answer inner join php_db.Question on 
                Question.Right_Answer_ID = Right_Answer.Right_Answer_ID
                set Right_Answer = :rAn where Question = :que ");

                $idConf->bindParam(':rAn', $_POST['right_answer']);
                $idConf->bindParam(':que', $row[0]['Question']);

                $idConf->execute();



                echo '<h3>Your Question edited Successfully!</h3>','
                <button><a href="questions.php" style="color: white">See all the questions for the
                        course:',$_SESSION['lesson'],'</a></button>';

                exit();

            }catch (PDOException $e){

                echo 'Something bad happened';
            }
        }

    }

    ?>

    <h3>Edit Question</h3>



<label style="color: white" type="text"  id="question"> Question :
    <?php echo $row[0]['Question']; ?>
</label>

    <label for="right_answer">Right Answer is the Answer :</label>
    <select style="color: white" type="text" name="right_answer" id="right_answer" required>
        <?php

        for ($j = 0; $j <= $idConf->rowCount(); $j++) {

            echo '<option style="color: white; vert" value="', $row[$j]['Answer'], '">',$row[$j]['Answer'] ,' </option>';
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
