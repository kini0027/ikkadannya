<?php
    $mysqli = new mysqli("localhost", "nakamura-lab", "(パスワードなので伏せます)");
    $mysqli->select_db("komatsubara_db");
//猫専用insert api, 猫の発言をchatデータベースに送信
    if (isset($_GET["group_id"]) && isset($_GET["message"]) && isset($_GET["date"]) ){
        $user_id = -1; // 猫はuser_id = -1に設定している
        $group_id = $_GET["group_id"];
        $message = $_GET["message"];
        $date = $_GET["date"];
        $db['host'] = "localhost";  // DBサーバのURL
        $db['user'] = "nakamura-lab";  // ユーザー名
        $db['pass'] = "(パスワードなので伏せます)";  // ユーザー名のパスワード
        $db['dbname'] = "komatsubara_db";  // データベース名
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        $sql = $pdo->prepare("INSERT INTO chat_table(user_id, group_id, message, date) value (?, ?, ?, ?)");
        $sql->execute(array($user_id,$group_id,$message,$date));

    }
    ?>