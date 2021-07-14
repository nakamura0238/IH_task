<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    // 所属していないグループにアクセスした時
    

    if (isset($_SESSION['email']) && $_SESSION['time'] + 3600 > time()) {
        // 接続時間更新
        $_SESSION['time'] = time();
    } else {
        header('Location: ../login/login.php');
        exit();
    }

    // ユーザー抽出

    // ユーザー数に合わせたSQLをどうするか

    // 好み抽出

    require('../functions/component.php');
?>

    <script src="./group_function.js" defer></script>
    <title>Document</title>
</head>
<body>

    <?php require('../functions/header.php'); ?>

    <main>
        <div>
            <a href="./group_create.php">新規グループ作成</a>
            <button>設定</button>
        </div>


        <?php if ($group_inv_list) { ?>
            <p>招待されているグループ</p>
            <div class="inv-group-area">
                <?php foreach ($group_inv_list as $record) { ?>
                    <div class="inv_group js-inv-group">
                        <img src="../images/group/<?php  echo $record['group_picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="グループイメージ" height="100">
                        <p class="item-group">name:<?php echo $record['group_name']; ?></p>
                        <button class="enter-group-index js-enter-group-index" value="<?php echo $record['group_index']; ?>">参加する</button>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>


        <?php if ($group_list) { ?>
            <p>参加しているグループ</p>
            <div class="join-group-area">
                <?php foreach ($group_list as $record) { ?>
                    <a class="join-group" href="./group_page.php?group_index=<?php echo $record['group_index']; ?>">
                        <img src="../images/group/<?php  echo $record['group_picture'] != NULL ? $record['picture'] : 'default.png';?>" alt="グループイメージ" height="100">
                        <p class="item-group">name:<?php echo $record['group_name']; ?></p>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>

    </main>

    <footer>
    </footer>
</body>
</html>