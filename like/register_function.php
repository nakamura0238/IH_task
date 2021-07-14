<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    // $search_word = $_POST['word'] . '%';
    $statement = $db -> prepare('SELECT * FROM genre_b WHERE in_genre_a = ?;');
    $statement -> execute(array(
        $_POST['genre_a']
    ));

    $genres = $statement -> fetchall(PDO::FETCH_ASSOC);

    // if (is_array($genres)) {
    //     $users = array_merge($users, array('my_id' => $_SESSION['user_id']));
    // }
    echo json_encode($genres);


?>