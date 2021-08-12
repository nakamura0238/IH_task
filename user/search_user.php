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
            <h1 class="page-title">Search User</h1>
            <div class="search">
                <div class="form-item">
                    <p class="formLabel js-formLabel">Search</p>
                    <input class="js-search input-search form-style" type="text">
                </div>
                <button class="btn-search js-btn-search form-style">search</button>
            </div>

            <div class="result"></div>
        </main>
        
    </div>

    <footer>
    </footer>
</body>
</html>