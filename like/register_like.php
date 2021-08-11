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

    // 大分類取得
    $genre_state = $db -> query('SELECT * FROM genre_a');
    $genres = $genre_state -> fetchall(PDO::FETCH_ASSOC);

    // 好み登録
    if ($_POST['genre-a'] != "" && $_POST['genre-b'] != "") {
        $genre_register = $db -> prepare('INSERT INTO likes SET user_index = ?, genre_a = ?, genre_b = ?, genre_c = ?;');
        $genre_register -> execute(array(
            $_SESSION['user_index'],
            escape($_POST['genre-a']),
            escape($_POST['genre-b']),
            escape($_POST['genre-c']),
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
<body id="favoriteRegister">

    <?php require('../functions/header.php'); ?>

    <div id="wrapper">
        <form action="" method="POST" class="area-select" autocomplete="off">
            <main>
                <div class="category">
                    <div class="form-item categoryL js-categoryL">
                        <p class="formLabel js-formLabel">CategoryL</p>
                        <select name="genre-a" class="genre-a js-genre-a">
                            <option value="" disabled selected></option>
                            <?php foreach ($genres as $genre) { ?>
                                <option value="<?php echo $genre['genre_a_index']; ?>"><?php echo $genre['genre_a_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-item categoryM js-categoryM">
                        <p class="formLabel js-formLabel">CategoryM</p>
                        <select name="genre-b" class="genre-b js-genre-b">
                            <option value="" disabled selected></option>
                        </select>
                    </div>

                    <div class="clear-fix"></div>
                    <!-- 小分類 -->
                    <div class="form-item categoryS js-categoryS">
                        <p class="formLabel js-formLabel">CategoryS</p>
                        <input type="text" name="genre-c" id="name" class="form-style genre-c js-genre-b" autocomplete="off"/>
                    </div>
                    
                </div>


            </main>

            <div class="link">
                <button class="form-style js-register">submit</button>
            </div>
            
        </form>
    </div>

    <div id="responsiveWrapper">
        <div id="favorites">
            <div class="catalog">
                <?php foreach ($genre_list as $record) { ?>
                    <div class="favorite">
                        <div class="genre-item item-genre-c genreS"><?php echo $record['genre_c']; ?></div>
                        <div class="genre-item item-genre-b genreM"><?php echo $record['genre_b_name']; ?></div>
                        <div class="genre-item item-genre-a genreL"><?php echo $record['genre_a_name']; ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <footer>
    </footer>
</body>
</html>