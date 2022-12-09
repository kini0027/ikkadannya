<?php
$mysqli = new mysqli ("localhost", "nakamura-lab", "パスワード");
$mysqli->select_db("ryota_db");
$date = date("Y-m-d");
if(isset($_GET["gram"]) and isset($_GET["group_id"]) and isset($date)){
    $gram = $_GET["gram"];
    $group_id = $_GET["group_id"];
    //挿入部分
    $sql = "INSERT INTO wp (gram, group_id, date) VALUES('$gram', '$group_id','$date')";
    $inserts = $mysqli->query($sql);
}
$mysqli->close();
?>