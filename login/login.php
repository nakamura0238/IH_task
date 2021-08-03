<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    // 自動ログイン
    if ($_COOKIE['user_id'] != '' && $_COOKIE['password'] != '') {
        $_POST['id'] = $_COOKIE['user_id'];
        $_POST['password'] = $_COOKIE['password'];
        $_POST['save'] = 'on';
    }

    // POST送信されたら
    if (!empty($_POST)) {
        // 入力チェック
        if ($_POST['id'] != '' && $_POST['password'] != '') {
            $login = $db->prepare('SELECT * FROM users WHERE user_id = ? AND password = ?;');
            $login->execute(array(
                escape($_POST['id']),
                sha1(escape($_POST['password']))
            ));
            // ユーザー情報取得
            $user = $login->fetch();
            // print_r($user);

            // ログイン情報セット
            if ($user) {
                $index = $db->prepare('SELECT user_index, user_id FROM users WHERE user_id = ?;');
                $index->execute(array(
                    escape($_POST['id']),
                ));
                $userIndex = $index->fetch();

                $_SESSION['user_index'] = $userIndex['user_index'];
                $_SESSION['user_id'] = $userIndex['user_id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['time'] = time();

                // cookieへログイン情報を保存
                if ($_POST['save'] == 'on') {
                    setcookie('user_id', $_SESSION['user_id'], time()+60*60*24*14);
                    setcookie('password', $_POST['password'], time()+60*60*24*14);
                }

                // index.phpへ移動
                header('Location: ../index.php');
                exit();
            } else {
                $error['login'] = 'failed';
                echo 'ログインに失敗しました';
            }
        } else {
            $error['login'] = 'blank';
            echo '入力されていません';
        }
    }

    require('../functions/component.php');
?>

    <title>Document</title>
</head>
<body>
    <header>

    </header>

    <main>
        <form action="" method="POST">
            <label>
                <span>ID</span><br>
                <input type="text" name="id">
            </label>
            <br>
            <label>
                <div>パスワード</div>
                <input type="password" name="password">
            </label>
            <br>
            <button>ログイン</button>
        </form>
        <a href="../signup/signup.php">新規登録</a>
    </main>

    <footer>

    </footer>
</body>
</html>