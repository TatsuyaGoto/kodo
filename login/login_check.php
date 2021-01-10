<?php

$servername = "localhost";
$username = "root";
$password = "";

try {

    $pinno = htmlspecialchars($_POST['pinno'], ENT_QUOTES, 'utf-8');

    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //$sql = "SELECT pinno, department from member where pinno=?";
    $sql = "SELECT member.pinno, member.department, setting.display FROM member LEFT JOIN setting ON member.pinno = setting.pinno WHERE member.pinno = ?";
	$stmt = $pdo->prepare($sql);
	$data[0] = $pinno; 
	$stmt->execute($data);
    
    $pdo = null;

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($pinno == "sfd"){
        session_start();
        $_SESSION['pinno'] = $pinno;
        $_SESSION['department'] = 'sfd';
        $_SESSION['display'] = 2;
        header('Location: ../main/main.php?department='.$result['department']);
        exit();
    }
    
    if($result == false){
        print '<center>';
        print 'そのPIN No.は登録されていません<br>';
        print '<a href="login.html">戻る</a>';
        print '</center>';
        exit();

    } else {
        session_start();
        $_SESSION['pinno'] = $pinno;
        $_SESSION['department'] = $result['department'];
        if(empty($result['display']) == true ){ $result['display'] = 1; }
        $_SESSION['display'] = $result['display'];
        header('Location: ../main/main.php?department='.$result['department']);
        exit();
    
    }



}
catch(PDOException $e)
{
    echo $e->getMessage();
    exit();
}

?>