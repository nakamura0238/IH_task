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

        // 画像更新
        $filename = $_FILES['picture']['name'];
        if (!empty($filename)) {
            echo $filename;
            $ext = substr($filename, -3);
            if ($ext != 'jpg' && $ext != 'png' && $ext != 'gif') {
                $error['picture'] = 'type';
            } else {
                echo $filename;
                $picture = date('YmdHis') . $_FILES['picture']['name'];
                move_uploaded_file($_FILES['picture']['tmp_name'], '../images/group/' . $picture);
                $update_image_state = $db -> prepare('UPDATE groups SET group_picture = ? WHERE group_index = ?;');
                $update_image_state -> execute(array(
                    $picture,
                    $group_index['group_index']
                ));
            }
        }
        
        // グループへ招待
        foreach ($_POST['user'] as $user) {
            $group_inv_state = $db -> prepare('INSERT INTO groups_invitation SET group_index = ?, inv_user_index  = ?');
            $group_inv_state -> execute(array(
                $group_index['group_index'],
                $user
            ));
        }

        
        header('Location: ../index.php');
        exit();
    }


    // フォロー抽出
    // $follow_list_state = $db -> prepare(
    //     'SELECT f.ff_index, f.follower_index, u.user_id, u.name, u.picture
    //     FROM follows AS f
    //     JOIN users AS u ON f.follower_index = u.user_index
    //     WHERE f.follow_index = ?
    //     ORDER BY f.follow_at DESC'
    // );
    // $follow_list_state -> execute(array(
    //     $_SESSION['user_index']
    // ));
    // $follow_list = $follow_list_state -> fetchall(PDO::FETCH_ASSOC);

    // 相互フォロー
    $follow_list_state = $db -> prepare(
        'SELECT user_index, user_id, name, picture, follow_at
        FROM (SELECT follow_index, follower_index, follow_at FROM follows WHERE follow_index = ?) AS f1 
        LEFT OUTER JOIN (SELECT follow_index FROM follows WHERE follower_index = ?) AS f2 
            ON f1.follower_index = f2.follow_index
        JOIN users AS u
            ON f2.follow_index = u.user_index
        WHERE f2.follow_index IS NOT NULL
        ORDER BY follow_at DESC'
    );
    $follow_list_state -> execute(array(
        $_SESSION['user_index'],
        $_SESSION['user_index'],
    ));
    $follow_list = $follow_list_state -> fetchall(PDO::FETCH_ASSOC);


    require('../functions/component.php');
?>

    <!-- <script src="./group_function.js" defer></script> -->
    <title>Document</title>
</head>
<body id="makeGroup">

    <?php require('../functions/header.php'); ?>

    <div id="responsiveWrapper">
        <main class="clearfix">
            <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">

            <div class="profileSetting">
                    <!-- image -->
                    <div class="imgUpLoad">
                        <img class="js-setting" src="../images/group/default.png" alt="profileImg">
                        <input type="file" name="picture" accept=".jpg, .jpeg, .png, .gif">
                    </div>
                    <!-- name -->
                    <div class="form-item">
                        <p class="formLabel js-formLabel">Group Name</p>
                        <input type="text" name="group_name" class="form-style js-group-name" >
                    </div>
                </div>

                <div class="buttonArea">
                    <input type="submit" value="make">
                </div>

                
                <div class="js-follow-user follow-user">
                    <?php $i = 1; foreach ($follow_list as $record) {?>
                        <div class ="item">
                            <div class="info">
                                <img src="../images/user/<?php  echo $record['picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
                                <h2 class="item-follow"><?php echo $record['name']; ?></h2>
                            </div>
                            <input type="checkbox" name="user[]" value="<?php echo $record['user_index'] ?>">
                        </div>
                    <?php $i++;} ?>
                </div>
            </form>
        </main>
    </div>

    <footer>
    </footer>
</body>
</html>