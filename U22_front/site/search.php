<?php
    require('../../functions/dbconnect.php');
?>

<!DOCTYPE html>
<html lang="ja">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>ユーザー検索</title>

        <link rel="stylesheet" type="text/css" href="css/reset.css">
        <link rel="stylesheet" type="text/css" href="css/parts.css">
        <link rel="stylesheet" type="text/css" href="css/common.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/responsive.css">

        <script src="https://code.jquery.com/jquery-2.1.0.min.js" ></script>
        <script src="js/script.js"></script>
        <script src="search_user.js" defer></script>

    </head>

    <body id="search">

        <!-- ヘッダー -->
        <header>
            <div class="headerWrapper">
                <!-- 戻るボタン -->
                <a href="index.html">戻る</a>
                <!-- ロゴ -->
                <h1>
                    <a href="index.html">
                        <img src="images/Logo.png">
                    </a>
                </h1>
                <!-- ナビゲーション -->
                <nav class="nav">
                    <a href="search.html">
                        <img src="images/search.png"><br>
                        <div class="description">検索</div>
                        <span>検索</span>
                    </a>
                    <a href="makeGroup.html">
                        <img src="images/groupMake.png"><br>
                        <div class="description">グループ作成</div>
                        <span>グループ作成</span>
                    </a>
                    <a href="setting.html">
                        <img src="images/setting.png"><br>
                        <div class="description">設定</div>
                        <span>設定</span>
                    </a>
                    <a href="favoriteRegister.html">
                        <img src="images/favoriteRegister.png"><br>
                        <div class="description">好み登録</div>
                        <span>好み登録</span>
                    </a>
                    <a href="login.html">
                        <img src="images/logout.png"><br>
                        <div class="description">ログアウト</div>
                        <span>ログアウト</span>
                    </a>
                </nav>

                <!-- ハンバーガーメニューボタン -->
                <button type="button" class="navBtn js-navBtn">
                    <span class="btn-line"></span>
                </button>

            </div>
        </header>

        <!-- メイン -->
        <div id="wrapper">
                <main>

                    <!-- form -->
                    <div class="search">
                        <img src="images/search.png" alt="検索">
                        <div class="form-item">
                            <p class="formLabel js-formLabel">ユーザーID</p>
                            <input type="text" name="search" class="form-style js-search input-search">
                            <button class="form-style js-btn-search">検索</button>
                        </div>
                    </div>
                    
                    <!-- result -->
                    <div id="results" class="mouseArea oneColumn">

                        <div class="item">
                                    <div class="result"></div>
                                </div>
                        </div>

                    </div>

                </main>
        </div>


        <footer>
            2021 &copy; GroupM.
        </footer>
        
    </body>

</html>