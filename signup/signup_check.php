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
            $statement = $db->prepare('INSERT INTO users SET user_id = ?, name = ?, password = ?, picture = ?, create_at = NOW();');
            $statement->execute(array(
                escape($_SESSION['join']['id']),
                escape($_SESSION['join']['name']),
                sha1(escape($_SESSION['join']['password'])),
                escape($_SESSION['join']['image']),
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

    <title>登録確認</title>

        <link rel="stylesheet" type="text/css" href="../css/reset.css">
        <link rel="stylesheet" type="text/css" href="../css/parts.css">
        <link rel="stylesheet" type="text/css" href="../css/common.css">
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <link rel="stylesheet" type="text/css" href="../css/responsive.css">
        
        <script src="https://code.jquery.com/jquery-2.1.0.min.js" ></script>
        <script src="js/script.js"></script>

</head>
<body>
    <header>
    </header>

        <div id="logo">
            <img src="">
        </div>

    <main>
        <div class="wrapper">
            <form action="" method="post">
                <input type="hidden" name="action" value="submit">

                    <div id="guide">
                        <p>以下の内容で登録します。</p>
                    </div>

                <div class="form-item">
                    <p class="formTop">Name</p>
                    <p class="form-style"><?php echo $_SESSION['join']['name'] ?></p>
                </div>

                <div class="form-item">
                    <p class="formTop">id</p>
                    <p class="form-style"><?php echo $_SESSION['join']['id'] ?></p>
                </div>

                <div class="form-item">
                    <p class="formTop">パスワード</p>
                    <p class="form-style">【表示されません】</p>
                </div>

                <div class="link">
                    <a href="./signup.php">登録しなおす</a>
                    <input type="submit" value="register">
                </div>
            </form>
        </div>
    </main>

    <footer>
        2021 &copy; GroupM.
    </footer>
</body>
</html>