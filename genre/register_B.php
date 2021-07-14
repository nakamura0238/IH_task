<?php

    ini_set('display_errors', "On");

    require('../functions/dbconnect.php');

    if (isset($_POST['genre_b'])) {
        $statement = $db -> prepare('INSERT INTO genre_b SET in_genre_a = ?, genre_b_name = ?;');
        $statement -> execute(array(
            $_POST['in_genre'],
            $_POST['genre_b'],
        ));

        header('Location: ./register_B.php');
        exit();
    }

    $genres_a = $db -> query('SELECT * FROM genre_a;');
    $genres_b = $db -> query('SELECT * FROM genre_a JOIN genre_b ON genre_a.genre_a_index = genre_b.in_genre_a ORDER BY in_genre_a ASC, genre_b_index ASC;');

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
                    <span>大分類</span><br>
                    <select name="in_genre">
                        <?php
                            foreach($genres_a as $genre) {
                        ?>
                                <option value="<?php echo $genre['genre_a_index']; ?>"><?php echo $genre['genre_a_name']; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </label>
                <br>
                <label>
                    <span>ジャンル名</span><br>
                    <input type="text" name="genre_b">
                </label>
                <button>登録</button>
            </form>
        </div>
        <a href="./register_A.php">大分類登録</a><br>

        <ul>
            <?php
                foreach ($genres_b as $genre) {
            ?>
                    <li><?php echo $genre['genre_a_name'] . "：" . $genre['genre_b_name'] ; ?></li>
            <?php
                }
            ?>
        </ul>
    </main>

    <footer>

    </footer>

</body>
</html>