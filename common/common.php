<?php

function get_date($before_date){
    $after_date = new DateTime($before_date);
    $change_date = $after_date -> format('m/d');
    $week = $after_date -> format('N');
    $week_list = array('', '月', '火', '水', '木', '金','土', '日');

    return $change_date.'('.$week_list[$week].')';
}

function holiday($date){

    $servername = "localhost";
    $username = "root";
    $password = "";

    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

function get_default($pinno) {

    $servername = "localhost";
    $username = "root";
    $password = "";

    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * from setting where pinno = ?";
    $stmt = $pdo->prepare($sql);
    $data[0] = $pinno;
    $stmt->execute($data);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result == ''){            
        $result['status'] = '守口';
        $result['start'] = '08:30:00';
        $result['end'] = '17:00:00';
        $result['location'] = '';
        $result['display'] = 1;
    }

    return $result;

}

function get_member_check($date, $department) {

    $servername = "localhost";
    $username = "root";
    $password = "";

    $check_list = array();
    $check_list = array(
        '守口' => array(), '在宅' => array(), '出張' => array(), '年休' => array(),'未登録' => array()
    );

    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT pinno from member where department = ?";
    $stmt = $pdo->prepare($sql);
    $data[0] = $department;
    $stmt->execute($data);
    $member = $stmt->fetchAll();

    foreach ($member as $value) {

        $sql = "SELECT member.name, member.pinno, plan.status, plan.flag FROM member LEFT JOIN plan ON member.pinno = plan.pinno AND plan.date = ? AND plan.flag = 0 WHERE member.pinno = ? AND member.department =?";
        $stmt = $pdo->prepare($sql);
        $data[0] = $date;
        $data[1] = $value['pinno'];
        $data[2] = $department;
        $stmt->execute($data);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        //$check_list['守口'][] = $result['status'];

        switch($result['status']){
            case '守口':
                $check_list['守口'][] = $result['name'];
                break;
            case '在宅':
                $check_list['在宅'][] = $result['name'];
                break;
            case '出張':
                $check_list['出張'][] = $result['name'];
                break;
            case '年休':
                $check_list['年休'][] = $result['name'];
                break;
            case '午前休':
                $check_list['年休'][] = $result['name'];
                break;
            case '午後休':
                $check_list['年休'][] = $result['name'];
                break;
            default :
                $check_list['未登録'][] = $result['name'];
                break;
        }

    }

    return $check_list;

}

?>