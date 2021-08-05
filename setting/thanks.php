<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    session_start();

    // データ削除
        // ユーザー削除
            $delete_u = $db -> prepare('DELETE FROM users WHERE user_index = ?;');
            $delete_u -> execute(array($_SESSION['user_index']));
        // 好み削除
            $delete_l = $db -> prepare('DELETE FROM likes WHERE user_index = ?;');
            $delete_l -> execute(array($_SESSION['user_index']));
        // フォロー削除
            $delete_f = $db -> prepare('DELETE FROM follows WHERE follow_index = ? OR follower_index = ?;');
            $delete_f -> execute(array($_SESSION['user_index'], $_SESSION['user_index']));
        // フォローリクエスト削除
            $delete_f_r = $db -> prepare('DELETE FROM follows_request WHERE follow_index = ? OR follower_index = ?');
            $delete_f_r -> execute(array($_SESSION['user_index'], $_SESSION['user_index']));
        // グループ削除
            $delete_g = $db -> prepare('DELETE FROM group_user WHERE user_index = ?;');
            $delete_g -> execute(array($_SESSION['user_index']));
        // グループ招待削除
            $delete_g_i = $db -> prepare('DELETE FROM groups_invitation WHERE inv_user_index = ?;');
            $delete_g_i -> execute(array($_SESSION['user_index']));


    $_SESSION = array();
    session_destroy();

    if (isset($_SESSION['PHPSESSID'])) {
        setcookie('PHPSESSID', '', time() - 3600);
    }

    setcookie('email', '', time() - 3600);
    setcookie('password', '', time() - 3600);


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Thank you for using.</p>
    <a href="../signup/signup.php">return</a>
</body>
</html>