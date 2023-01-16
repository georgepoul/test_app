<?php
session_start();
if ($_SESSION['role'] == 'Student') {
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    for ($i = 1; $i <= 4; $i++) {

        $var = 'answer' . $i;
        if (isset($_POST[$var])) {

            $_SESSION['input'][$var]=null;

            for ($k = 0; $k < count($_POST[$var]); $k++) {

                $_SESSION['input'][$var][$k] = $_POST[$var][$k];
            }
        } else {
            $_SESSION['input'][$var] = null;
        }
    }


    if (isset($_POST['answer1']) and isset($_POST['answer2']) and isset($_POST['answer3']) and isset($_POST['answer4'])) {
        if (isset($_POST['Next'])) {

            header("Location: page2.php");

        }
    } else {

        echo '<h4 style="color: #ff512f"> You mast answer all the questions</h4>';
    }
}

?>
<h2 style="color: deepskyblue; "><?php echo $_SESSION['lesson'] ?></h2>

<form method="post">
    <h3 style="color: white"><?php echo '1)', ' ', $_SESSION['questions'][0]['Question'] ?></h3>
    <div class="answers">
        <?php
        $cou = 0;
        $checked = null;
        for ($k = 0; $k < count($_SESSION['Answers']); $k++) {

            if ($_SESSION['Answers'][$k]['Question_ID'] == $_SESSION['questions'][0]['Question_ID']) {

                if (isset($_SESSION['input']['answer1']) and in_array($_SESSION['Answers'][$k]['Answer'],$_SESSION['input']['answer1'])) {
                    $checked = 'checked';
                }

                echo $_SESSION['ab'][$cou], ') ', '<input type="checkbox" name="answer1[]" value="', $_SESSION['Answers'][$k]['Answer'], '" ', $checked, '>', '      ', $_SESSION['Answers'][$k]['Answer'], '</input><br>';
                $cou++;
            }
            $checked = null;
        }

        ?>
    </div>

    <h3 style="color: white"><?php echo '2)', ' ', $_SESSION['questions'][1]['Question'] ?></h3>
    <div class="answers">
        <?php

        $cou = 0;

        for ($k = 0; $k < count($_SESSION['Answers']); $k++) {

            if ($_SESSION['Answers'][$k]['Question_ID'] == $_SESSION['questions'][1]['Question_ID']) {

                if (isset($_SESSION['input']['answer2']) and in_array($_SESSION['Answers'][$k]['Answer'],$_SESSION['input']['answer2'])) {
                    $checked = 'checked';
                }

                echo $_SESSION['ab'][$cou], ') ', '<input type="checkbox" name="answer2[]" value="', $_SESSION['Answers'][$k]['Answer'], '" ', $checked, '>', '      ', $_SESSION['Answers'][$k]['Answer'], '</input><br>';
                $cou++;
            }
            $checked = null;

        }
        ?>
    </div>

    <h3 style="color: white"><?php echo '3)', ' ', $_SESSION['questions'][2]['Question'] ?></h3>
    <div class="answers">
        <?php

        $cou = 0;

        for ($k = 0; $k < count($_SESSION['Answers']); $k++) {

            if ($_SESSION['Answers'][$k]['Question_ID'] == $_SESSION['questions'][2]['Question_ID']) {

                if (isset($_SESSION['input']['answer3']) and in_array($_SESSION['Answers'][$k]['Answer'],$_SESSION['input']['answer3'])) {
                    $checked = 'checked';
                }

                echo $_SESSION['ab'][$cou], ') ', '<input type="checkbox" name="answer3[]" value="', $_SESSION['Answers'][$k]['Answer'], '" ', $checked, '>', '      ', $_SESSION['Answers'][$k]['Answer'], '</input><br>';
                $cou++;
            }
            $checked = null;

        }
        ?>
    </div>

    <h3 style="color: white"><?php echo '4)', ' ', $_SESSION['questions'][3]['Question'] ?></h3>
    <div class="answers">
        <?php

        $cou = 0;

        for ($k = 0; $k < count($_SESSION['Answers']); $k++) {

            if ($_SESSION['Answers'][$k]['Question_ID'] == $_SESSION['questions'][3]['Question_ID']) {

                if (isset($_SESSION['input']['answer4']) and in_array($_SESSION['Answers'][$k]['Answer'],$_SESSION['input']['answer4'])) {
                    $checked = 'checked';
                }

                echo $_SESSION['ab'][$cou], ') ', '<input type="checkbox" name="answer4[]" value="', $_SESSION['Answers'][$k]['Answer'], '" ', $checked, '>', '      ', $_SESSION['Answers'][$k]['Answer'], '</input><br>';
                $cou++;
            }
            $checked = null;

        }
        ?>
    </div>


    <button type="submit" name="Pre" style="margin-top: 100px;" hidden="hidden">&#8592; Pre</button>
    <button type="submit" name="Next" style="margin-left: 1000px; margin-top: 100px">Next &#8594;</button>
</form>
<?php


} else {
    echo '<h4>401, Unauthorized</h4>';
}

?>

