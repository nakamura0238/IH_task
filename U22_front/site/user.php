<!DOCTYPE html>
<html lang="ja">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>トップページ</title>

        <link rel="stylesheet" type="text/css" href="css/reset.css">
        <link rel="stylesheet" type="text/css" href="css/parts.css">
        <link rel="stylesheet" type="text/css" href="css/common.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/responsive.css">

        <script src="https://code.jquery.com/jquery-2.1.0.min.js" ></script>
        <script src="js/script.js"></script>

    </head>

    <body id="user">

        <!-- ヘッダー -->
        <header>
            <div class="headerWrapper">
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


        <!-- プロフィール -->
        <div id="profile">
            <div class="center">
                <div class="left">
                    <div class="profile">
                        <img src="images/no_img.png" alt="profileImage">
                        <div>
                            <h2>Name</h2><br>
                            <p>@ID</p>
                        </div>
                    </div>
                    <!-- フォローボタン -->
                    <button class="form-style">フォロー</button>
                </div>
            </div>
        </div>


        <!-- 見出し -->
        <div class="favoriteHeader">
            <div class="center">
                <div class="right">
                    <h2>
                        My Favorites
                    </h2>
                </div>
            </div>
        </div>


        <!-- メイン -->
        <div id="responsiveWrapper">
            <main>
                <!-- 好み一覧 -->
                <div id="favorites">
                    <div class="catalog">
                        
                        <div class="favorite">
                            <p>
                                <span class="genreS">S</span>
                                <span class="genreM">M:</span>
                                <span class="genreL">L</span>
                            </p>
                            <img src="images/circle.png">
                        </div>
                        
                        <div class="favorite">
                            <p>
                                <span class="genreS">S</span>
                                <span class="genreM">M:</span>
                                <span class="genreL">L</span>
                            </p>
                            <img src="images/circle.png">
                        </div>
                        
                        <div class="favorite">
                            <p>
                                <span class="genreS">S</span>
                                <span class="genreM">M:</span>
                                <span class="genreL">L</span>
                            </p>
                            <img src="images/circle.png">
                        </div>
                        
                        <div class="favorite">
                            <p>
                                <span class="genreS">S</span>
                                <span class="genreM">M:</span>
                                <span class="genreL">L</span>
                            </p>
                            <img src="images/circle.png">
                        </div>
                        
                        <div class="favorite">
                            <p>
                                <span class="genreS">S</span>
                                <span class="genreM">M:</span>
                                <span class="genreL">L</span>
                            </p>
                            <img src="images/circle.png">
                        </div>
                        
                        <div class="favorite">
                            <p>
                                <span class="genreS">S</span>
                                <span class="genreM">M:</span>
                                <span class="genreL">L</span>
                            </p>
                            <img src="images/circle.png">
                        </div>
                        
                        <div class="favorite">
                            <p>
                                <span class="genreS">S</span>
                                <span class="genreM">M:</span>
                                <span class="genreL">L</span>
                            </p>
                            <img src="images/circle.png">
                        </div>
                        
                        <div class="favorite">
                            <p>
                                <span class="genreS">S</span>
                                <span class="genreM">M:</span>
                                <span class="genreL">L</span>
                            </p>
                            <img src="images/circle.png">
                        </div>
                        
                        <div class="favorite">
                            <p>
                                <span class="genreS">S</span>
                                <span class="genreM">M:</span>
                                <span class="genreL">L</span>
                            </p>
                            <img src="images/circle.png">
                        </div>
                        
                        <div class="favorite">
                            <p>
                                <span class="genreS">S</span>
                                <span class="genreM">M:</span>
                                <span class="genreL">L</span>
                            </p>
                            <img src="images/circle.png">
                        </div>
                        
                        <div class="favorite">
                            <p>
                                <span class="genreS">S</span>
                                <span class="genreM">M:</span>
                                <span class="genreL">L</span>
                            </p>
                            <img src="images/circle.png">
                        </div>
                        
                        <div class="favorite">
                            <p>
                                <span class="genreS">S</span>
                                <span class="genreM">M:</span>
                                <span class="genreL">L</span>
                            </p>
                            <img src="images/circle.png">
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