<?php

unset($_SESSION);

session_destroy();

header("Location: homePage.php");
