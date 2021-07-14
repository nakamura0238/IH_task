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
        <?php

            // $search_word = $_POST['word'] . '%';
            $search_word = 'testaa';
            $statement = $db -> prepare('SELECT user_index, user_id, `name` FROM users WHERE user_id = ?;');
            $statement -> execute(array(
                $search_word
            ));

        ?>
        <pre>
            <?php
                $users = $statement -> fetch(PDO::FETCH_ASSOC);
                // var_dump($user);
                echo json_encode($users);
            ?>

        </pre>
    </footer>
</body>
</html>