<?php
// セッション開始
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "nakamura-lab";  // ユーザー名
$db['pass'] = "n1k2m3r4fms";  // ユーザー名のパスワード
$db['dbname'] = "komatsubara_db";  // データベース名

// エラーメッセージの初期化
$errorMessage = "";

// ログインボタンが押された場合
if (isset($_POST["login"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST["userid"])) {  // emptyは値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    }

    if (!empty($_POST["userid"]) && !empty($_POST["password"])) {
        // 入力したユーザIDを格納
        $userid = $_POST["userid"];

        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare('SELECT * FROM user_table WHERE user_id = ?');
            $stmt->execute(array($userid));

            $password = $_POST["password"];

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($password, $row['user_pass'])) {
                    session_regenerate_id(true);

                    // 入力したIDのユーザー名を取得
                    $id = $row['user_id'];
                    $sql = "SELECT * FROM user_table WHERE user_id = $id";  //入力したIDからユーザー名を取得
                    $stmt = $pdo->query($sql);
                    foreach ($stmt as $row) {
                        $row['user_name'];  // ユーザー名
                        $row['user_id'];
                        $row['group_id'];
                    }
                    $_SESSION["user_id"] = $row['user_id'];
                    $_SESSION['name'] = $row['user_name'];
                    $_SESSION['group_id'] = $row['group_id'];
                    header("Location: chat.php");  // メイン画面へ遷移
                    exit();  // 処理終了
                } else {
                    // 認証失敗
                    $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
                }
            } else {
                // 4. 認証成功なら、セッションIDを新規に発行する
                // 該当データなし
                $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
            }
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            $errorMessage = $sql;
            $e->getMessage(); //でエラー内容を参照可能（デバッグ時のみ表示）
            echo $e->getMessage();
        }
    }
}
?>

<!doctype html>
<html>
<link rel="shortcut icon" href="neko.ico" type="image/x-icon"/>
<link rel="stylesheet" href="css.css"> 
    <head>
            <meta charset="UTF-8">
            <title>ログイン</title>
     
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
        <img src="Image/login2.png">
        <form id="loginForm" name="loginForm" action="" method="POST">
            <fieldset>
                <legend>ログインフォーム</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <label for="userid">ユーザーID</label><input type="text" id="userid" name="userid" placeholder="ユーザーIDを入力" value="<?php if (!empty($_POST["userid"])) {echo htmlspecialchars($_POST["userid"], ENT_QUOTES);} ?>">
                <br>
                <label for="password">パスワード</label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力">
                <br>
                <input type="submit" id="login" name="login" value="ログイン">
            </fieldset>
        </form>
        <br>
        <form action="signup.php">
            <fieldset>          
                <legend>新規登録フォーム</legend>
                <input type="submit" value="新規登録">
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
<p class="chat"> <a href="chat_css.php" ><img src="Image/chat.png" onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></a></p>  
<p class="login"> <a href="login_css.php"><img src="Image/login.png" onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></a></p> 
    </body>
</html>
