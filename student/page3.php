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

    for ($i = 9; $i <= 12; $i++) {

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


    if (isset($_POST['answer9']) and isset($_POST['answer10']) and isset($_POST['answer11']) and isset($_POST['answer12'])) {
        if (isset($_POST['Submit'])) {

            header("Location: results.php");

        }elseif (isset($_POST['Pre'])){
            header("Location: page2.php");

        }
    } else {

        echo '<h4 style="color: #ff512f"> You mast answer all the questions</h4>';
    }
}

?>
<h2 style="color: deepskyblue; "><?php echo $_SESSION['lesson'] ?></h2>

<form method="post">
    <h3 style="color: white"><?php echo '9)', ' ', $_SESSION['questions'][8]['Question'] ?></h3>
    <div class="answers">
        <?php
        $cou = 0;
        $checked = null;
        for ($k = 0; $k < count($_SESSION['Answers']); $k++) {

            if ($_SESSION['Answers'][$k]['Question_ID'] == $_SESSION['questions'][8]['Question_ID']) {

                if (isset($_SESSION['input']['answer9']) and in_array($_SESSION['Answers'][$k]['Answer'],$_SESSION['input']['answer9'])) {
                    $checked = 'checked';
                }

                echo $_SESSION['ab'][$cou], ') ', '<input type="checkbox" name="answer9[]" value="', $_SESSION['Answers'][$k]['Answer'], '" ', $checked, '>', '      ', $_SESSION['Answers'][$k]['Answer'], '</input><br>';
                $cou++;
            }
            $checked = null;
        }

        ?>
    </div>

    <h3 style="color: white"><?php echo '10)', ' ', $_SESSION['questions'][9]['Question'] ?></h3>
    <div class="answers">
        <?php

        $cou = 0;

        for ($k = 0; $k < count($_SESSION['Answers']); $k++) {

            if ($_SESSION['Answers'][$k]['Question_ID'] == $_SESSION['questions'][9]['Question_ID']) {

                if (isset($_SESSION['input']['answer10']) and in_array($_SESSION['Answers'][$k]['Answer'],$_SESSION['input']['answer10'])) {
                    $checked = 'checked';
                }

                echo $_SESSION['ab'][$cou], ') ', '<input type="checkbox" name="answer10[]" value="', $_SESSION['Answers'][$k]['Answer'], '" ', $checked, '>', '      ', $_SESSION['Answers'][$k]['Answer'], '</input><br>';
                $cou++;
            }
            $checked = null;

        }
        ?>
    </div>

    <h3 style="color: white"><?php echo '11)', ' ', $_SESSION['questions'][10]['Question'] ?></h3>
    <div class="answers">
        <?php

        $cou = 0;

        for ($k = 0; $k < count($_SESSION['Answers']); $k++) {

            if ($_SESSION['Answers'][$k]['Question_ID'] == $_SESSION['questions'][10]['Question_ID']) {

                if (isset($_SESSION['input']['answer11']) and in_array($_SESSION['Answers'][$k]['Answer'],$_SESSION['input']['answer11'])) {
                    $checked = 'checked';
                }

                echo $_SESSION['ab'][$cou], ') ', '<input type="checkbox" name="answer11[]" value="', $_SESSION['Answers'][$k]['Answer'], '" ', $checked, '>', '      ', $_SESSION['Answers'][$k]['Answer'], '</input><br>';
                $cou++;
            }
            $checked = null;

        }
        ?>
    </div>

    <h3 style="color: white"><?php echo '12)', ' ', $_SESSION['questions'][11]['Question'] ?></h3>
    <div class="answers">
        <?php

        $cou = 0;

        for ($k = 0; $k < count($_SESSION['Answers']); $k++) {

            if ($_SESSION['Answers'][$k]['Question_ID'] == $_SESSION['questions'][11]['Question_ID']) {

                if (isset($_SESSION['input']['answer12']) and in_array($_SESSION['Answers'][$k]['Answer'],$_SESSION['input']['answer12'])) {
                    $checked = 'checked';
                }

                echo $_SESSION['ab'][$cou], ') ', '<input type="checkbox" name="answer12[]" value="', $_SESSION['Answers'][$k]['Answer'], '" ', $checked, '>', '      ', $_SESSION['Answers'][$k]['Answer'], '</input><br>';
                $cou++;
            }
            $checked = null;

        }
        ?>
    </div>


    <button type="submit" name="Pre" style="margin-top: 100px;">&#8592; Pre</button>
    <button type="submit" name="Submit" style="margin-left: 900px; margin-top: 100px">Submit</button>
</form>
<?php


} else {
    echo '<h4>401, Unauthorized</h4>';
}

?>

