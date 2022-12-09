<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION["name"])) {
    header("Location: logout.php");
    exit;
}

?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>メイン</title>
    </head>
    <body>
        <h1>メイン画面</h1>
        <!-- ユーザーIDにHTMLタグが含まれても良いようにエスケープする -->
        <p>ようこそ<u><?php echo htmlspecialchars($_SESSION["name"], ENT_QUOTES); ?></u>さん</p>  <!-- ユーザー名をechoで表示 -->
        <ul>
            <li><a href="logout.php">ログアウト</a></li>
        </ul>
    </body>
</html>