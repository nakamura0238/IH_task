<?php

    session_start();

    $_SESSION = array();
    session_destroy();

    if (isset($_SESSION['PHPSESSID'])) {
        setcookie('PHPSESSID', '', time() - 3600);
    }

    setcookie('user_id', '', time() - 3600);
    setcookie('password', '', time() - 3600);


    header('Location:login.php');
    exit();

?>