<?php

    // ドライバ呼び出しを使用して MySQL データベースに接続
    $dsn = 'mysql:dbname=IH_DB;host=localhost';
    $user = 'IH22_M';
    $password = 'N3wtYXVK';

    try {
        $db = new PDO($dsn, $user, $password);
        // echo "接続成功\n";
    } catch (PDOException $e) {
        echo "接続失敗: " . $e->getMessage() . "\n";
        exit();
    }
