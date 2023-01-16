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

    for ($i = 5; $i <= 8; $i++) {

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


    if (isset($_POST['answer5']) and isset($_POST['answer6']) and isset($_POST['answer7']) and isset($_POST['answer8'])) {
        if (isset($_POST['Next'])) {

            header("Location: page3.php");

        }else if (isset($_POST['Pre'])){
            header("Location: page1.php");

        }
    } else {

        echo '<h4 style="color: #ff512f"> You mast answer all the questions</h4>';
    }
}

?>
<h2 style="color: deepskyblue; "><?php echo $_SESSION['lesson'] ?></h2>

<form method="post">
    <h3 style="color: white"><?php echo '5)', ' ', $_SESSION['questions'][4]['Question'] ?></h3>
    <div class="answers">
        <?php
        $cou = 0;
        $checked = null;
        for ($k = 0; $k < count($_SESSION['Answers']); $k++) {

            if ($_SESSION['Answers'][$k]['Question_ID'] == $_SESSION['questions'][4]['Question_ID']) {

                if (isset($_SESSION['input']['answer5']) and in_array($_SESSION['Answers'][$k]['Answer'],$_SESSION['input']['answer5'])) {
                    $checked = 'checked';
                }

                echo $_SESSION['ab'][$cou], ') ', '<input type="checkbox" name="answer5[]" value="', $_SESSION['Answers'][$k]['Answer'], '" ', $checked, '>', '      ', $_SESSION['Answers'][$k]['Answer'], '</input><br>';
                $cou++;
            }
            $checked = null;
        }

        ?>
    </div>

    <h3 style="color: white"><?php echo '6)', ' ', $_SESSION['questions'][5]['Question'] ?></h3>
    <div class="answers">
        <?php

        $cou = 0;

        for ($k = 0; $k < count($_SESSION['Answers']); $k++) {

            if ($_SESSION['Answers'][$k]['Question_ID'] == $_SESSION['questions'][5]['Question_ID']) {

                if (isset($_SESSION['input']['answer6']) and in_array($_SESSION['Answers'][$k]['Answer'],$_SESSION['input']['answer6'])) {
                    $checked = 'checked';
                }

                echo $_SESSION['ab'][$cou], ') ', '<input type="checkbox" name="answer6[]" value="', $_SESSION['Answers'][$k]['Answer'], '" ', $checked, '>', '      ', $_SESSION['Answers'][$k]['Answer'], '</input><br>';
                $cou++;
            }
            $checked = null;

        }
        ?>
    </div>

    <h3 style="color: white"><?php echo '7)', ' ', $_SESSION['questions'][6]['Question'] ?></h3>
    <div class="answers">
        <?php

        $cou = 0;

        for ($k = 0; $k < count($_SESSION['Answers']); $k++) {

            if ($_SESSION['Answers'][$k]['Question_ID'] == $_SESSION['questions'][6]['Question_ID']) {

                if (isset($_SESSION['input']['answer7']) and in_array($_SESSION['Answers'][$k]['Answer'],$_SESSION['input']['answer7'])) {
                    $checked = 'checked';
                }

                echo $_SESSION['ab'][$cou], ') ', '<input type="checkbox" name="answer7[]" value="', $_SESSION['Answers'][$k]['Answer'], '" ', $checked, '>', '      ', $_SESSION['Answers'][$k]['Answer'], '</input><br>';
                $cou++;
            }
            $checked = null;

        }
        ?>
    </div>

    <h3 style="color: white"><?php echo '8)', ' ', $_SESSION['questions'][7]['Question'] ?></h3>
    <div class="answers">
        <?php

        $cou = 0;

        for ($k = 0; $k < count($_SESSION['Answers']); $k++) {

            if ($_SESSION['Answers'][$k]['Question_ID'] == $_SESSION['questions'][7]['Question_ID']) {

                if (isset($_SESSION['input']['answer8']) and in_array($_SESSION['Answers'][$k]['Answer'],$_SESSION['input']['answer8'])) {
                    $checked = 'checked';
                }

                echo $_SESSION['ab'][$cou], ') ', '<input type="checkbox" name="answer8[]" value="', $_SESSION['Answers'][$k]['Answer'], '" ', $checked, '>', '      ', $_SESSION['Answers'][$k]['Answer'], '</input><br>';
                $cou++;
            }
            $checked = null;

        }
        ?>
    </div>


    <button type="submit" name="Pre" style="margin-top: 100px;">&#8592; Pre</button>
    <button type="submit" name="Next" style="margin-left: 900px; margin-top: 100px">Next &#8594;</button>
</form>
<?php


} else {
    echo '<h4>401, Unauthorized</h4>';
}

?>

