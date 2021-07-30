<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    if (isset($_POST)) {
        $statement = $db -> prepare('SELECT * FROM follows WHERE follow_index = ? AND follower_index = ? LIMIT 1;');
        $statement -> execute(array(
            $_SESSION['user_index'],
            $_POST['follower_user']
        ));

        $follow = $statement -> fetch(PDO::FETCH_ASSOC);

        if (!($follow)) {
            $follow_register = $db -> prepare('INSERT INTO follows SET follow_index = ?, follower_index = ?, follow_at = NOW()');
            $follow_register -> execute(array(
                $_SESSION['user_index'],
                $_POST['follower_user']
            ));
            $json = ['result' => true, 'result_message' => 'レコードを追加しました'];
        } else {
            $follow_delete = $db -> prepare('DELETE FROM follows WHERE follow_index = ? AND follower_index = ?');
            $follow_delete -> execute(array(
                $_SESSION['user_index'],
                $_POST['follower_user']
            ));
            $json = ['result' => false, 'result_message' => 'レコードを削除しました'];
        }


    }

    echo json_encode($json);

?>