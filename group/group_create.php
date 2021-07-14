<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    if (isset($_SESSION['email']) && $_SESSION['time'] + 3600 > time()) {
        // 接続時間更新
        $_SESSION['time'] = time();
    } else {
        header('Location: ../login/login.php');
        exit();
    }

    // グループ作成
    if (isset($_POST['group_name']) && isset($_POST['user'])) {
        // ランダム文字列生成
        $random = sha1(uniqid(mt_rand(), true));

        // グループの作成
        $create_group = $db -> prepare('INSERT INTO groups SET group_name = ?, random_code = ?');
        $create_group -> execute(array(
            $_POST['group_name'],
            $random
        ));
        // グループインデックス取得
        $find_group = $db -> prepare('SELECT group_index FROM groups WHERE random_code = ?');
        $find_group -> execute(array(
            $random
        ));
        $group_index = $find_group -> fetch(PDO::FETCH_ASSOC);
        // 自身をグループに追加
        $join_group = $db -> prepare('INSERT INTO group_user SET group_index = ?, user_index = ?');
        $join_group -> execute(array(
            $group_index['group_index'],
            $_SESSION['user_index']
        ));
        
        // グループへ招待
        foreach ($_POST['user'] as $user) {
            $group_inv_state = $db -> prepare('INSERT INTO groups_invitation SET group_index = ?, inv_user_index  = ?');
            $group_inv_state -> execute(array(
                $group_index['group_index'],
                $user
            ));
        }

        header('Location: ./group_top.php');
        exit();
    }


    // フォロー抽出
    $follow_list_state = $db -> prepare(
        'SELECT f.ff_index, f.follower_index, u.user_id, u.name, u.picture
        FROM follows AS f
        JOIN users AS u ON f.follower_index = u.user_index
        WHERE f.follow_index = ?
        ORDER BY f.follow_at DESC'
    );
    $follow_list_state -> execute(array(
        $_SESSION['user_index']
    ));
    $follow_list = $follow_list_state -> fetchall(PDO::FETCH_ASSOC);


    require('../functions/component.php');
?>

    <script src="./group_function.js" defer></script>
    <title>Document</title>
</head>
<body>

    <?php require('../functions/header.php'); ?>

    <?php
        print_r($_POST);
        echo $_POST['user'][1];
    ?>

    <main>
        <a href="./group_top.php">戻る</a>
        <br>
        <form action="" method="POST"  autocomplete="off">
            <label>
                <span>グループ作成</span><br>
                <input class="js-group-name input-group-name" type="text" name="group_name" placeholder="グループ名">
            </label>
            <button class="js-btn-group">作成</button>

            <p>フォロー中</p>
            
            <div class="js-follow-user follow-user">
                <?php foreach ($follow_list as $record) { ?>
                    <label>
                        <input type="checkbox" name="user[]" value="<?php echo $record['follower_index'] ?>">
                        <img src="../images/user/<?php  echo $record['picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
                        <p class="item-follow">name:<?php echo $record['name']; ?></p>
                    </label>
                <?php } ?>
            </div>
        </form>
    </main>

    <footer>
    </footer>
</body>
</html>