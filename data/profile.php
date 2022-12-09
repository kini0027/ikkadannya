<?php

session_start();

// ログイン状態チェック
if (!isset($_SESSION["name"])) {
    header("Location: logout.php");
    exit;
}

if(isset($_POST["profileUp"])) {
    $username = $_POST["username"];
    $useradd = $_POST["user_add"];
    $usericon = $_POST["user_icon"];
}

//ファイルアップロードがあったとき
if (isset($_FILES['user_icon']['error']) && is_int($_FILES['user_icon']['error']) && $_FILES["user_icon"]["name"] !== ""){
    //エラーチェック
    switch ($_FILES['upfile']['error']) {
        case UPLOAD_ERR_OK: // OK
            break;
        case UPLOAD_ERR_NO_FILE:   // 未選択
            throw new RuntimeException('ファイルが選択されていません', 400);
        case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
            throw new RuntimeException('ファイルサイズが大きすぎます', 400);
        default:
            throw new RuntimeException('その他のエラーが発生しました', 500);
    }

    //画像・動画をバイナリデータにする．
    $raw_data = file_get_contents($_FILES['upfile']['tmp_name']);

    //拡張子を見る
    $tmp = pathinfo($_FILES["upfile"]["name"]);
    $extension = $tmp["extension"];
    if($extension === "jpg" || $extension === "jpeg" || $extension === "JPG" || $extension === "JPEG"){
        $extension = "jpeg";
    }
    elseif($extension === "png" || $extension === "PNG"){
        $extension = "png";
    }
    else{
        echo "非対応ファイルです．<br/>";
        echo ("<a href=\"profile.php\">戻る</a><br/>");
        exit(1);
    }

    //DBに格納するファイルネーム設定
    //サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256をかける．
    $date = getdate();
    $fname = $_FILES["upfile"]["tmp_name"].$date["year"].$date["mon"].$date["mday"].$date["hours"].$date["minutes"].$date["seconds"];
    $fname = hash("sha256", $fname);

    //画像・動画をDBに格納．
    $sql = "INSERT INTO media(fname, extension, raw_data) VALUES (:fname, :extension, :raw_data);";
    $stmt = $pdo->prepare($sql);
    $stmt -> bindValue(":fname",$fname, PDO::PARAM_STR);
    $stmt -> bindValue(":extension",$extension, PDO::PARAM_STR);
    $stmt -> bindValue(":raw_data",$raw_data, PDO::PARAM_STR);
    $stmt -> execute();
}


?>

<!doctype html>
<html lang='ja'>
<link rel="shortcut icon" href="neko.ico" type="image/x-icon"/>
<link rel="stylesheet" href="css.css">

 <head>
  <meta charset="utf-8">
  <title>プロフィール確認ページ</title>
  <script language="JavaScript">
   function imgSize(obj,w,h){
       obj.width = w;
       obj.height = h;
   }
   </script>
 </head>

 <body>
 <div class="back">
 <img src="Image/back2.png">
 </div>
 <div class="loginmain">
  <form id="profile" name="profile" action="" method="post">
   <fieldset>
    <label for="username">ユーザー名</label><input type="text" id="username" name="username" placeholder="ユーザー名を入力" value="<?php echo $_SESSION['name']?>">
    <br>
    <label for="password">メールアドレス</label><input type="text" id="user_add" name="user_add" value="<?php echo $_SESSION['user_add']?>" placeholder="メールアドレスを入力">
    <br>
    <label for="password2">ユーザーアイコン</label><input type="file" id="user_icon" name="user_icon">
    <br>
    <input type="submit" id="profileUp" name="profileUp" value="更新">
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
 </head>