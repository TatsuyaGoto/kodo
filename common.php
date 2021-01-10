<?php

$servername = "localhost";
$username = "emc";
$password = "pass";

$pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function get_date($before_date){
    $after_date = new DateTime($before_date);
    $change_date = $after_date -> format('m/d');
    $week = $after_date -> format('N');
    $week_list = array('', '月', '火', '水', '木', '金','土', '日');

    return $change_date.'('.$week_list[$week].')';
}

function holiday($date){

    $sql = "SELECT * FROM offday WHERE date=?";
    $stmt = $pdo->prepare($sql);
    $data[0] = $date;
    $stmt->execute($data);
    $flg = 0;
    $datetime = new DateTime($date);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $w = $datetime -> format('N');
    
    if($w >= 6 || $result !=''){ $flg = 1; }

    return $flg;
}

?>