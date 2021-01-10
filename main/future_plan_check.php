<?php

require_once('../common/common.php');

$count = (int)$_POST['count'];
$flg = 0;
$term = array();
$status = array();
$location = array();
$start = array();
$end = array();
$remarks = array();
$updateTime = array();

print '<center>';

for ($i=0; $i < $count; $i++) { 
  
    //print $_POST['date'.$i].' ';
    //print $_POST['status'.$i].' ';
    //print $_POST['start'.$i].' ';
    //print $_POST['end'.$i].' ';
    //print $_POST['location'.$i].' ';
    //print $_POST['other'.$i].' ';
    //print $_POST['remarks'.$i].'<br>';

    if(holiday($_POST['date'.$i]) != 1 && $_POST['status'.$i] == '----'){
        print $_POST['date'.$i].'　';
        print '平日は「----」以外を選択して下さい<br>';
        $flg = 1;
    }

    if(preg_match('/^(0[0-9]{1}|1{1}[0-9]{1}|2{1}[0-3]{1}):(0[0-9]{1}|[1-5]{1}[0-9]{1})$/', $_POST['start'.$i]) == 0){
        print $_POST['date'.$i].'　';
        print '正しい時刻を入力して下さい⇛';
        print $_POST['start'.$i].'<br>';
        $flg = 1; 
    }
    
    if(preg_match('/^(0[0-9]{1}|1{1}[0-9]{1}|2{1}[0-3]{1}):(0[0-9]{1}|[1-5]{1}[0-9]{1})$/', $_POST['end'.$i]) == 0){
        print $_POST['date'.$i].'　';
        print '正しい時刻を入力して下さい⇛';
        print $_POST['end'.$i].'<br>';
        $flg = 1;
    }

    if($_POST['other'.$i] == '' && $_POST['location'.$i] == 'その他'){
        print $_POST['date'.$i].'　';
        print '「その他」選択時は「その他出張先」に場所を記入して下さい<br>';
        $flg = 1;
    }

    if($_POST['other'.$i] != '' && $_POST['location'.$i] != 'その他'){
        print $_POST['date'.$i].'　';
        print '「その他出張先」手入力時は「その他」を選択して下さい<br>';
        $flg = 1;
    }

    if(strlen($_POST['remarks'.$i]) >100){
        print $_POST['date'.$i].'　';
        print '自由記述欄は３０文字以内にして下さい<br>';
        $flg = 1;
    }

    if($flg==0){
        $term[] = $_POST['date'.$i];
        $status[] = $_POST['status'.$i];
        $start[] = $_POST['start'.$i];
        $end[] = $_POST['end'.$i];
        $remarks[] = $_POST['remarks'.$i];
        $updateTime[] = $_POST['updateTime'.$i];

        if($_POST['location'.$i]=='住之江' || $_POST['location'.$i]=='貝塚' || $_POST['location'.$i]=='加西' || $_POST['location'.$i]=='姫路' || $_POST['location'.$i]==''){
            $location[] = $_POST['location'.$i];
        } else {
            $location[] = $_POST['other'.$i];
        }

    }
}

//for ($i=0; $i < $count; $i++) { 
    //print $term[$i].' ';
    //print $status[$i].' ';
    //print $start[$i].' ';
    //print $end[$i].' ';
    //print $location[$i].' ';
    //print $remarks[$i].'<br>';
//}

if($flg==0){
    session_start();

    $_SESSION['count'] = $count;
    $_SESSION['date'] = $term;
    $_SESSION['start'] = $start;
    $_SESSION['end'] = $end;
    $_SESSION['location'] = $location;
    $_SESSION['remarks'] = $remarks;
    $_SESSION['status'] = $status;
    $_SESSION['updateTime'] = $updateTime;

    header('Location: future_plan_done.php');
    //var_dump($_SESSION['updateTime']);
    exit();
}

print '<br>';
print '<input type="button" onclick="history.back()" value="戻る">';
print '</center>';

?>