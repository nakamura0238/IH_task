<?php

    ini_set('display_errors', "On");

    require('./functions/dbconnect.php');
    require('./functions/function.php');

    session_start();

    if (isset($_SESSION['user_id']) && $_SESSION['time'] + 3600 > time()) {
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


    // フォローリクエスト抽出 フォロー側
    // $follow_request_list_state = $db -> prepare(
    //     'SELECT f_r.request_index, f_r.follower_index, u.user_id, u.name, u.picture
    //     FROM follows_request AS f_r
    //     JOIN users AS u ON f_r.follower_index = u.user_index
    //     WHERE f_r.follow_index = ?
    //     ORDER BY f_r.request_at DESC'
    // );
    // $follow_request_list_state -> execute(array(
    //     $_SESSION['user_index']
    // ));
    // $follow_request_list = $follow_request_list_state -> fetchall(PDO::FETCH_ASSOC);


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


    // フォローリクエスト抽出 フォロワー側
    // $follower_request_list_state = $db -> prepare(
    //     'SELECT f_r.request_index, f_r.follower_index, u.user_id, u.name, u.picture
    //     FROM follows_request AS f_r
    //     JOIN users AS u ON f_r.follow_index = u.user_index
    //     WHERE f_r.follower_index = ?
    //     ORDER BY f_r.request_at DESC'
    // );
    // $follower_request_list_state -> execute(array(
    //     $_SESSION['user_index']
    // ));
    // $follower_request_list = $follower_request_list_state -> fetchall(PDO::FETCH_ASSOC);


    // フォロワー抽出
    $follower_list_state = $db -> prepare(
        'SELECT f.ff_index, f.follow_index, u.user_id, u.name, u.picture, f_i.ff_index
        FROM follows AS f
        JOIN users AS u 
			ON f.follow_index = u.user_index
		LEFT OUTER JOIN (SELECT * FROM follows WHERE follow_index = ?) AS f_i
			ON f.follow_index = f_i.follower_index
        WHERE f.follower_index = ?
        ORDER BY f.follow_at DESC'
    );
    $follower_list_state -> execute(array(
        $_SESSION['user_index'],
        $_SESSION['user_index'],
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
    <!-- <link rel="stylesheet" href="./css/destyle.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/index.css"> -->
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/parts.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">

    <script src="js/script.js" defer></script>
    <script src="./follow/follow_index.js" defer></script>
    <script src="./group/group_function_index.js" defer></script>
    <title>トップページ</title>
</head>
<body>

    <!-- ヘッダー -->
    <header>
        <div class="headerWrapper">
            <!-- ロゴ -->
            <h1>
                <a href="./index.php">
                    <img src="" alt="ロゴ">
                    トップページ
                </a>
            </h1>
            <!-- ナビゲーション -->
            <nav class="nav">
                <a href="./user/search_user.php">
                    <img src="images/search.png"><br>
                    <div class="description">検索</div>
                    <span>検索</span>
                </a>
                <a href="./group/group_create.php">
                    <img src="images/groupMake.png"><br>
                    <div class="description">グループ作成</div>
                    <span>グループ作成</span>
                </a>
                <a href="./setting/setting.php">
                    <img src="images/setting.png"><br>
                    <div class="description">設定</div>
                    <span>設定</span>
                </a>
                <a href="./like/register_like.php">
                    <img src="images/favoriteRegister.png"><br>
                    <div class="description">好み登録</div>
                    <span>好み登録</span>
                </a>
                <a href="./login/logout.php">
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

        <div class="nav">
            <a href="./genre/register_A.php">分類登録(完成後削除)</a><br>
            <!-- <a href="./like/register_like.php">好み登録</a><br>
            <a href="./user/search_user.php">ユーザー検索</a>
            <a href="./group/group_create.php">グループ作成</a>
            <a href="./setting/setting.php">設定</a>
            <a href="./login/logout.php">ログアウト</a> -->
        </div>
        
    </header>

        

    <!-- プロフィール -->
    <div id="profile">
        <div class="center">
            <div class="left">
                <div class="profile">
                    <img src="./images/user/<?php  echo $user['picture'] != NULL ? $user['picture'] : 'default.png';?>" alt="profileImage">
                    <div>
                        <h2><?php echo $_SESSION['user_name'] ?></h2><br>
                        <p><?php echo $_SESSION['user_id'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- メニュー -->
    <nav id="tab">
        <div class="center">
            <div class="right">
                <ul>
                    <li class="favorites tabMenuOpen">
                        <span>favorite</span>
                        <img src="images/favorite.png" alt="favorites">
                    </li>
                    <li class="follows">
                        <span>follow</span>
                        <img src="images/follow.png" alt="follow">
                    </li>
                    <li class="followers">
                        <span>follower</span>
                        <img src="images/follower.png" alt="follower">
                    </li>
                    <li class="groups">
                        <span>group</span>
                        <img src="images/group.png" alt="group">
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- メイン -->
    <div id="responsiveWrapper">
        <main>
            
            <!-- <div>
                <img src="./images/user/<?php  echo $user['picture'] != NULL ? $user['picture'] : 'default.png';?>" alt="ユーザーイメージ">
                <br>
                <?php
                    echo $_SESSION['user_index'] . "<br>";
                    echo "user_name : " . $_SESSION['user_name'] . "<br>";
                    echo "user_id : " . $_SESSION['user_id'] . "<br>";
                ?>
            </div> -->


            <!-- 好み表示 -->
            <div  id="favorites" class="mouseArea tabOpen">
                <div class="catalog">
                    <?php foreach ($genre_list as $record) { ?>
                        <div class="favorite">
                            <div class="genreS"><?php echo $record['genre_c']; ?></div>
                            <div class="genreM"><?php echo $record['genre_b_name']; ?></div>
                            <div class="genreL"><?php echo $record['genre_a_name']; ?></div>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="addFavorite">
                    <a href="./like/register_like.php"><span>+</span></a>
                </div>
            </div>

            <!-- フォロー表示 -->
            <div id="follows" class="mouseArea oneColumn">
                <!-- フォロー-->
                <?php foreach ($follow_list as $record) { ?>
                    <div class="item follow">
                        <a class="follow-user box-user" href="./user/user_page.php?index=<?php echo $record['follower_index']; ?>">
                            <div class="info">
                                <img class="item-picture" src="./images/user/<?php  echo $record['picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="ユーザーイメージ">
                                <h2 class="item-name"><?php echo $record['name']; ?></h2>
                            </div>
                        </a>
                        <button class="btn-follow js-btn-follow js-follow-submit" value="<?php echo $record['follower_index'];?>">
                            unfollow
                        </button>
                    </div>
                <?php } ?>
            </div>

            <!-- フォロワー表示 -->
            <div id="followers" class="mouseArea oneColumn">
                <?php foreach ($follower_list as $record) { ?>
                    <div class="item follower">
                        <a class="follower-user box-user" href="./user/user_page.php?index=<?php echo $record['follow_index']; ?>">
                            <div class="info">
                                <img class="item-picture" src="./images/user/<?php  echo $record['picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="ユーザーイメージ">
                                <h2 class="item-name"><?php echo $record['name']; ?></h2>
                            </div>
                        </a>
                        <button class="btn-follow js-btn-follow js-follow-submit" value="<?php echo $record['follow_index'];?>">
                            <?php
                                if ($record['ff_index'] != NULL) {
                                    echo 'unfollow';
                                } else {
                                    echo 'follow';
                                }
                            ?>
                        </button>
                    </div>
                <?php } ?>
            </div>

                <!-- フォローモーダル -->
                <!-- <div class="follow-modal js-follow-modal">
                    <div class="modal-window">
                        <div class="box-modal-item">
                            <p class="modal-message">
                            </p>
                            <div class="box-modal-btn">
                                <button class="follow-submit js-follow-submit js-close-modal" value="<?php echo $_REQUEST['index'];?>">
                                </button>
                                <button class="close-modal js-close-modal">close</button>
                            </div>
                        </div>
                    </div>
                </div> -->



            <!-- グループ表示 -->
            <div id="groups" class="mouseArea twoColumn">
                <!-- 招待 -->
                <!-- invitation内が空の場合は非表示にする -->
                <?php if (!empty($group_inv_list)) { ?>
                <div class="invitations column">

                    <div class="heading">
                        <p>invitation</p>
                        <span class="js-slideBtn slideBtn">▲</span>
                    </div>

                    <div class="js-slideContent">
                    <?php foreach ($group_inv_list as $record) { ?>
                        <div class="item invitation">
                            <div class="inv_group js-inv-group">
                                <div class="info">
                                    <img src="./images/group/<?php  echo $record['group_picture'] != NULL ? $record['group_picture'] : 'default.png';?>" alt="グループイメージ">
                                    <h2><?php echo $record['group_name']; ?></h2>
                                </div>
                                <button class="enter-group-index js-enter-group-index" value="<?php echo $record['group_index']; ?>">参加する</button>
                            </div>
                        </div>
                    <?php } ?>
                    <div>
                </div>
                <?php } ?>

                <!-- グループ -->
                <div class="join-group-area">

                    <div class="heading">
                        <p>groups</p>
                        <span class="js-slideBtn slideBtn">▲</span>
                    </div>

                    <div class="js-slideContent">
                    <?php foreach ($group_list as $record) { ?>
                        <div class="item group">
                            <a class="join-group" href="./group/group_page.php?group_index=<?php echo $record['group_index']; ?>">
                                <div class="info">
                                    <img src="./images/group/<?php  echo $record['group_picture'] != NULL ? $record['group_picture'] : 'default.png';?>" alt="グループイメージ">
                                    <h2><?php echo $record['group_name']; ?></h2>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <footer>

    </footer>
</body>
</html>