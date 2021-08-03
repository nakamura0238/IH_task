<?php

    // 使用しない！！！！！

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
        </div>


    <?php if ($group_inv_list) { ?>
        <p>招待されているグループ</p>
        <div class="inv-group-area">
            <?php foreach ($group_inv_list as $record) { ?>
                <div class="inv_group js-inv-group">
                    <img src="../images/group/<?php  echo $record['group_picture'] != NULL ? $record['group_picture'] : 'default.png';?>" alt="グループイメージ" height="100">
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
                    <img src="../images/group/<?php  echo $record['group_picture'] != NULL ? $record['group_picture'] : 'default.png';?>" alt="グループイメージ" height="100">
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