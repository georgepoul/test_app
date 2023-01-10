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
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

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

        $_SESSION['qId'] = $row[0]['id'];


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
        echo 'Something bad happened';
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

        if (isset($_POST['answer'])) {

            $answers = $_POST['answer'];

            $updRA = $conn->prepare("delete from php_db.Answer where Question_ID = :id");

            $updRA->bindParam(':id', $row[0]['id']);

            $updRA->execute();

            $AnswerId = $updRA->fetchAll(PDO::FETCH_ASSOC);


            for ($i = 0; $i < sizeof($answers); $i++) {


                    $updRA = $conn->prepare("insert into php_db.Answer (Answer,Question_ID) values(:answer, :id) ");

                    $updRA->bindParam(':id', $row[0]['id']);
                    $updRA->bindParam(':answer', $answers[$i]);

                    $updRA->execute();

            }

            header("Location: edit2.php");

        }
    } catch (PDOException $e) {
        echo 'Something bad happened';
    }
    }

    ?>

    <label for="question">Question:</label>
    <input style="color: white" type="text" name="question" placeholder="Wright your Question here" id="question"
           required
           value="<?php echo $rowQuestion[0]['Question']; ?>">

    <label for="difficulty">Difficulty</label>
    <select style="color: white" type="text" name="difficulty" id="difficulty" required>
        <option style="color: white" value="" disabled selected hidden>Please choose the Questions difficulty</option>
        <option style="color: white"
                value="1" <?php if (isset($rowQuestion[0]['Difficulty']) and !strcmp($rowQuestion[0]['Difficulty'], "easy")) echo 'selected'; ?>>
            easy
        </option>
        <option style="color: white"
                value="2" <?php if (isset($rowQuestion[0]['Difficulty']) and !strcmp($rowQuestion[0]['Difficulty'], "medium")) echo 'selected'; ?>>
            medium
        </option>
        <option style="color: white"
                value="3" <?php if (isset($rowQuestion[0]['Difficulty']) and !strcmp($rowQuestion[0]['Difficulty'], "hard")) echo 'selected'; ?>>
            hard
        </option>
    </select>


    <div class="table-responsive">
        <table style="width: 1125px" class="table table-bordered" id="dynamic_field">
            <?php
            echo '<label for="answer">Answers</label>';
            for ($q = 1; $q <= $stm1->rowCount(); $q++) {
                echo '<tr id="row', $q, '">';

                echo '<td hidden="hidden">', $q, '</td><td ><input style = "color: white" type = "text" name="answer[]', $q, '" placeholder = "Add your new question"
                       value="', $rowAnswers[$q - 1]['Answer'], '"></td >';

                if (!isset($q) or $q == 1) {
                    echo '<td style="align-content: center; vertical-align: middle"><button style="vertical-align: middle" type="button" name="add" id="add" class="btn btn-success">Add More</button></td>';
                } else {

                    echo '<td style="align-content: center; vertical-align: middle"><button style="vertical-align: middle" type="button" name="remove" id="', $q, '"', ' class="btn btn-danger btn_remove">X</button></td>';
                }
                echo '</tr>';
            }
            ?>

        </table>

        <input style="vertical-align: middle" type="submit" name="submit" id="submit" class="btn btn-info"
               value="Next"/>
    </div>

    </form>
    </body>
    </html>


    <script>
        $(document).ready(function () {
            var i = <?php echo $q - 1 ?>;
            $('#add').click(function () {
                i++;
                $('#dynamic_field').append('<tr style="align-content: center" id="row' + i + '"><td hidden="hidden">' + i + '</td><td style="align-content: center"><input type="text" name="answer[]" placeholder="Add your new question" class="form-control name_list" required/></td><td style="vertical-align: middle"><button style="vertical-align: middle" type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button></td></tr>');

            });

            $(document).on('click', '.btn_remove', function () {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });
    </script>

    <?php
} else {

    echo '<h4>401, Unauthorized</h4>';
}
?>
