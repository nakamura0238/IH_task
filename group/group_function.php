<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    if (isset($_POST) && $_POST['status'] == 'enter') {
        $check_state = $db -> prepare('SELECT * FROM group_user WHERE group_index = ? AND user_index = ?');
        $check_state -> execute(array(
            $_POST['group_index'],
            $_SESSION['user_index']
        ));
        $check = $check_state -> fetch(PDO::FETCH_ASSOC);

        if (empty($check)) {
            // グループへ参加
            $enter_group = $db -> prepare('INSERT INTO group_user SET group_index = ?, user_index = ?;');
            $enter_group -> execute(array(
                $_POST['group_index'],
                $_SESSION['user_index']
            ));
            // 招待から削除
            $inv_delete = $db -> prepare('DELETE FROM groups_invitation WHERE group_index = ? AND inv_user_index = ? LIMIT 1;');
            $inv_delete -> execute(array(
                $_POST['group_index'],
                $_SESSION['user_index']
            ));

            $json = ['result' => 'true'];
        }
        
    }

    // グループ内の集計


    echo json_encode($json);

?>