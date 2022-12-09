<?php

require 'password.php';   // password_hash()はphp 5.5.0以降の関数のため、バージョンが古くて使えない場合に使用
// セッション開始
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "nakamura-lab";  // ユーザー名
$db['pass'] = "n1k2m3r4fms";  // ユーザー名のパスワード
$db['dbname'] = "komatsubara_db";  // データベース名

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$signUpMessage = "";

// ログインボタンが押された場合
if (isset($_POST["signUp"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST["username"])) {  // 値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    } else if (empty($_POST["password2"])) {
        $errorMessage = 'パスワードが未入力です。';
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["address"]) && !empty($_POST["password2"]) && $_POST["password"] === $_POST["password2"]) {
        // 入力したユーザIDとパスワードを格納
        $username = $_POST["username"];
        $password = $_POST["password"];
        $address = $_POST["address"];

        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare("INSERT INTO user_table(user_name, user_pass, group_id, user_add) VALUES (?, ?, '-1', ?)");

            $stmt->execute(array($username, password_hash($password, PASSWORD_DEFAULT), $address));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
            $userid = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$useridに入れる
            $_SESSION['user_id'] = $userid;
            $_SESSION['name'] = $username;
            $_SESSION['user_add'] = $address;
            header("Location: groupin.php");
            $signUpMessage = '登録が完了しました。あなたの登録IDは '. $userid. ' です。パスワードは '. $password. ' です。';  // ログイン時に使用するIDとパスワード
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            echo $e->getMessage();
        }
    } else if($_POST["password"] != $_POST["password2"]) {
        $errorMessage = 'パスワードに誤りがあります。';
    }
}
?>

<!doctype html>
<html>
<link rel="shortcut icon" href="neko.ico" type="image/x-icon"/>
<link rel="stylesheet" href="css.css"> 
    <head>
            <meta charset="UTF-8">
            <title>新規登録</title>
        <script language="JavaScript">
          function imgSize(obj,w,h){
          obj.width=w;
          obj.height=h;
          }
        </script>
    </head>
    
    <body>
     <div class="back">
     <img src="Image/back2.png">
     </div>
     <div class="loginmain">
        <form id="loginForm" name="loginForm" action="" method="POST">
            <fieldset>
                <legend>新規登録</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
                <label for="username">ユーザー名</label><input type="text" id="username" name="username" placeholder="ユーザー名を入力" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
                <br>
                <label for="password">パスワード</label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力">
                <br>
                <label for="password2">パスワード(確認用)</label><input type="password" id="password2" name="password2" value="" placeholder="再度パスワードを入力">
                <br>
                <label for="address">メールアドレス</label><input type="address" id="address" name="address" value="" placeholder="メールアドレスを入力">
                <br>
                <input type="submit" id="signUp" name="signUp" value="新規登録">
            </fieldset>
        </form>
        <br>
        <form action="login.php">
            <input type="submit" value="戻る">
        </form>
        </div>
        <div class="aneko"></div> 
        <div class="bneko"></div>        
        <div class="cneko"></div>
        <div class="dneko"></div>
        <div class="eneko"></div>
        <p class="feed"> <img src="Image/feed.png"  onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></p>
        <p class="play"> <img src="Image/play.png"  onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></p>
        <p class="chat"> <a href="chat_css.php" ><img src="Image/chat.png" onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></a></p>  
        <p class="login"> <a href="login_css.php"><img src="Image/login.png" onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></a></p> 
 
    </body>
</html>