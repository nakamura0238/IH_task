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

    // 自分のindexの場合トップページへ
    if ($_SESSION['user_index'] == $_REQUEST['index']) {
        header('Location: ../index.php');
    }

    // ユーザー情報抽出
    if (isset($_REQUEST['index'])) {
        $user_state = $db -> prepare('SELECT * FROM users WHERE user_index = ?;');
        $user_state -> execute(array(
            $_REQUEST['index']
        ));
        $user = $user_state -> fetch(PDO::FETCH_ASSOC);

        //フォロー情報抽出
        $follow_state = $db -> prepare('SELECT * FROM follows WHERE follow_index = ? AND follower_index = ? LIMIT 1;');
        $follow_state -> execute(array(
            $_SESSION['user_index'],
            $_REQUEST['index']
        ));
        $follow = $follow_state -> fetch(PDO::FETCH_ASSOC);
        if ($follow) {
            $follow_flg = true;
        } else {
            $follow_flg = false;
        }

        // 好み抽出
        $like_state = $db -> prepare(
            'SELECT
                *
            FROM (
                SELECT
                    likes.like_index
                    , likes.user_index as user
                    , likes.genre_a
                    , A.genre_a_name
                    , likes.genre_b
                    , B.genre_b_name
                    , likes.genre_c
                    , genre_ab_cf.AB_cf
                    , genre_abc_cf.ABC_cf
                FROM
                    likes
                    JOIN genre_a as A
                        ON likes.genre_a = A.genre_a_index
                    JOIN genre_b as B
                        ON likes.genre_b = B.genre_b_index
                    LEFT JOIN (
                        -- AB一致抽出 --
                        SELECT
                            genre_ab.A_cf as A_cf
                            , genre_ab.B_cf as B_cf
                            , count(genre_ab.B_cf) as AB_cf
                        FROM (
                            -- ABジャンル抽出 --
                            SELECT DISTINCT
                                likes.user_index
                                , likes.genre_a as A_cf
                                , likes.genre_b as B_cf
                            FROM
                                likes
                                JOIN genre_a as A
                                    ON likes.genre_a = A.genre_a_index
                                JOIN genre_b as B
                                    ON likes.genre_b = B.genre_b_index
                            WHERE
                                -- ユーザー抽出 --
                                likes.user_index = ? OR likes.user_index = ?
                            ) as genre_ab
                        GROUP BY
                            genre_ab.A_cf, genre_ab.B_cf
                    ) as genre_ab_cf
                        ON likes.genre_a = genre_ab_cf.A_cf AND likes.genre_b = genre_ab_cf.B_cf
            
                -- ここからABC一致 --
                    LEFT JOIN (
                        -- AB一致抽出 --
                        SELECT
                            genre_abc.A_cf as A_cf
                            , genre_abc.B_cf as B_cf
                            , genre_abc.C_cf as C_cf
                            , count(genre_abc.C_cf) as ABC_cf
                        FROM (
                            -- ABジャンル抽出 --
                            SELECT DISTINCT
                                likes.user_index
                                , likes.genre_a as A_cf
                                , likes.genre_b as B_cf
                                , likes.genre_c as C_cf
                            FROM
                                likes
                                JOIN genre_a as A
                                    ON likes.genre_a = A.genre_a_index
                                JOIN genre_b as B
                                    ON likes.genre_b = B.genre_b_index
                            WHERE
                                -- ユーザー抽出 --
                                likes.user_index = ? OR likes.user_index = ?
                            ) as genre_abc
                        GROUP BY
                            genre_abc.A_cf, genre_abc.B_cf, genre_abc.C_cf
                    ) as genre_abc_cf
                        ON likes.genre_a = genre_abc_cf.A_cf AND likes.genre_b = genre_abc_cf.B_cf AND likes.genre_c = genre_abc_cf.C_cf
                -- ここまでABC一致 --
            
                GROUP BY
                    likes.user_index, likes.genre_a, likes.genre_b, likes.genre_c
                ORDER BY
                    likes.genre_a, likes.genre_b, likes.genre_c
                ) as genre_all
            WHERE
                -- ユーザー抽出 --
                genre_all.user = ?
            ORDER BY
                genre_a, genre_b'
        );
        $like_state -> execute(array(
            $_REQUEST['index'],
            $_SESSION['user_index'],
            $_REQUEST['index'],
            $_SESSION['user_index'],
            $_REQUEST['index']
        ));

        $like_list = $like_state -> fetchall(PDO::FETCH_ASSOC);

    } else {
        header('Location: ./search_user.php');
        exit();
    }

    // フォロー情報確認
    

    require('../functions/component.php');
?>

    <link rel="stylesheet" href="../css/user_page.css">
    <script src="../follow/follow.js" defer></script>
    <title>Document</title>
</head>
<body>

    <?php require('../functions/header.php'); ?>

    <main>
        <div>
            <img src="../images/user/<?php  echo $user['picture'] != NULL ? $user['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
            <p><?php echo $user['name'] ?></p>
            <p><?php echo $user['user_id'] ?></p>
        </div>

        <div class="follow-area">
            <button class="btn-follow js-btn-follow">
                <?php
                    if ($follow_flg) {
                        echo 'following';
                    } else {
                        echo 'follow';
                    }
                ?>
            </button>

            <!-- フォローモーダル -->
            <div class="follow-modal js-follow-modal">
                <div class="modal-window">
                    <div class="box-modal-item">
                        <p class="modal-message">
                            <?php
                                if ($follow_flg) {
                                    echo 'フォロー解除します';
                                } else {
                                    echo 'フォローします';
                                }
                            ?>
                        </p>
                        <div class="box-modal-btn">
                            <button class="follow-submit js-follow-submit js-close-modal" value="<?php echo $_REQUEST['index'];?>">
                                <?php
                                    if ($follow_flg) {
                                        echo 'unfollow';
                                    } else {
                                        echo 'follow';
                                    }
                                ?>
                            </button>
                            <button class="close-modal js-close-modal">close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="like-area">
            <?php foreach ($like_list as $like) { ?>
                <div class="like-tag">
                    <div class="item-like item-like-a"><?php echo $like['genre_a_name']; ?></div>
                    <div class="item-like item-like-b"><?php echo $like['genre_b_name']; ?></div>
                    <div class="item-like item-like-c"><?php echo $like['genre_c']; ?></div>
                    <div class="item-like imte-like-mark">
                        <?php 
                            if ($like['ABC_cf'] > 1) { 
                                echo '☆';
                            } elseif ($like['AB_cf'] > 1) {
                                echo '○';
                            }
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="result"></div>
    </main>

    <footer>
    </footer>
</body>
</html>