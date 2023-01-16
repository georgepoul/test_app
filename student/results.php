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
echo '<pre>';
print_r($_SESSION['input']);
echo '</pre>';
} else {
    echo '<h4>401, Unauthorized</h4>';
}

?>

