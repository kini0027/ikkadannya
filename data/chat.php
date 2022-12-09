<?php

session_start();

// ログイン状態チェック
if (!isset($_SESSION["name"])) {
    header("Location: logout.php");
    exit;
}

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "nakamura-lab";  // ユーザー名
$db['pass'] = "n1k2m3r4fms";  // ユーザー名のパスワード
$db['dbname'] = "komatsubara_db";  // データベース名

// エラーメッセの初期化
$errorMessage = "";

// 2. ユーザIDとパスワードが入力されていたら認証する
$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

if(isset($_POST["submit"])){
    if(isset($_POST["message"])){
        $message = $_POST["message"];
        $date = date("Y-m-d H:i");
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        $sql = $pdo->prepare("INSERT INTO chat_table(user_id, group_id, message, date) value (?, ?, ?, ?)");
        $sql->execute(array($_SESSION['user_id'],$_SESSION['group_id'],$message,$date));
    }
    header('Location: ./chat.php ');
}
?>

<!DOCTYPE HTML>
<html>

 <head>
  <meta charset="UTF-8">
  <link rel="shortcut icon" href="Image/neko.ico" type="image/x-icon"/>
  <link rel="stylesheet" href="css.css"> 
  <SCRIPT LANGUAGE="JavaScript">
    function imgSize(obj,w,h){
    obj.width=w;
    obj.height=h;
    };
        //  setTimeout("location.reload()",1000*5);
  </SCRIPT>
 </head>
 
 <body>
    <div class="back">
    <img src="Image/back2.png">
    </div>  
    <div class="welcome">
    <!-- ユーザーIDにHTMLタグが含まれても良いようにエスケープする -->
    </div>
    <div class="chatmain">
  <?php
  $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
  $sql = $pdo->prepare("SELECT * FROM chat_table WHERE group_id=? ORDER BY user_id ASC");
  $sql->execute(array($_SESSION['group_id']));
  $data = $sql->fetchAll();
  foreach($data as $chat){
    $name = $pdo->query("SELECT * FROM user_table WHERE user_id = ".$chat['user_id']);
    $n = $name->fetchAll();
    foreach($n as $name){

    } 
    if($chat['user_id']==-1)
    {
      echo "
      <div class='one'>
      <div>
      <img src='Image/asiato.png' class='iconA' width='80'>
      </div>
      <div class='chatA' width='400'>

          <p style='left:100px'>
          <p style='height:10px;width:120px;font-size:13px;left:60px'>
          むさし</p>
             <p style='min-height:30px;min-width:70px;background-color:#7ec7d8; border-bottom-left-radius: 10px;border-top-left-radius: 10px;border-top-right-radius: 40px;border-bottom-right-radius: 40px;'>
              {$chat["message"]}    
         </p> 
         </p> 
         </div>
         </div>"; 
    }   else{ 
             echo "
             <div class='one'>
             <div>
             <img src='Image/human.png' class='iconB' width='80'>
             </div>
             <div class='chatB' width='400'>
        <p style='left:100px'>
         <p style='height:10px;width:120px;font-size:13px;left:60px'>
           {$name['user_name']}</p>
           <p style='min-height:30px;min-width:70px;background-color:pink; border-bottom-left-radius: 10px;border-top-left-radius: 10px;border-top-right-radius: 40px;border-bottom-right-radius: 40px;'>
            {$chat["message"]}     
           </p> 
           </p> 
           </div>
           </div>";
           }       
  }
  ?>
  <br>
  </div>
  <div class="message">
      <form name="chat" id="chat" action="chat.php" method="POST">
        <input type="text" id="message" name="message" placeholder="">
        <input type="submit" id="submit" name="submit" value="送信">
      </form>
    </div>
<div class="link">
  <p class="feed"> <a href="eat_display.php"><img src="Image/feed.png"  onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></p>
  <p class="play"> <a href="test_chart.php"><img src="Image/play.png"  onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></p>
  <p class="chat"> <a href="profile.php" ><img src="Image/chat.png" onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></a></p>  
  <p class="logout"> <a href="logout.php"><img src="Image/logout.png" onMousemove="imgSize(this,300,143)" onMouseout="imgSize(this,274,130)"></a></p> 
   </div>
 </body>

 <div class="aneko"></div> 
 <div class="bneko"></div>        
 <div class="cneko"></div>
 <div class="dneko"></div>
 <div class="eneko"></div>

</html>