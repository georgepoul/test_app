<?php

include 'dbconfig.php';

try {
    $conn = new PDO("mysql:host=$dbServername;dbname=$dbname", $dbUsername, $dbPassword);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "Something bad happened";
}

