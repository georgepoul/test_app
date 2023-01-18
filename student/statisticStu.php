<!--//$_SESSION['stat'],$_SESSION['date'];-->
<?php
session_start();
if ($_SESSION['role'] == 'Student') {
include('../database/db_connection.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title> <?php echo $_SESSION['lesson'] ?> Test</title>
    <link rel="stylesheet" href="../css/test.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>


    <?php
    require('../templates/studentHeader.inc.php');


    ?>

</head>
<body style="background-color: #080710">

<?php


try {




    $right = $conn->prepare("select count(*) as sum from php_db.Statistics where User_ID = :user and result=1");

    $right->bindParam(':user', $_SESSION['user_id']);
    $right->execute();

    $_SESSION['RightUser'] = $right->fetchAll(PDO::FETCH_ASSOC);

    $false = $conn->prepare("select count(*) as sum from php_db.Statistics where User_ID = :user and result=0");

    $false->bindParam(':user', $_SESSION['user_id']);
    $false->execute();

    $_SESSION['FalseUser'] = $false->fetchAll(PDO::FETCH_ASSOC);

    echo '<h3 style="color: greenyellow;text-align: center">Here is your statistic at all tests:</h3>'

    ?>
    <canvas id="pie-chart" style="margin-top: 50px"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>

    <script>
        var ctx = document.getElementById('pie-chart').getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Right Answers','Wrong answers'],
                datasets: [{
                    data: [<?php echo $_SESSION['RightUser'][0]['sum'] ?>, <?php echo $_SESSION['FalseUser'][0]['sum'] ?> ],
                    backgroundColor: [ '#4bc0c0', '#ff6384']
                }]
            }
        });
    </script>

    <?php
    exit();
} catch (PDOException $e) {

    echo 'Something bad happened';
}
?>



<?php

} else {
    echo '<h4>401, Unauthorized</h4>';
}

?>

