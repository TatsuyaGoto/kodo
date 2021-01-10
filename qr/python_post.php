<?php

$servername = "localhost";
$username = "root";
$password = "";

$pinno = $_POST['pinno'];
$status = $_POST['status'];


try {
    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * from member where pinno =?";
    $stmt = $pdo->prepare($sql);
    $data[0] = $pinno;
    $stmt->execute($data);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result == '') {
        print 'そのPINNOは存在しません';
        exit();
    }

    $sql = "SELECT * FROM plan pl1 WHERE pl1.pinno = ? AND pl1.date = ? 
             AND pl1.updateTime = (SELECT MAX(pl2.updateTime) FROM plan pl2 WHERE pl2.pinno = pl1.pinno AND pl2.date = pl1.date)";
	$stmt = $pdo->prepare($sql);
	$data[0] = $pinno;
	$data[1] = date('Y-m-d');
	$stmt->execute($data);

	$result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($result == ''){
        $result['pinno'] = $pinno;
        $result['date'] = date('Y-m-d');
        $result['status'] = $status;
        $result['start'] = '08:30:00';
        $result['end'] = '17:00:00';
        $result['location'] = '居室';
        $result['remarks'] = '';

    }

    $sql = "INSERT INTO plan (pinno, date, status, start, end, location, remarks, updateTime, flag) VALUES (?,?,?,?,?,?,?,now(),1) ";
    
    $stmt = $pdo->prepare($sql);
    $data[0] = $pinno;
    $data[1] = $result['date'];
    $data[2] = $status;
    $data[3] = $result['start'];
    $data[4] = $result['end'];
    $data[5] = '居室';
    $data[6] = $result['remarks'];
	$stmt->execute($data);
    
    print ('Success');

    $pdo = null;

    exit();
    

} catch(PDOException $e) {
	echo $e->getMessage();
	exit();
}
?>