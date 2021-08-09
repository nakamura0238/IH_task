<?php

    ini_set('display_errors', "0");

    require('../../functions/dbconnect.php');

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
    

    require('../../functions/component.php');
?>


<!DOCTYPE html>
<html lang="ja">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>ユーザーページ</title>

        <link rel="stylesheet" type="text/css" href="css/reset.css">
        <link rel="stylesheet" type="text/css" href="css/parts.css">
        <link rel="stylesheet" type="text/css" href="css/common.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/responsive.css">
        <link rel="stylesheet" type="text/css" href="css/user_page.css">

        <script src="https://code.jquery.com/jquery-2.1.0.min.js" ></script>
        <script src="js/script.js"></script>

    </head>

    <body id="user">

        <!-- ヘッダー -->
        <header>
            <div class="headerWrapper">
                <!-- ロゴ -->
                <h1>
                    <a href="index.html">
                        <img src="images/Logo.png">
                    </a>
                </h1>
                <!-- ナビゲーション -->
                <nav class="nav">
                    <a href="search.html">
                        <img src="images/search.png"><br>
                        <div class="description">検索</div>
                        <span>検索</span>
                    </a>
                    <a href="makeGroup.html">
                        <img src="images/groupMake.png"><br>
                        <div class="description">グループ作成</div>
                        <span>グループ作成</span>
                    </a>
                    <a href="setting.html">
                        <img src="images/setting.png"><br>
                        <div class="description">設定</div>
                        <span>設定</span>
                    </a>
                    <a href="favoriteRegister.html">
                        <img src="images/favoriteRegister.png"><br>
                        <div class="description">好み登録</div>
                        <span>好み登録</span>
                    </a>
                    <a href="login.html">
                        <img src="images/logout.png"><br>
                        <div class="description">ログアウト</div>
                        <span>ログアウト</span>
                    </a>
                </nav>

                <!-- ハンバーガーメニューボタン -->
                <button type="button" class="navBtn js-navBtn">
                    <span class="btn-line"></span>
                </button>

            </div>
        </header>


        <!-- プロフィール -->
        <div id="profile">
            <div class="center">
                <div class="left">
                    <div class="profile">
                        <img src="images/no_img.png" alt="profileImage">
                        <div>
                            <h2>Name</h2><br>
                            <p>@ID</p>
                        </div>
                    </div>
                    <!-- フォローボタン -->
                    <button class="form-style">フォロー</button>
                </div>
            </div>
        </div>


        <!-- 見出し -->
        <div class="favoriteHeader">
            <div class="center">
                <div class="right">
                    <h2>
                        My Favorites
                    </h2>
                </div>
            </div>
        </div>


        <!-- メイン -->
        <div id="responsiveWrapper">
            <main>
                <!-- 好み一覧 -->
                <div id="favorites">
                    <div class="catalog">

                            <?php foreach ($like_list as $like) { ?>
                                <div class="favorite">
                                    <div class="like-tag">
                                        <p>
                                            <span class="item-like item-like-a genreS"><?php echo $like['genre_a_name']; ?></span>
                                            <span class="item-like item-like-b genreM"><?php echo $like['genre_b_name']; ?>:</span>
                                            <span class="item-like item-like-c genreL"><?php echo $like['genre_c']; ?></span>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="result"></div>
                        
                    </div>
                </div>
            </main>
        </div>


        <footer>
            2021 &copy; GroupM.
        </footer>
        
    </body>

</html>