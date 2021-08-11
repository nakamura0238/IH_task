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

    $error = array();
    // ユーザー情報更新
    if (!empty($_POST)) {
        $update_state = $db -> prepare('SELECT * FROM users WHERE user_index = ?');
        $update_state -> execute(array(
            $_SESSION['user_index'],
        ));
        $update = $update_state -> fetch(PDO::FETCH_ASSOC);

        // 画像更新
        $filename = $_FILES['picture']['name'];
        if (!empty($filename)) {
            $ext = substr($filename, -3);
            if ($ext != 'jpg' && $ext != 'png' && $ext != 'gif') {
                $error['picture'] = 'type';
            } else {
                $picture = date('YmdHis') . $_FILES['picture']['name'];
                move_uploaded_file($_FILES['picture']['tmp_name'], '../images/user/' . $picture);
                $update_image_state = $db -> prepare('UPDATE users SET picture = ? WHERE user_index = ?;');
                $update_image_state -> execute(array(
                    $picture,
                    $_SESSION['user_index']
                ));
            }
        }

        // 名前
        if ($_POST['name'] != '') {
            $update['name'] = escape($_POST['name']);
        }

        // ID
        if ($_POST['id'] != '') {
            $id_check = $db -> prepare('SELECT count(*) AS id_cnt FROM users WHERE user_id = ?');
            $id_check -> execute(array(
                escape($_POST['id']),
            ));
            $id = $id_check -> fetch(PDO::FETCH_ASSOC);
            if ($id['id_cnt'] == 0) {
                $update['user_id'] = escape($_POST['id']);
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
                    if (strlen($_POST['new_pass']) >= 4 && strlen($_POST['new_pass_check']) >= 4) {
                        $update['password'] = sha1(escape($_POST['new_pass']));
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

        if (empty($error)) {
            $new_data_state = $db -> prepare('UPDATE users SET name = ?, user_id = ?, password = ? WHERE user_index = ?');
            $new_data_state -> execute(array(
                $update['name'],
                $update['user_id'],
                $update['password'],
                $_SESSION['user_index'],
            ));

            $_SESSION['user_id'] = $update['user_id'];
            $_SESSION['user_name'] = $update['name'];

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
<body id="setting">

    <?php require('../functions/header.php'); ?>

        <div id="wrapper">
            <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                <main class="clearfix">
                    <p>設定ページ</p>
                    <div class="imgUpLoad">
                        <img class="js-setting" src="../images/user/<?php  echo $user['picture'] != NULL ? $user['picture'] : 'default.png';?>" alt="profileImg">
                        <input type="file" name="picture" accept=".jpg, .jpeg, .png, .gif">
                    </div>

                    <div class="form-item">
                        <p class="formLabel js-formLabel formTop">Name</p>
                        <input type="text" name="name" class="form-style" placeholder="<?php echo $_SESSION['user_name']; ?>" value="<?php if (!empty($_POST['name'])) { echo $_POST['name']; } ?>">
                    </div>

                    <div class="form-item">
                        <p class="formLabel js-formLabel formTop">id</p>
                        <input type="text" name="id" class="form-style" placeholder="<?php echo $_SESSION['user_id']; ?>" value="<?php if (!empty($_POST['user_id'])) { echo $_POST['user_id']; } ?>">
                    </div>

                    <div class="form-item">
                        <p class="formLabel js-formLabel">Password Now</p>
                        <input type="password" name="old_pass" class="form-style" />
                    </div>

                    <div class="form-item">
                        <p class="formLabel js-formLabel">New Password Check</p>
                        <input type="password" name="new_pass" class="form-style" />
                    </div>

                    <div class="form-item">
                        <p class="formLabel js-formLabel">New Password Check</p>
                        <input type="password" name="new_pass_check" class="form-style" />
                    </div>

                    <div class="link">
                        <a href="./quit.php">
                            <button class="form-style leaveBtn alert-color" type="button">quit</button>
                        </a>
                        <input type="submit" value="check">
                    </div>

                </main>
            </form>
        </div>




    <footer>

    </footer>
</body>
</html>