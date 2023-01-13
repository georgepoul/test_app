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
        <h1 column="4" class="log"><a href="teacher.php">Test Page</a></h1>
        <nav column="8" class="nav">
            <ul>
                <li><a href="editCourses.php">Edit Courses</a></li>
                <li><a href="courses.php">Edit Questions</a></li>
                <li><a href="statistics.php">Statistics</a></li>
                <li><a href="../logout.php">Log out</a></li>
            </ul>
        </nav>
    </div>
</header>
<main container class="siteContent">