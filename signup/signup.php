<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    if (!empty($_POST)) {

        // ユーザーネーム
        if ($_POST['name'] == '') {
            $error['name'] = 'blank';
        }
        // パスワード不一致
        if ($_POST['password'] != $_POST['password_re']) {
            $error['password'] = 'mismatch';
        }
        // パスワード文字数
        if (strlen($_POST['password']) < 4) {
            $error['password'] = 'length';
        }
        // パスワード未入力
        if ($_POST['password'] == '') {
            $error['password'] = 'blank';
        }

        // ファイルの拡張子
        // $filename = $_FILES['image']['name'];

        // if (!empty($filename)) {
        //     $ext = substr($filename, -3);
        //     if ($ext != 'jpg' && $ext != 'png') {
        //         $error['image'] = 'type';
        //     }
        // }

        // 入力情報にエラーがなければ
        if (empty($error)) {
            // ID重複チェック
            $user_id = $db -> prepare('SELECT COUNT(*) as id_cnt FROM users WHERE user_id = ?;');
            $user_id -> execute(array(
                escape($_POST['id'])
            ));
            $record_id = $user_id -> fetch();
            if ($record_id['id_cnt'] > 0) {
                $error['id'] = 'duplicate';
            }
        }

        if (empty($error)) {
            // if (!empty($filename)) {
            //     $picture = date('YmdHis') . $_FILES['picture']['name'];
            //     move_uploaded_file($_FILES['picture']['tmp_name'], '../images/user/' . $picture);
            // }
            $_SESSION['join'] = $_POST;
            // $_SESSION['join']['image'] = $picture;

            header('Location:signup_check.php');
            exit();
        }

    }

    if ($_REQUEST['action'] == 'rewrite') {
        unlink("../user_images/{$_SESSION['join']['image']}");
        $_POST = $_SESSION['join'];
        $error['rewrite'] = true;
    }

    require('../functions/component.php');
?>

    <title>Document</title>

    <script src="" defer></script>

</head>
<body>
    <header>

    </header>

    <main>
        <div>
            <p><?php if ($error['email'] == 'duplicate') { echo '登録済みです'; } ?></p>
            <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                <label>
                    <span>ニックネーム</span><br>
                    <input type="text" name="name">
                </label>
                <br>
                <label>
                    <span>ユーザーID</span><br>
                    <input type="text" name="id">
                </label>
                <br>
                <label>
                    <span>パスワード</span><br>
                    <input type="password" name="password">
                </label>
                <br>
                <label>
                    <span>パスワード確認</span><br>
                    <input type="password" name="password_re">
                </label>
                <br>
                <button>確認</button>
            </form>
            <a href="../login/login.php">ログイン</a>
        </div>
    </main>

    <footer>
        
    </footer>
</body>
</html>