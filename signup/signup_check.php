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
</head>
<body>

    <div id="logo">
        <img src="images/Logo.png">
    </div>

    
        <div id="wrapper">
            <form action="" method="post">
                <main>
                    <input type="hidden" name="action" value="submit">

                    <div class="form-item">
                        <p class="formTop">Name</p><br>
                        <p class="form-style"><?php echo $_SESSION['join']['name'] ?></p>
                    </div>

                    <div class="form-item">
                        <p class="formTop">id</p><br>
                        <p class="form-style"><?php echo $_SESSION['join']['id'] ?></p>
                    </div>

                    <div class="form-item">
                        <p class="formTop">Password</p><br>
                        <p class="form-style">【表示されません】</p>
                    </div>

                    <div class="link">
                        <a href="./signup.php">登録しなおす</a>
                        <input type="submit" value="register">
                    </div>
                </main>
            </form>
        </div>

    <footer>
        
    </footer>
</body>
</html>