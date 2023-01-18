<!DOCTYPE html>
<html>
<head>
    <title><?php if (isset($title)) print($title); else print("Το καλύτερο site!"); ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/concise.min.css">
    <link rel="stylesheet" href="../css/masthead.css">
</head>
<body>
<header container class="siteHeader">
    <div row>
        <h1 column="4" class="log"><a href="student.php">Test Page</a></h1>
        <nav column="8" class="nav">
            <ul>
                <li><a href="testme.php">Test yourself</a></li>
                <li><a href="statisticStu.php">Statistics</a></li>
                <li><a href="../logout.php">Log out</a></li>
            </ul>
        </nav>
    </div>
</header>
<main container class="siteContent"