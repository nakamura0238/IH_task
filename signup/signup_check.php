<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    // セッションに値がなければindex.phpへ
    if (!isset($_SESSION['join'])) {
        header('Location: ../index.php');
        exit();
    }

    if (!empty($_POST)) {
        try {
            $statement = $db->prepare('INSERT INTO users SET email = ?, user_id = ?, name = ?, password = ?, create_at = NOW();');
            $statement->execute(array(
                escape($_SESSION['join']['email']),
                escape($_SESSION['join']['id']),
                escape($_SESSION['join']['name']),
                sha1(escape($_SESSION['join']['password'])),
            ));

            unset($_SESSION['join']);

            header('Location: tutorial.php');
            exit();
        } catch (PDOException $e) {
            $error = '会員登録に失敗しました。管理者に連絡してください';
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
        <div>
            <form action="" method="post">
                <input type="hidden" name="action" value="submit">
                <div class="box-param">
                    <span>ニックネーム</span><br>
                    <p><?php echo $_SESSION['join']['name'] ?></p>
                </div>
                <div class="box-param">
                    <span>メールアドレス</span><br>
                    <p><?php echo $_SESSION['join']['email'] ?></p>
                </div>
                <div class="box-param">
                    <span>ユーザーID</span><br>
                    <p><?php echo $_SESSION['join']['id'] ?></p>
                </div>
                <div class="box-param">
                    <span>パスワード</span><br>
                    <p>【表示されません】</p>
                </div>
                <div class="box-btn">
                    <a href="./signup.php">戻る</a>
                    <button>登録</button>
                </div>
            </form>
        </div>
    </main>

    <footer>
        
    </footer>
</body>
</html>