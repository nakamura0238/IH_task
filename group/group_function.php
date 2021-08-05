<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    // グループへ参加
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

    // グループへ招待
    if (isset($_POST) && $_POST['status'] == 'invitation') {
        $check_state = $db -> prepare('SELECT * FROM groups_invitation WHERE group_index = ? AND inv_user_index = ?');
        $check_state -> execute(array(
            $_POST['group_index'],
            $_POST['inv_user_index']
        ));
        $check = $check_state -> fetch(PDO::FETCH_ASSOC);

        if (empty($check)) {
            // グループへ招待
            $enter_group = $db -> prepare('INSERT INTO groups_invitation SET group_index = ?, inv_user_index = ?;');
            $enter_group -> execute(array(
                $_POST['group_index'],
                $_POST['inv_user_index']
            ));

            $json = ['result' => 'true'];
        }
    }

    // メンバー削除
    if (isset($_POST) && $_POST['status'] == 'member_delete') {
        // グループへ招待
        $member_delete = $db -> prepare('DELETE FROM group_user WHERE group_index = ? AND inv_user_index = ?;');
        $member_delete -> execute(array(
            $_POST['group_index'],
            $_POST['user_index']
        ));

        $json = ['result' => 'true'];
    }

    // 招待削除
    if (isset($_POST) && $_POST['status'] == 'inv_delete') {
        // グループへ招待
        $inv_delete = $db -> prepare('DELETE FROM groups_invitation WHERE group_index = ? AND inv_user_index = ?;');
        $inv_delete -> execute(array(
            $_POST['group_index'],
            $_POST['inv_user_index']
        ));

        $json = ['result' => 'true'];
    }

    echo json_encode($json);

?>