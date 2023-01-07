<?php

$_SESSION = null;

session_destroy();

header("Location: homePage.php");
