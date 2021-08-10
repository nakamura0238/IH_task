<?php

    // ドライバ呼び出しを使用して MySQL データベースに接続
    $dsn = 'mysql:dbname=IH_DB;host=localhost';
    $user = 'IH22_M';
    $password = 'N3wtYXVK';

    // $dsn = 'mysql:dbname=example0108_ih22;host=sv1.php.xdomain.ne.jp';
    // $user = 'example0108_ih22m';
    // $password = 'halih22m';

    try {
        $db = new PDO($dsn, $user, $password);
        // echo "接続成功\n";
    } catch (PDOException $e) {
        echo "接続失敗: " . $e->getMessage() . "\n";
        exit();
    }
