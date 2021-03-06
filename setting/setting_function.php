<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    // ιδΌε¦η
    if (isset($_POST) && $_POST['status'] == 'quit') {
        $user_auth_state = $db -> prepare(
            'SELECT user_index
            FROM users
            WHERE user_index = ?
                AND user_id = ?
                AND password = ?'
        );
        $user_auth_state -> execute(array(
            $_SESSION['user_index'],
            $_SESSION['user_id'],
            sha1(escape($_POST['user_password']))
        ));
        $user_auth = $user_auth_state -> fetch(PDO::FETCH_ASSOC);

        $json = $user_auth;
    }

    echo json_encode($json);