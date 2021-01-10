<?php

$start = $_POST['start'];
$end = $_POST['end'];
$location = $_POST['location'];
$other = $_POST['other'];
//$remarks = $_POST['remarks'];
$status = $_POST['status'];
$display = $_POST['display'];

$flg = 0;
print '<center>';

if(preg_match('/^(0[0-9]{1}|1{1}[0-9]{1}|2{1}[0-3]{1}):(0[0-9]{1}|[1-5]{1}[0-9]{1})$/', $start) == 0){
    print '正しい時刻を入力して下さい<br>';
    print '開始時間　'.$start.'<br>';
    $flg = 1;
}

if(preg_match('/^(0[0-9]{1}|1{1}[0-9]{1}|2{1}[0-3]{1}):(0[0-9]{1}|[1-5]{1}[0-9]{1})$/', $end) == 0){
    print '正しい時刻を入力して下さい<br>';
    print '終了時間　'.$end.'<br>';
    $flg = 1;
}

if($location == 'その他'){
    if($other =='' || $other == null){
        print '「その他」選択時は場所を記入して下さい<br>';
        $flg = 1;
    } else {
        $location = $other;
    }
}

if($status == '在宅' || $status == '終業' ){
    $location = '';
}

if(strlen($remarks) >100){
    print '自由記述欄は３０文字以内にして下さい<br>';
    $flg = 1;
}

if($flg == 0){
    session_start();
    //$_SESSION['date'] = $date;
    $_SESSION['start'] = $start;
    $_SESSION['end'] = $end;
    $_SESSION['location'] = $location;
    //$_SESSION['remarks'] = $remarks;
    $_SESSION['status'] = $status;
    $_SESSION['display'] = $display;

    header('Location: default_setting_done.php');
    exit();
}

print '<br>';
print '<input type="button" onclick="history.back()" value="戻る">';
print '</center>';

?>
