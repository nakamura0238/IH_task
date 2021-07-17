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

        <form action="POST">
            <label>
                <span>Name</span><br>
                <input type="text" name="name">
            </label>
            <br>
            <label>
                <span>Email</span><br>
                <input type="text" name="email">
            </label>
            <br>
            <label>
                <span>ID</span><br>
                <input type="text" name="id">
            </label>
            <br>
            <label>
                <span>Old Password</span><br>
                <input type="password" name="old_pass">
            </label>
            <br>
            <label>
                <span>New Password</span><br>
                <input type="password" name="new_pass">
            </label>
            <br>
            <label>
                <span>New Password Check</span><br>
                <input type="password" name="new_pass_check">
            </label>
        </form>

        <a href="./quit.php">退会</a>


    </main>

    <footer>

    </footer>
</body>
</html>