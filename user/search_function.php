<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    // $search_word = $_POST['word'] . '%';
    $statement = $db -> prepare('SELECT user_index, user_id, `name` FROM users WHERE user_id = ?;');
    $statement -> execute(array(
        $_POST['word']
    ));

    $users = $statement -> fetch(PDO::FETCH_ASSOC);

    if (is_array($users)) {
        $users = array_merge($users, array('my_id' => $_SESSION['user_id']));
    }
    echo json_encode($users);

?>