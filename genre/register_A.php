<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');

    if (isset($_POST['genre_a'])) {
        $statement = $db -> prepare('INSERT INTO genre_a SET genre_a_name = ?;');
        $statement -> execute(array(
            $_POST['genre_a'],
        ));

        header('Location: ./register_A.php');
        exit();
    }

    $genres = $db -> query('SELECT * FROM genre_a;');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <?php require('../functions/header.php'); ?>

    <main>
        <div>
            <form action="" method="POST">
                <label>
                    <span>ジャンル名</span><br>
                    <input type="text" name="genre_a">
                </label>
                <button>登録</button>
            </form>
        </div>
        <a href="./register_B.php">中分類登録</a><br>

        <ul>
            <?php
                foreach ($genres as $genre) {
            ?>
                    <li><?php echo $genre['genre_a_name']; ?></li>
            <?php
                }
            ?>
        </ul>
    </main>

    <footer>

    </footer>

</body>
</html>