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
            }
        } else {
            $error['login'] = 'blank';
        }
    }

    require('../functions/component.php');
?>

    <title>新規登録</title>
</head>
<body>

    <div id="logo">
        <img src="../images/Logo.png">
    </div>

    <div id="wrapper">
        <form action="" method="POST">
            <main>
                <div class="form-item">
                    <p class="formLabel js-formLabel">ID</p>
                    <input type="text" name="id" class="form-style">
                </div>

                <div class="form-item">
                    <p class="formLabel js-formLabel">Password</p>
                    <input type="password" name="password" class="form-style">
                </div>

                <div id="auto_login">
                        <label><input type="checkbox" name="save" id="checkbox">
                        <span class="tag">次回以降自動でログインする</span></label>
                </div>

                <div class="link">
                    <div>
                        <a href="../signup/signup.php">新規登録</a>
                    </div>
                    <input type="submit" value="Log In">
                </div>

            </main>
        </form>
    </div>

    <footer>

    </footer>
</body>
</html>