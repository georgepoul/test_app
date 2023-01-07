<?php
session_start();
if ($_SESSION['role'] == 'Teacher') {
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
    ?>

</head>
<body style="background-color: #080710">
<div class="background">
    <div class="shape"></div>
    <div class="shape"></div>
</div>
<form>
    <h3 style="text-align: center">Hello Mr/Ms <?php echo $_SESSION['username']; ?> </h3>
    <p style="text-align: left">In our page you can: </p>
    <ul>
        <li>See statistics based on course, degree of difficulty and question</li>
        <li>Create delete courses and questions</li>
    </ul>
</form>
</body>
</html>

<?php
}else{
    echo '<h4>401, Unauthorized</h4>';
}
?>

