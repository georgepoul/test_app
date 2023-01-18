<!--//$_SESSION['stat'],$_SESSION['date'];-->
<?php
session_start();
if ($_SESSION['role'] == 'Teacher') {
include('../database/db_connection.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title> <?php echo $_SESSION['lesson'] ?> Test</title>
    <link rel="stylesheet" href="../css/question.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>


    <?php
    require('../templates/teacherHeader.inc.php');


    ?>

</head>
<body style="background-color: #080710">

<?php

if ($_SESSION['stat'] == 'difficulty') {

try {

    $A = $conn->prepare("select count(*) as sum from php_db.Statistics where timstamp = :time");

    $A->bindParam(':time', $_SESSION['date']);

    $A->execute();

    $_SESSION['All'] = $A->fetchAll(PDO::FETCH_ASSOC);


    $Eas = $conn->prepare("select count(*) as sum from php_db.Statistics where Difficulty_ID = 1 and timstamp = :time and result=1");

    $Eas->bindParam(':time', $_SESSION['date']);
    $Eas->execute();

    $_SESSION['Easy'] = $Eas->fetchAll(PDO::FETCH_ASSOC);

    $Med = $conn->prepare("select count(*) as sum, count(result) as result  from php_db.Statistics where Difficulty_ID = 2 and timstamp = :time and result=1");

    $Med->bindParam(':time', $_SESSION['date']);
    $Med->execute();

    $_SESSION['Medium'] = $Med->fetchAll(PDO::FETCH_ASSOC);

    $Har = $conn->prepare("select count(*) as sum, count(result) as result  from php_db.Statistics where Difficulty_ID = 3 and timstamp = :time and result=1");

    $Har->bindParam(':time', $_SESSION['date']);
    $Har->execute();

    $EasF = $conn->prepare("select count(*) as sum from php_db.Statistics where Difficulty_ID = 1 and timstamp = :time and result=0");

    $EasF->bindParam(':time', $_SESSION['date']);
    $EasF->execute();

    $_SESSION['EasyF'] = $EasF->fetchAll(PDO::FETCH_ASSOC);

    $MedF = $conn->prepare("select count(*) as sum, count(result) as result  from php_db.Statistics where Difficulty_ID = 2 and timstamp = :time and result=0");

    $MedF->bindParam(':time', $_SESSION['date']);
    $MedF->execute();

    $_SESSION['MediumF'] = $MedF->fetchAll(PDO::FETCH_ASSOC);

    $HarF = $conn->prepare("select count(*) as sum, count(result) as result  from php_db.Statistics where Difficulty_ID = 3 and timstamp = :time and result=0");

    $HarF->bindParam(':time', $_SESSION['date']);
    $HarF->execute();

    $_SESSION['HardF'] = $HarF->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <canvas id="barChart"></canvas>
    <script>
        // Get the context of the canvas element we want to select
        var ctx = document.getElementById("barChart").getContext("2d");

        var data = {
            labels: ["All Questions", "Easy Right", "Easy False", "Medium Right", "Medium False", "Hard right", "HArd False"],
            datasets: [{
                label: "By Difficulty",
                data: [<?php echo $_SESSION['All'][0]['sum']; ?>, <?php echo $_SESSION['Easy'][0]['sum']; ?>, <?php echo $_SESSION['EasyF'][0]['sum']; ?>, <?php echo $_SESSION['Medium'][0]['sum']; ?>, <?php echo $_SESSION['MediumF'][0]['sum']; ?>, <?php echo $_SESSION['Hard'][0]['sum'];?> , <?php echo $_SESSION['HardF'][0]['sum'];  ?>],
                backgroundColor: "#ff6384"
            }]
        };

        var options = {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Instantiate a new chart using the chart.js library
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });
    </script>


    <a href="statistics.php" style="color: white">
        <button style="width: 900px; margin-left: 160px; margin-top: 100px;">See other statistics</button>
    </a>
<?php
exit();
} catch (PDOException $e) {

    echo 'Something bad happened';
}


} elseif ($_SESSION['stat'] == 'course') {

try {

    $Eas = $conn->prepare("select Lesson_Name, Lesson_ID from php_db.Lesson ");

    $Eas->execute();

    $_SESSION['StatLes'] = $Eas->fetchAll(PDO::FETCH_ASSOC);


    for ($i = 0; $i < count($_SESSION['StatLes']); $i++) {

        $Eas = $conn->prepare("select count(*) as sum from php_db.Statistics where result = 1 and Lesson_ID = :id and timstamp = :time");

        $Eas->bindParam(':time', $_SESSION['date']);
        $Eas->bindParam(':id', $_SESSION['StatLes'][$i]['Lesson_ID']);
        $Eas->execute();

        $_SESSION['StatLesRight'][$i] = $Eas->fetchAll(PDO::FETCH_ASSOC);

        $Eas1 = $conn->prepare("select count(*) as sum from php_db.Statistics where result=0 and Lesson_ID = :id and timstamp = :time");

        $Eas1->bindParam(':time', $_SESSION['date']);
        $Eas1->bindParam(':id', $_SESSION['StatLes'][$i]['Lesson_ID']);
        $Eas1->execute();

        $_SESSION['StatLesFalse'][$i] = $Eas1->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {

    echo 'Something bad happened';
}
?>

    <canvas id="barChart"></canvas>
    <script>
        // Get the context of the canvas element we want to select
        var ctx = document.getElementById("barChart").getContext("2d");

        var data = {
            labels: [<?php for ($i = 0; $i < sizeof($_SESSION['StatLes']); $i++) {
                echo '"', $_SESSION['StatLes'][$i]['Lesson_Name'], ' Right", ', '"', $_SESSION['StatLes'][$i]['Lesson_Name'], ' False', '"';
                if ($i != sizeof($_SESSION['StatLes']) - 1) {
                    echo ', ';
                }

            }?>],
            datasets: [{
                label: "By Course",
                data: [<?php for ($i = 0; $i < count($_SESSION['StatLes']); $i++) {
                    echo $_SESSION['StatLesRight'][$i][0]['sum'], ',', $_SESSION['StatLesFalse'][$i][0]['sum'];
                    if ($i != sizeof($_SESSION['StatLes']) - 1) {
                        echo ',';
                    }
                }?>],
                backgroundColor: "#ff6384"
            }]
        };

        var options = {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Instantiate a new chart using the chart.js library
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });
    </script>


    <a href="statistics.php" style="color: white">
        <button style="width: 900px; margin-left: 160px; margin-top: 100px;">See other statistics</button>
    </a>
<?php
exit();


} elseif ($_SESSION['stat'] == 'question') {

$Eas = $conn->prepare("select Question_ID, Question from php_db.Question ");

$Eas->execute();

$_SESSION['AllQue'] = $Eas->fetchAll(PDO::FETCH_ASSOC);


for ($i = 0; $i < count($_SESSION['AllQue']); $i++) {

    $stm = $conn->prepare("select count(*) as sum from php_db.Statistics where Question_ID = :id and result = 1");

    $stm->bindParam(':id', $_SESSION['AllQue'][$i]['Question_ID']);

    $stm->execute();

    $_SESSION['AllQueRight'][$i] = $stm->fetchAll(PDO::FETCH_ASSOC);


    $stm = $conn->prepare("select count(*) as sum from php_db.Statistics where Question_ID = :id and result = 0");

    $stm->bindParam(':id', $_SESSION['AllQue'][$i]['Question_ID']);

    $stm->execute();

    $_SESSION['AllQueFalse'][$i] = $stm->fetchAll(PDO::FETCH_ASSOC);
}
//echo '<pre>';
//print_r($_SESSION);
//echo '</pre>';

?>

    <table style="color: white">
        <tr>
            <th style="color: #74cbe8">Question</th>
            <th style="color: #74cbe8">Answered Right (%)</th>
        </tr>
        <?php
        for ($i = 0; $i < count($_SESSION['AllQue']); $i++) {

            if (($_SESSION['AllQueRight'][$i][0]['sum'] + $_SESSION['AllQueFalse'][$i][0]['sum']) != 0) {
                $sum = round((100 * $_SESSION['AllQueRight'][$i][0]['sum']) / ($_SESSION['AllQueRight'][$i][0]['sum'] + $_SESSION['AllQueFalse'][$i][0]['sum']));
                $per = '%';
            }else{
                $sum = 'The question have not answers whet';
                $per = null;
            }
            echo '<tr>';
            echo '<th>',$_SESSION['AllQue'][$i]['Question'],'</th>';
            echo '<th>',$sum,$per,'</th>';
            echo '<tr>';
        }

        ?>
    </table>

    <a href="statistics.php" style="color: white">
        <button style="width: 900px; margin-left: 160px; margin-top: 100px;">See other statistics</button>
    </a>
    <?php
    exit();
}

?>



<?php

} else {
    echo '<h4>401, Unauthorized</h4>';
}

?>

