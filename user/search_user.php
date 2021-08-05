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

    require('../functions/component.php');
?>

    <script src="./search_user.js" defer></script>
    <title>Document</title>
</head>
<body>

    <?php require('../functions/header.php'); ?>

    <main>
        <div>
            <label>
                <span>ユーザー検索</span><br>
                <input class="js-search input-search" type="text" placeholder="ユーザーID">
            </label>
            <button class="js-btn-search">検索</button>
        </div>

        <div>
            <a href="./genre/register_A.php">ジャンル登録</a>
        </div>

        <div class="result"></div>
    </main>

    <footer>
    </footer>
</body>
</html>