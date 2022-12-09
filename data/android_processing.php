<?php
if(isset($_GET["operate"])){
    $operate = $_GET["operate"];
    
    $mysqli = new mysqli("localhost", "nakamura-lab", "n1k2m3r4fms");
    $mysqli->select_db("watanabe_db");

    if ($operate == "insert"){
        $x = $_GET["x"];
        $y = $_GET["y"];
        $z = $_GET["z"];
        $group_id = $_GET["group_id"];
        $date = $_GET["date"];
        $insert = "INSERT INTO smartphone_processing (x, y, z, group_id, date) VALUES ( '$x' , '$y', '$z', '$group_id', '$date')";
        echo $insert;
        $mysqli->query(
          $insert
        );
        //echo "<script type='text/javascript'>window.close();</script>";
    }
    //遊んだことを通知するために、その日の運動量をまとめる
    else if($operate == "view" && isset($_GET["group_id"]) && isset($_GET["date"])){
      $userData = array();
      $group_id = $_GET["group_id"];
      $date = $_GET["date"];
      $send = "SELECT sum(x + y + z) FROM `smartphone_processing` where date='".$date."' and group_id=".$group_id;
        $results=$mysqli->query(
         $send
        );
      while( $line = $results->fetch_array( MYSQLI_BOTH )){
        $userData[]=array(
          'sum'=>$line['sum(x + y + z)']
          );
      }
    header('Content-type: application/json');
    echo json_encode($userData);
    }

    else if($operate == "view" && isset($_GET["group_id"])){
      $userData = array();
      $group_id = $_GET["group_id"];
        $results=$mysqli->query(
         "SELECT date, sum(x + y + z), group_id FROM `smartphone_processing` where group_id=$group_id group by date, group_id"
        );
       while( $line = $results->fetch_array( MYSQLI_BOTH )){
        $userData[]=array(
          'date'=>$line['date'],
          'sum'=>$line['sum(x + y + z)'],
          'group_id' => $line['group_id']
          );

      }
      //echo "</items>";
          //jsonとして出力
    header('Content-type: application/json');
    echo json_encode($userData);
    }
}
?>