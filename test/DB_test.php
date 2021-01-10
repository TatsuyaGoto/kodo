<?php

$servername = "localhost";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM member";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

    $result = $stmt->fetchAll();
    
    foreach ($result as $key => $value) {
        print $value['pinno'].'<br>';
        print $value['name'].'<br>';
        print $value['row'].'<br>';
        print '<br>';
    }

} catch(PDOException $e) {
	echo $e->getMessage();
	exit();
}
?>
