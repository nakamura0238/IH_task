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

    // echo $_POST['genre-a'];
    // echo $_POST['genre-b'];
    print_r($_SESSION);

    // 大分類取得
    $genre_state = $db -> query('SELECT * FROM genre_a');
    $genres = $genre_state -> fetchall(PDO::FETCH_ASSOC);

    // 好み登録
    if ($_POST['genre-a'] != "" && $_POST['genre-b'] != "") {
        $genre_register = $db -> prepare('INSERT INTO likes SET user_index = ?, genre_a = ?, genre_b = ?, genre_c = ?;');
        $genre_register -> execute(array(
            $_SESSION['user_index'],
            $_POST['genre-a'],
            $_POST['genre-b'],
            $_POST['genre-c'],
        ));

        header('Location: ./register_like.php');
        exit();
    }

    // 好み抽出
    $genre_list_state = $db -> prepare(
    'SELECT likes.user_index, likes.genre_a, A.genre_a_name, likes.genre_b, B.genre_b_name, likes.genre_c FROM likes
    JOIN genre_a as A ON likes.genre_a = A.genre_a_index
    JOIN genre_b as B ON likes.genre_b = B.genre_b_index
    WHERE likes.user_index = ?
    ORDER BY likes.genre_a, likes.genre_b, likes.genre_c');
    $genre_list_state -> execute(array(
        $_SESSION['user_index']
    ));
    $genre_list = $genre_list_state -> fetchall(PDO::FETCH_ASSOC);

    require('../functions/component.php');
?>

    <script src="./register_like.js" defer></script>
    <link rel="stylesheet" href="../css/register_like.css">
    <title>Document</title>
</head>
<body>

    <?php require('../functions/header.php'); ?>

    <main>
        <div class="box-select">
            <form action="" method="POST" class="area-select" autocomplete="off">
                <div class="box-genre-all">
                    <div class="box-genre-ab">
                        <select name="genre-a" class="genre-a js-genre-a">
                            <option value="" disabled selected>大分類</option>
                            <?php foreach ($genres as $genre) { ?>
                                <option value="<?php echo $genre['genre_a_index']; ?>"><?php echo $genre['genre_a_name']; ?></option>
                            <?php } ?>
                        </select><!--
                        --><select name="genre-b" class="genre-b js-genre-b">
                            <option value="" disabled selected>中分類</option>
                        </select>
                    </div>
                    <input type="text" name="genre-c" class="genre-c js-genre-b">
                </div>
                <button type="submit" class="js-register">登録</button>
            </form>
            <!-- <button class="js-add-form">ADD Form</button> -->
        </div>

        <div class="genre-area">
            <?php foreach ($genre_list as $record) { ?>
                <div class="like-tag">
                    <div class="item-genre item-genre-a"><?php echo $record['genre_a_name']; ?></div>
                    <div class="item-genre item-genre-b"><?php echo $record['genre_b_name']; ?></div>
                    <div class="item-genre item-genre-c"><?php echo $record['genre_c']; ?></div>
                    <div></div>
                </div>
            <?php } ?>
        </div>

    </main>

    <footer>
        <?php

            // $search_word = $_POST['word'] . '%';
            $search_word = 'testaa';
            $statement = $db -> prepare('SELECT user_index, user_id, `name` FROM users WHERE user_id = ?;');
            $statement -> execute(array(
                $search_word
            ));

        ?>
        <pre>
            <?php
                $users = $statement -> fetch(PDO::FETCH_ASSOC);
                // var_dump($user);
                echo json_encode($users);
            ?>

        </pre>
    </footer>
</body>
</html>