<?php
session_start();
if ($_SESSION['role'] == 'Teacher') {

    $rowId = $_GET['id'] -1;
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Test Page</title>
        <link rel="stylesheet" href="../css/template.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="stylesheet" href="../https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

        <?php
        require('../templates/teacherHeader.inc.php');
        include ('../database/db_connection.php');

        try {
            $idConf = $conn->prepare("select Question.Question_ID, Question, Difficulty_ID, Answer 
            from php_db.Question inner join php_db.Answer on Answer.Question_ID = Question.Question_ID 
            where php_db.Question.Question = :question");

            $idConf->bindParam(':question', $_SESSION['row'][$rowId]['Question']);
            $idConf->execute();

            $row = $idConf->fetchAll(PDO::FETCH_ASSOC);

            $idConf2 = $conn->prepare("select Right_Answer 
            from php_db.Right_Answer
            where php_db.Right_Answer.Question_ID = :id");

            $idConf2->bindParam(':id', $row[0]['Question_ID']);
            $idConf2->execute();

            $row2 = $idConf2->fetchAll(PDO::FETCH_ASSOC);

            $ans = null;

            for ($i=0;$i<$idConf2->rowCount();$i++){
                $ans[$i]= $row2[$i]['Right_Answer'];
            }

            for ($i=1;$i<4;$i ++){
                if (!strcmp($row[0]['Difficulty_ID'], '1')){
                    $dif = 'easy';
                    $col = 'style="color: green"';
                }
                if (!strcmp($row[0]['Difficulty_ID'], '2')){
                    $dif = 'medium';
                    $col = 'style="color: orange"';

                }
                if (!strcmp($row[0]['Difficulty_ID'], '3')){
                    $dif = 'hard';
                    $col = 'style="color: red"';

                }
            }


        }catch (PDOException $e){
            echo 'Something bad happened';
        }


        ?>

    </head>
    <body style="background-color: #080710">
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form>
        <h3 style="text-align: center"> Your Question is:</h3>

        <p style="text-align: left;color: #74cbe8">Question: <?php echo $row[0]['Question']?></p>
        <ul>
            <li style="color: #74cbe8">Answers:</li><br>
            <?php
            for ($i=0;$i< $idConf->rowCount();$i++){
                $green = null;
                if (in_array($row[$i]['Answer'],$ans)){
                    $green = 'style="color: green"';
                }

                echo '<li ',$green,' >',$row[$i]['Answer'],'</li>';
            }
            ?>
            <br>
            <li style="color: #74cbe8">Difficulty:</li>
            <li <?php echo $col ?> > <?php  echo $dif ?></li>
        </ul>
        <?php
        echo '<button><a href="questions.php" style="color: white">See all the questions for the
            course:',$_SESSION['lesson'],'</a></button>';
        ?>

    </form>
    </body>
    </html>

    <?php
} else {
    echo '<h4>401, Unauthorized</h4>';
}
?>

