<?php

session_start();
$department = $_POST['department'];
$pinno = $_POST["pinno"];
$name = $_POST["name"];
$row = $_POST["row"];

$servername = "localhost";
$username = "root";
$password = "";

$count = 0;

try {

    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    for ($i=0; $i < count($pinno); $i++) { 
        
        $sql = "SELECT * from member where pinno=?";
        
        $stmt = $pdo->prepare($sql);
        $data1[0] = $pinno[$i];
        $stmt->execute($data1);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result != ''){

            print $pinno[$i].'は登録済です<br>';

        } else {

            $sql = "INSERT INTO member (pinno, name, pass, row, department) VALUES (?,?,'',?,?)";
            
            $stmt = $pdo->prepare($sql);
            $data2[0] = $pinno[$i];
            $data2[1] = $name[$i];
            $data2[2] = $row[$i];
            $data2[3] = $department;
            $stmt->execute($data2);
            
            $count++;
        }

    }

    $pdo = null;

    print $count.'人登録しました<br>';

    
    //header('Location: ../main/main.php?department='.$_SESSION['department']);
    //exit();

}
catch(PDOException $e)
{
    echo $e->getMessage();
    exit();
}

?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>SFD行動予定表</title>
</head>
<body>
    <br>
    <a href="../main/main.php?department=<?php print $_SESSION['department'] ?>">メイン画面へ</a>
</body>
</html>