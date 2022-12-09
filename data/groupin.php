<?php
require 'password.php';   // password_hash()はphp 5.5.0以降の関数のため、バージョンが古くて使えない場合に使用
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "nakamura-lab";  // ユーザー名
$db['pass'] = "n1k2m3r4fms";  // ユーザー名のパスワード
$db['dbname'] = "komatsubara_db";  // データベース名

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$signUpMessage = "";

if(isset($_POST["groupin"])){
    // 1. ユーザIDの入力チェック
    if (empty($_POST["groupname"])) {  // 値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["grouppass1"])) {
        $errorMessage = 'パスワードが未入力です。';
    } else if (empty($_POST["grouppass2"])) {
        $errorMessage = 'パスワードが未入力です。';
    } else if (empty($_POST["groupid"])) {
        $errorMessage = 'IDが未入力です。';
    }

    if(!empty($_POST["groupname"]) && !empty($_POST["grouppass1"]) && !empty($_POST["grouppass2"]) && !empty($_POST["gorupid"]) && $_POST["grouppass1"] === $_POST["grouppass2"]) {
        $groupname = $_POST["groupname"];
        $grouppass = $_POST["grouppass1"];
        $groupid = $_POST["groupid"];

        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $check = $pdo->prepare("SELECT * FROM group_table WHERE group_id=?");
            $check -> execute(array($groupid));
            $group = $check->fetchAll();

            if ($group["group_pass"] === $grouppass) {
                $stmt = $pdo->prepare("INSERT INTO group_table(group_id) VALUES (?)");
                $stmt->execute(array($groupid));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
            } else {
                $errorMessage = 'パスワードが間違っています';
            }
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            echo $e->getMessage();
        }
    } else if($_POST["grouppass1"] != $_POST["grouppass2"]) {
        $errorMessage = 'パスワードに誤りがあります。';
    }
}else if (isset($_POST["groupset"])){
    
    if (empty($_POST["groupname2"])) {  // 値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["grouppass3"])) {
        $errorMessage = 'パスワードが未入力です。';
    } else if (empty($_POST["grouppass4"])) {
        $errorMessage = 'パスワードが未入力です。';
    }
    echo 'set';

    if(!empty($_POST["groupname2"]) && !empty($_POST["grouppass3"]) && !empty($_POST["grouppass4"]) && $_POST["grouppass3"] === $_POST["grouppass4"]){
        $groupname = $_POST["groupname2"];
        $grouppass = $_POST["grouppass3"];
        
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare("INSERT INTO group_table(group_name, group_pass) VALUES (?, ?)");
            $stmt->execute(array($groupname, password_hash($grouppass, PASSWORD_DEFAULT)));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
            $groupid = $pdo->lastinsertid();
            echo "登録完了";
            $stmt = $pdo->prepare("UPDATE user_table SET group_id=? WHERE user_table.user_id = ?");
            $stmt->execute(array($groupid,$_SESSION['user_id']));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
            $_SESSION['group_id'] = $groupid;
            header("Location: chat.php");
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            echo $e->getMessage();
        }
    } else if($_POST["grouppass3"] != $_POST["grouppass4"]) {
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
<title>グループ登録</title>
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
     <div class="groupinmain">
<form id="groupinForm" name="groupinForm" action="" method="POST">
<fieldset>
<div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
<legend>グループ加入はこちら</legend>

<label for="groupname">グループ名</label><input type="text" id="groupname" name="groupname" value="" placeholder="グループ名を入力">
<br>
<label for="grouppass1">グループ用パスワード</label><input type="password" id="grouppass1" name="grouppass1" value="" placeholder="パスワードを入力">
<br>
<label for="grouppass2">グループ用パスワード(確認用)</label><input type="password" name="grouppass2" value="" placeholder="再度パスワードを入力">
<br>
<label for="groupid">グループID</label><input type="groupid" id="groupid" name="groupid" value="" placeholder="グループIDを入力">
<br>  
<input type="submit" id="groupin" name="groupin" value="グループ加入">
</fieldset>
</form>

<form id="groupsetForm" name="groupsetForm" action="" method="POST">
<fieldset>
<legend>新しくグループをつくる場合はこちら</legend>
<label for="groupname2">グループ名</label><input type="text" id="groupname2" name="groupname2" value="" placeholder="グループ名を入力">
<br>
<label for="grouppass3">グループ用パスワード</label><input type="password" id="grouppass3" name="grouppass3" value="" placeholder="パスワードを入力">
<br>
<label for="grouppass4">グループ用パスワード(確認用)</label><input type="password" name="grouppass4" value="" placeholder="再度パスワードを入力">
<br>
<input type="submit" id="groupset" name="groupset" value="グループ作成">
</fieldset>
</form>
        </div>

<div class="aneko"></div> 
        <div class="bneko"></div>        
        <div class="cneko"></div>
        <div class="dneko"></div>
        <div class="eneko"></div>
        <p class="feed"> <img src="Image/feed.png"  onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></p>
        <p class="play"> <img src="Image/play.png"  onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></p>
        <p class="chat"> <a href="chat.php" ><img src="Image/chat.png" onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></a></p>  
        <p class="login"> <a href="login.php"><img src="Image/login.png" onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></a></p> 
 
</body>
</html>
