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

    // 所属していないグループにアクセスした時
    $check_group_state = $db -> prepare('SELECT count(*) AS check_num FROM group_user WHERE group_index = ? AND user_index = ?');
    $check_group_state -> execute(array(
        escape($_REQUEST['group_index']),
        $_SESSION['user_index'],
    ));
    $check_group = $check_group_state -> fetch(PDO::FETCH_ASSOC);

    if ($check_group['check_num'] < 1) {
        header('Location: ./group_top.php');
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
        

        // グループページへ
        $url = './group_page.php?group_index='.$_REQUEST['group_index'];
        header("Location:".$url);
        exit();
    }

    $group_state = $db -> prepare('SELECT * FROM groups WHERE group_index = ?');
    $group_state -> execute(array(
        $_REQUEST['group_index']
    ));
    $group = $group_state -> fetch(PDO::FETCH_ASSOC);

    require('../functions/component.php');
?>

    <script src="./group_function.js" defer></script>
    <title>Document</title>
</head>
<body>

    <?php require('../functions/header.php'); ?>

    <main>

        <p>グループ情報更新</p>

        <form action="./group_setting.php?group_index=<?php echo $_REQUEST['group_index']; ?>" method="POST" enctype="multipart/form-data">
            <!-- 画像を登録 -->
            <input type="file" name="image">

            <!-- グループ名変更 -->
            <input type="text" name="group_name" placeholder="<?php echo $group['group_name'] ?>">
            <button>変更</button>

        </form>


    </main>

    <footer>
    </footer>
</body>
</html>