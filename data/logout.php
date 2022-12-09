<?php
session_start();

if (isset($_SESSION["NAME"])) {
    $errorMessage = "ログアウトしました。";
} else {
    $errorMessage = "セッションがタイムアウトしました。";
}

// セッションの変数のクリア
$_SESSION = array();

// セッションクリア
@session_destroy();
?>

<!doctype html>
<html>
  <link rel="shortcut icon" href="neko.ico" type="image/x-icon"/>
<link rel="stylesheet" href="css.css"> 
    <head>
        <meta charset="UTF-8">
        <title>ログアウト</title>
    </head>
    <body>
    <div class="back">
    <img src="Image/back2.png">
    </div>  
    <div class="loginmain">
        <h1>ログアウト画面</h1>
        <div><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></div>
        <ul>
            <li><a href="login.php">ログイン画面に戻る</a></li>
        </ul>
</div>
        
<p class="feed"> <img src="Image/feed.png"  onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></p>
<p class="play"> <img src="Image/play.png"  onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></p>
<p class="chat"> <a href="chat_css.php" ><img src="Image/chat.png" onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></a></p>  
<p class="logout"> <a href="logout.php"><img src="Image/logout.png" onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></a></p> 
 </body>

 <div class="aneko"></div> 
<div class="bneko"></div>        
<div class="cneko"></div>
<div class="dneko"></div>
<div class="eneko"></div>
    </body>
</html>