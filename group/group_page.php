<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    if (isset($_SESSION['user_id']) && $_SESSION['time'] + 3600 > time()) {
        // 接続時間更新
        $_SESSION['time'] = time();
    } else {
        header('Location: ../login/login.php');
        exit();
    }

    // 所属していないグループにアクセスした時
    $check_group_state = $db -> prepare('SELECT count(*) AS check_num FROM group_user WHERE group_index = ? AND user_index = ?');
    $check_group_state -> execute(array(
        escape($_REQUEST['group_index']),
        $_SESSION['user_index'],
    ));
    $check_group = $check_group_state -> fetch(PDO::FETCH_ASSOC);

    if ($check_group['check_num'] < 1) {
        header('Location: ../index.php');
        exit();
    }


    // ユーザー抽出
    if (isset($_REQUEST['group_index'])) {
        // ユーザー数取得
        $users_num_state = $db -> prepare(
            'SELECT count(*) AS u_num
            FROM group_user
            WHERE group_index = ?'
        );
        $users_num_state -> execute(array(
            $_REQUEST['group_index']
        ));
        $users_num = $users_num_state -> fetch(PDO::FETCH_ASSOC);

        // ユーザー取得
        $users_state = $db -> prepare(
            'SELECT *
            FROM group_user AS g_u
            JOIN users AS u
                ON g_u.user_index = u.user_index
            WHERE group_index = ?'
        );
        $users_state -> execute(array(
            $_REQUEST['group_index']
        ));
        $users = $users_state -> fetchall(PDO::FETCH_ASSOC);

        // 招待ユーザー取得
        $inv_users_state = $db -> prepare(
            'SELECT *
            FROM groups_invitation AS g_i
            JOIN users AS u
                ON g_i.inv_user_index = u.user_index
            WHERE group_index = ?'
        );
        $inv_users_state -> execute(array(
            $_REQUEST['group_index']
        ));
        $inv_users = $inv_users_state -> fetchall(PDO::FETCH_ASSOC);

        // SQL生成
        $user_array = '';
        foreach ($users as $user) {
            $user_array .= $user['user_index'] . ',';
        }
        $sql_1 = 'SELECT user_index, genre_a, genre_a_name, genre_b, genre_b_name, genre_c, count(*) AS num
                FROM likes AS l
                JOIN genre_a AS g_a
                    ON l.genre_a = g_a.genre_a_index
                JOIN genre_b AS g_b
                    ON l.genre_b = g_b.genre_b_index
                GROUP BY user_index, genre_a, genre_b
                HAVING l.user_index in (' . substr($user_array, 0 ,-1) . ')';

        $sql_2 = 'SELECT genre_b_agg.genre_a, genre_b_agg.genre_a_name, genre_b_agg.genre_b, genre_b_agg.genre_b_name, genre_b_agg.genre_c, count(*) AS num
                FROM (' . $sql_1 . ') AS genre_b_agg
                GROUP BY genre_b_agg.genre_a, genre_b_agg.genre_b
                ORDER BY num DESC, genre_a, genre_b';
    }

    $like_list_state = $db -> query($sql_2);
    $like_list = $like_list_state -> fetchall(PDO::FETCH_ASSOC);


    require('../functions/component.php');
?>

    <link rel="stylesheet" href="../css/group_page.css">
    <script src="./group_function.js" defer></script>
    <title>Document</title>
</head>
<body>

    <?php require('../functions/header.php'); ?>

    <main>

    <a href="./group_setting.php?group_index=<?php echo $_REQUEST['group_index']; ?>">グループ設定</a>

        <div class="group-area">
            <?php 
                $sql_3 = 'SELECT g_c_agg.genre_a_name, g_c_agg.genre_b_name, g_c_agg.genre_c, count(*) AS num
                        FROM (' . $sql_1 . ') AS g_c_agg
                        GROUP BY genre_a, genre_b, genre_c
                        HAVING g_c_agg.genre_a = ? AND g_c_agg.genre_b = ?
                        ORDER BY g_c_agg.num DESC, genre_c';

                foreach ($like_list as $record) { 
            ?>
                <div class="box-genre-all">
                    <div class="block-genre-ab">
                        <p class="item-genre-b-name"><?php echo $record['genre_b_name']; ?></p>
                        <p class="item-genre-a-name"><?php echo $record['genre_a_name']; ?></p>
                        <p class="item-genre-num"><?php echo $record['num']; ?>人</p>
                    </div>
                    <div class="box-genre-c">
            <?php
                $genre_c_list_state = $db -> prepare($sql_3);
                $genre_c_list_state -> execute(array(
                    $record['genre_a'],
                    $record['genre_b']
                ));
                $genre_c_list = $genre_c_list_state -> fetchall(PDO::FETCH_ASSOC);

                foreach ($genre_c_list as $genre_c) {
            ?>
                        <div class="block-genre-c">
                            <p class="item-genre-c-name"><?php echo $genre_c['genre_c'] ?></p>
                            <p class="item-genre-c-num"><?php echo $genre_c['num'] ?>人</p>
                        </div>
                <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="user-area">
            <div class="menber-area">
                <p>member</p>
                <?php foreach ($users as $user) { ?>
                    <a class="member-user box-user" href="../user/user_page.php?index=<?php echo $user['user_index']; ?>">
                        <img class="item-picture" src="../images/user/<?php echo $user['picture'] != NULL ? $user['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
                        <p class="item-name"><?php echo $user['name']; ?></p>
                    </a>
                <?php } ?>
            </div>
            <div class="inv-area">
                <p>invitation</p>
                <?php foreach ($inv_users as $inv_user) { ?>
                    <a class="member-user box-user" href="../user/user_page.php?index=<?php echo $inv_user['user_index']; ?>">
                        <img class="item-picture" src="../images/user/<?php echo $inv_user['picture'] != NULL ? $inv_user['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
                        <p class="item-name"><?php echo $inv_user['name']; ?></p>
                    </a>
                <?php } ?>
            </div>
        </div>
    </main>

    <footer>
    </footer>
</body>
</html>