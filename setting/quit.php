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

    <link rel="stylesheet" href="../css/leave.css">
    <script src="./setting_function.js" defer></script>
    <title>Document</title>
</head>
<body>

    <?php require('../functions/header.php'); ?>

    <main>
        <div>
            <img src="../images/user/<?php  echo $user['picture'] != NULL ? $user['picture'] : 'default.png';?>" alt="ユーザーイメージ" height="100">
            <br>
            <?php
                echo "user_name : " . $_SESSION['user_name'] . "<br>";
                echo "user_id : " . $_SESSION['user_id'] . "<br>";
            ?>
        </div>
        <p>退会</p>

        <label>
            <span>確認のためパスワードを入力してください</span><br>
            <p class="js-password-alert"></p>
            <input class="password js-password" type="password" name="password">
        </label>
        <button class="btn-quit js-btn-quit">確認</button>

        <div class="quit-modal js-quit-modal">
                <div class="modal-window">
                    <div class="box-modal-item">
                        <p class="modal-message">
                            退会処理を行います<br>アカウントは即時削除されます
                        </p>
                        <div class="box-modal-btn">
                            <a class="quit-submit js-quit-submit js-close-modal" href="./thanks.php">
                                Quit
                            </a>
                            <button class="close-modal js-close-modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


    </main>

    <footer>

    </footer>
</body>
</html>