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

    // 更新
    if (!empty($_POST)) {
        // 名前更新
        if (!empty($_POST['group_name'])) {
            $update_state = $db -> prepare('UPDATE groups SET group_name = ? WHERE group_index = ?;');
            $update_state -> execute(array(
                escape($_POST['group_name']),
                escape($_REQUEST['group_index'])
            ));
        }

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
                    escape($_REQUEST['group_index'])
                ));
            }
        }

        // グループページへ
        $url = './group_page.php?group_index='.$_REQUEST['group_index'];
        header("Location:".$url);
        exit();
    }

    // グループ情報
    $group_state = $db -> prepare('SELECT * FROM groups WHERE group_index = ?');
    $group_state -> execute(array(
        $_REQUEST['group_index']
    ));
    $group = $group_state -> fetch(PDO::FETCH_ASSOC);

    // 参加ユーザー
    $group_user_state = $db -> prepare(
        'SELECT *
        FROM group_user AS g_u
        JOIN users AS u 
            ON g_u.user_index = u.user_index
        WHERE group_index = ?'
    );
    $group_user_state -> execute(array(
        $_REQUEST['group_index']
    ));
    $group_user = $group_user_state -> fetchall(PDO::FETCH_ASSOC);

    // 招待ユーザー
    $group_inv_state = $db -> prepare(
        'SELECT *
        FROM groups_invitation AS g_i
        JOIN users AS u
            ON g_i.inv_user_index = u.user_index
        WHERE group_index = ?'
    );
    $group_inv_state -> execute(array(
        $_REQUEST['group_index']
    ));
    $group_inv = $group_inv_state -> fetchall(PDO::FETCH_ASSOC);

    // 招待可能ユーザー
    $invited_state = $db -> prepare(
        'SELECT ff.user_index, ff.user_id, ff.name, ff.picture, ff.follow_at, g_u.gu_index, g_i.invitation_index
        FROM (SELECT user_index, user_id, name, picture, follow_at
            FROM (SELECT follow_index, follower_index, follow_at FROM follows WHERE follow_index = ?) AS f1 
            LEFT OUTER JOIN (SELECT follow_index FROM follows WHERE follower_index = ?) AS f2 
                ON f1.follower_index = f2.follow_index
            JOIN users AS u
                ON f2.follow_index = u.user_index
            WHERE f2.follow_index IS NOT NULL
            ORDER BY follow_at DESC) AS ff
        LEFT OUTER JOIN (SELECT * FROM group_user WHERE group_index = ?) AS g_u
            ON ff.user_index = g_u.user_index
        LEFT OUTER JOIN (SELECT * FROM groups_invitation WHERE group_index = ?) AS g_i
            ON ff.user_index = g_i.inv_user_index'
    );
    $invited_state -> execute(array(
        $_SESSION['user_index'],
        $_SESSION['user_index'],
        $_REQUEST['group_index'],
        $_REQUEST['group_index'],
    ));
    $invited = $invited_state -> fetchall(PDO::FETCH_ASSOC);

    // print_r($invited);


    require('../functions/component.php');
?>

    <script src="./group_function.js" defer></script>
    <title>Document</title>
</head>
<body>

    <?php require('../functions/header.php'); ?>

    <main>

        <p>グループ情報更新</p>

        <form action="./group_setting.php?group_index=<?php echo $_REQUEST['group_index']; ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- 画像を登録 -->
            <input type="file" name="picture">

            <!-- グループ名変更 -->
            <input type="text" name="group_name" placeholder="<?php echo $group['group_name'] ?>">
            <button>変更</button>

        </form>

        <div>
            <!-- 招待モーダル -->
            <div>
                <?php foreach ($invited as $record) { ?>
                    <div>
                        <div class="follower-user box-user">
                            <img class="item-picture" src="../images/user/<?php  echo $record['picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
                            <div class="flex">
                                <p class="item-name"><?php echo $record['name']; ?></p>
                            </div>
                        </div>
                        <?php
                            if ($record['gu_index'] == NULL && $record['invitation_index'] == NULL) {
                                echo "<button>招待</button>";
                            } elseif ($record['gu_index'] != NULL && $record['invitation_index'] == NULL) {
                                echo "<span>参加済</span>";
                            } elseif ($record['gu_index'] == NULL && $record['invitation_index'] != NULL) {
                                echo "<span>招待中</span>";
                            }
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div>
            <!-- メンバーリスト -->
            <p>member------------</p>
            <div>
                <?php foreach ($group_user as $record) { ?>
                    <div>
                        <div class="follower-user box-user">
                            <img class="item-picture" src="../images/user/<?php  echo $record['picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
                            <div class="flex">
                                <p class="item-name"><?php echo $record['name']; ?></p>
                            </div>
                        </div>
                        <button>delete</button>
                    </div>
                <?php } ?>
            </div>

            <!-- 招待メンバーリスト -->
            <p>invitation----------</p>
            <div>
                <?php foreach ($group_inv as $record) { ?>
                    <div>
                        <div class="inv-user box-user" >
                            <img class="item-picture" src="../images/user/<?php  echo $record['picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
                            <div class="flex">
                                <p class="item-name"><?php echo $record['name']; ?></p>
                            </div>
                        </div>
                        <button>delete</button>
                    </div>
                <?php } ?>
            </div>
        </div>


    </main>

    <footer>
    </footer>
</body>
</html>