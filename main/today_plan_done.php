<?php

$servername = "localhost";
$username = "root";
$password = "";

try {

    session_start();
    //$pinno = htmlspecialchars($_SESSION['pinno'], ENT_QUOTES, 'utf-8');
    $pinno = htmlspecialchars($_GET['pinno'], ENT_QUOTES, 'utf-8');
    $date = htmlspecialchars($_SESSION['date'], ENT_QUOTES, 'utf-8');
    $start = htmlspecialchars($_SESSION['start'], ENT_QUOTES, 'utf-8');
    $end = htmlspecialchars($_SESSION['end'], ENT_QUOTES, 'utf-8');
    $location = htmlspecialchars($_SESSION['location'], ENT_QUOTES, 'utf-8');
    $remarks = htmlspecialchars($_SESSION['remarks'], ENT_QUOTES, 'utf-8');
    $status = htmlspecialchars($_SESSION['status'], ENT_QUOTES, 'utf-8');


    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO plan (pinno, date, status, start, end, location, remarks, updateTime, flag) VALUES (?,?,?,?,?,?,?,now(),1) ";
    
    //$sql = "INSERT INTO plan (pinno, date, status, start, end, location, remarks, updateTime, flag) VALUES (?,?,?,?,?,?,?,now(),1) 
    //        ON DUPLICATE KEY UPDATE status=VALUES(status), start=VALUES(start), end=VALUES(end), location=VALUES(location), remarks=VALUES(remarks), updateTime=now(), flag=1;";
    
    $stmt = $pdo->prepare($sql);
    $data[0] = $pinno;
    $data[1] = $date;
    $data[2] = $status;
    $data[3] = $start.':00';
    $data[4] = $end.':00';
    $data[5] = $location;
    $data[6] = $remarks;
	$stmt->execute($data);
    
    $pdo = null;

    //print $pinno.'<br>';
    //print $date.'<br>';
    //print $start.'<br>';
    //print $end.'<br>';
    //print $location.'<br>';
    //print $remarks.'<br>';
    //print $status.'<br>';

    header('Location: ../main/main.php?department='.$_SESSION['department']);
    exit();

}
catch(PDOException $e)
{
    echo $e->getMessage();
    exit();
}

?>