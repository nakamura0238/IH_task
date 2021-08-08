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
    <title>検索</title>
</head>
<body id="search">

    <?php require('../functions/header.php'); ?>

    <div id="wrapper">
        
            <main>
                <div class="search">
                    <div class="form-item">
                        <p class="formLabel js-formLabel">Search</p>
                        <input class="js-search input-search form-style" type="text">
                    </div>
                    <button class="js-btn-search">検索</button>
                </div>

                <div>
                    <a href="./genre/register_A.php">ジャンル登録</a>
                </div>

                <div class="result"></div>
            </main>
        
    </div>

    <footer>
    </footer>
</body>
</html>