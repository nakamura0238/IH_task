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

    print_r($_POST);

    $error = array();
    // ユーザー情報更新
    if (!empty($_POST)) {
        $update_state = $db -> prepare('SELECT * FROM users WHERE user_index = ?');
        $update_state -> execute(array(
            $_SESSION['user_index'],
        ));
        $update = $update_state -> fetch(PDO::FETCH_ASSOC);




        // 名前
        if ($_POST['name'] != '') {
            $update['name'] = $_POST['name'];
        }

        // メールアドレス
        if ($_POST['email'] != '') {
            $email_check = $db -> prepare('SELECT count(*) AS email_cnt FROM users WHERE email = ?');
            $email_check -> execute(array(
                $_POST['email'],
            ));
            $email = $email_check -> fetch(PDO::FETCH_ASSOC);
            print_r($email);
            if ($email['email_cnt'] == 0) {
                $update['email'] = $_POST['email'];
            } else {
                $error['email'] = 'duplicate';
            }
        }

        // ID
        if ($_POST['id'] != '') {
            $id_check = $db -> prepare('SELECT count(*) AS id_cnt FROM users WHERE user_id = ?');
            $id_check -> execute(array(
                $_POST['id'],
            ));
            $id = $id_check -> fetch(PDO::FETCH_ASSOC);
            if ($id['id_cnt'] == 0) {
                $update['user_id'] = $_POST['id'];
            } else {
                $error['id'] = 'duplicate';
            }
        }

        // パスワード
        if (!empty($_POST['old_pass'])) {
            if ($update['password'] == sha1($_POST['old_pass'])) {
                // 一致確認
                if ($_POST['new_pass'] == $_POST['new_pass_check']) {
                    // 文字数確認
                    if (strlen($_POST['new_pass']) > 4 && strlen($_POST['new_pass_check']) > 4) {
                        $update['password'] = sha1($_POST['new_pass']);
                    } else {
                        $error['pass'] = 'short';
                    }
                } else {
                    $error['pass'] = 'mismatch';
                }
            } else {
                // パスワード違い
                $error['pass'] = 'wrong';
            }
        }

        print_r($update);

        if (empty($error)) {
            $new_data_state = $db -> prepare('UPDATE users SET name = ?, email = ?, user_id = ?, password = ? WHERE user_index = ?');
            $new_data_state -> execute(array(
                $update['name'],
                $update['email'],
                $update['user_id'],
                $update['password'],
                $_SESSION['user_index'],
            ));

            $_SESSION['user_id'] = $update['user_id'];
            $_SESSION['user_name'] = $update['name'];
            $_SESSION['email'] = $update['email'];

            header('Location: ./setting.php');
            exit();
        }

    }

    // ユーザー情報抽出
    $user_state = $db -> prepare('SELECT * FROM users WHERE user_index = ?;');
    $user_state -> execute(array(
        $_SESSION['user_index']
    ));
    $user = $user_state -> fetch(PDO::FETCH_ASSOC);

    require('../functions/component.php');
?>

    <script src="./setting_function.js" defer></script>
    <title>Document</title>
</head>
<body>

    <?php require('../functions/header.php'); ?>

    <main>
        <a href="../login/logout.php">ログアウト</a>
        <div>
            <img src="../images/user/<?php  echo $user['picture'] != NULL ? $user['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
            <br>
            <?php
                echo "user_name : " . $_SESSION['user_name'] . "<br>";
                echo "user_id : " . $_SESSION['user_id'] . "<br>";
                echo "email : " . $_SESSION['email'] . "<br>";
            ?>
        </div>

        <p>設定ページ</p>

        <form action="" method="POST" autocomplete="off">
            <label>
                <span>Name</span><br>
                <input type="text" name="name" placeholder="<?php echo $_SESSION['user_name']; ?>" value="<?php if (!empty($_POST['name'])) { echo $_POST['name']; } ?>">
            </label><br>
            <label>
                <span>Email</span><br>
                <input type="email" name="email" placeholder="<?php echo $_SESSION['email']; ?>" value="<?php if (!empty($_POST['email'])) { echo $_POST['email']; } ?>">
            </label><br>
            <label>
                <span>ID</span><br>
                <input type="text" name="id" placeholder="<?php echo $_SESSION['user_id']; ?>" value="<?php if (!empty($_POST['user_id'])) { echo $_POST['user_id']; } ?>">
            </label><br>
            <label>
                <span>現在のパスワード</span><br>
                <input type="password" name="old_pass">
            </label><br>
            <label>
                <span>新しいパスワード</span><br>
                <input type="password" name="new_pass" placeholder="5文字以上">
            </label>
            <br>
            <label>
                <span>新しいパスワードの確認</span><br>
                <input type="password" name="new_pass_check" placeholder="5文字以上">
            </label>
            <button>送信</button>
        </form>

        <a href="./quit.php">退会</a>


    </main>

    <footer>

    </footer>
</body>
</html>