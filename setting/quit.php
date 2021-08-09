<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');
    require('../functions/function.php');

    session_start();

    if (isset($_SESSION['user_id']) && $_SESSION['time'] + 3600 > time()) {
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
<body id="leave">

    <?php require('../functions/header.php'); ?>

    <div id="wrapper">
        <main>

            <div id="guide">
                <p>確認のためパスワードを入力してください</p>
            </div>

            <div class="form-item">
                
                <p class="formLabel js-formLabel">Password</p>
                <input class="password js-password form-style" type="password" name="password">
            </div>

            <div class="link">
                <button class="form-style btn-quit js-btn-quit">check</button>
            </div>


        </main>
    </div>

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