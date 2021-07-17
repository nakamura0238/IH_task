<?php

    ini_set('display_errors', "On");

    require('./functions/dbconnect.php');
    require('./functions/function.php');

    session_start();

    if (isset($_SESSION['email']) && $_SESSION['time'] + 3600 > time()) {
        // 接続時間更新
        $_SESSION['time'] = time();
    } else {
        header('Location: ./login/login.php');
        exit();
    }

    // ユーザー情報抽出
    $user_state = $db -> prepare('SELECT * FROM users WHERE user_index = ?;');
    $user_state -> execute(array(
        $_SESSION['user_index']
    ));
    $user = $user_state -> fetch(PDO::FETCH_ASSOC);

    // 好み抽出
    $genre_list_state = $db -> prepare(
        'SELECT likes.user_index, likes.genre_a, A.genre_a_name, likes.genre_b, B.genre_b_name, likes.genre_c
        FROM likes
        JOIN genre_a AS A ON likes.genre_a = A.genre_a_index
        JOIN genre_b AS B ON likes.genre_b = B.genre_b_index
        WHERE likes.user_index = ?
        ORDER BY likes.genre_a, likes.genre_b, likes.genre_c'
    );
    $genre_list_state -> execute(array(
        $_SESSION['user_index']
    ));
    $genre_list = $genre_list_state -> fetchall(PDO::FETCH_ASSOC);
    
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

    // フォロワー抽出
    $follower_list_state = $db -> prepare(
        'SELECT f.ff_index, f.follow_index, u.user_id, u.name, u.picture
        FROM follows AS f
        JOIN users AS u ON f.follow_index = u.user_index
        WHERE f.follower_index = ?
        ORDER BY f.follow_at DESC'
    );
    $follower_list_state -> execute(array(
        $_SESSION['user_index']
    ));
    $follower_list = $follower_list_state -> fetchall(PDO::FETCH_ASSOC);

    // グループ招待抽出
    $group_inv_list_state = $db -> prepare(
        'SELECT g_i.*, g.group_name, g.group_picture
        FROM groups_invitation AS g_i
        JOIN groups AS g ON g_i.group_index = g.group_index
        WHERE g_i.inv_user_index = ?
        ORDER BY g.group_name'
    );
    $group_inv_list_state -> execute(array(
        $_SESSION['user_index']
    ));
    $group_inv_list = $group_inv_list_state -> fetchall(PDO::FETCH_ASSOC);

    // グループ抽出
    $group_list_state = $db -> prepare(
        'SELECT g_u.*, g.group_name, g.group_picture
        FROM group_user AS g_u
        JOIN groups AS g ON g_u.group_index = g.group_index
        WHERE g_u.user_index = ?
        ORDER BY g.group_name'
    );
    $group_list_state -> execute(array(
        $_SESSION['user_index']
    ));
    $group_list = $group_list_state -> fetchall(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/destyle.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/index.css">
    <script src="./group/group_function_index.js" defer></script>
    <title>Document</title>
</head>
<body>
    <header>
        <a href="./index.php">トップページ</a>
        <div class="nav">
            <a href="./genre/register_A.php">分類登録</a><br>
            <a href="./like/register_like.php">好み登録</a><br>
            <a href="./user/search_user.php">ユーザー検索</a>
            <a href="./group/group_top.php">グループ</a>
            <a href="./setting/setting.php">設定</a>
        </div>
    </header>

    <main>
        <a href="./login/logout.php">ログアウト</a>
        <div>
            <img src="./images/user/<?php  echo $user['picture'] != NULL ? $user['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
            <br>
            <?php
                echo "user_name : " . $_SESSION['user_name'] . "<br>";
                echo "user_id : " . $_SESSION['user_id'] . "<br>";
                echo "email : " . $_SESSION['email'] . "<br>";
            ?>
        </div>

        <!-- 好み表示 -->
        <div class="genre-area">
            <p>--likes--</p>
            <?php foreach ($genre_list as $record) { ?>
                <div class="like-tag">
                    <div class="item-genre item-genre-a"><?php echo $record['genre_a_name']; ?></div>
                    <div class="item-genre item-genre-b"><?php echo $record['genre_b_name']; ?></div>
                    <div class="item-genre item-genre-c"><?php echo $record['genre_c']; ?></div>
                </div>
            <?php } ?>
        </div>

        <!-- フォロー表示 -->
        <div class="follow-area">
            <p>--follow--</p>
            <?php foreach ($follow_list as $record) { ?>
                <a class="follow-user box-user" href="./user/user_page.php?index=<?php echo $record['follower_index']; ?>">
                    <img class="item-picture" src="./images/user/<?php  echo $record['picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
                    <div class="flex">
                        <p class="item-name"><?php echo $record['name']; ?></p>
                        <!-- <p class="item-id">ID:<?php echo $record['user_id']; ?></p> -->
                    </div>
                </a>
            <?php } ?>
        </div>

        <!-- フォロワー表示 -->
        <div class="follower-area">
            <p>--follower--</p>
            <?php foreach ($follower_list as $record) { ?>
                <a class="follower-user box-user" href="./user/user_page.php?index=<?php echo $record['follow_index']; ?>">
                    <img class="item-picture" src="./images/user/<?php  echo $record['picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
                    <div class="flex">
                        <p class="item-name"><?php echo $record['name']; ?></p>
                        <!-- <p class="item-id">ID:<?php echo $record['user_id']; ?></p> -->
                    </div>
                </a>
            <?php } ?>
        </div>

        <!-- グループ表示 -->
        <div class="group-area">
            <div class="inv-group-area">
                <p>--invitation--</p>
                <?php foreach ($group_inv_list as $record) { ?>
                    <div class="inv_group js-inv-group">
                        <img src="./images/group/<?php  echo $record['group_picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="グループイメージ" height="100">
                        <div class="item-group">name:<?php echo $record['group_name']; ?></div>
                        <button class="enter-group-index js-enter-group-index" value="<?php echo $record['group_index']; ?>">参加する</button>
                    </div>
                <?php } ?>
            </div>

            <div class="join-group-area">
                <p>--group--</p>
                <?php foreach ($group_list as $record) { ?>
                    <a class="join-group" href="./group/group_page.php?group_index=<?php echo $record['group_index']; ?>">
                        <img src="./images/group/<?php  echo $record['group_picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="グループイメージ" height="100">
                        <div class="item-group">name:<?php echo $record['group_name']; ?></div>
                    </a>
                <?php } ?>
            </div>
        </div>

    </main>

    <footer>

    </footer>
</body>
</html>