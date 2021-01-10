<?php

$from = $_POST['from'];
$to = $_POST['to'];


$flg = 0;
print '<center>';

if(preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $from) == 0){
    print '正しい日付を入力して下さい<br>';
    print '開始　'.$from.'<br>';
    $flg = 1;
}

if(preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $to) == 0){
    print '正しい日付を入力して下さい<br>';
    print '終了　'.$to.'<br>';
    $flg = 1;
}

if($flg == 0){
    header('Location: rate.php?from='.$from.'&to='.$to);
    exit();
}

print '<br>';
print '<input type="button" onclick="history.back()" value="戻る">';
print '</center>';

?>
