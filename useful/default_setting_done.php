<?php

$servername = "localhost";
$username = "root";
$password = "";

try {

    session_start();
    $pinno = htmlspecialchars($_SESSION['pinno'], ENT_QUOTES, 'utf-8');
    //$date = htmlspecialchars($_SESSION['date'], ENT_QUOTES, 'utf-8');
    $start = htmlspecialchars($_SESSION['start'], ENT_QUOTES, 'utf-8');
    $end = htmlspecialchars($_SESSION['end'], ENT_QUOTES, 'utf-8');
    $location = htmlspecialchars($_SESSION['location'], ENT_QUOTES, 'utf-8');
    //$remarks = htmlspecialchars($_SESSION['remarks'], ENT_QUOTES, 'utf-8');
    $status = htmlspecialchars($_SESSION['status'], ENT_QUOTES, 'utf-8');
    $display = htmlspecialchars($_SESSION['display'], ENT_QUOTES, 'utf-8');


    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //$sql = "INSERT INTO plan (pinno, date, status, start, end, location, remarks, updateTime, flag) VALUES (?,?,?,?,?,?,?,now(),1) ";
    
    $sql = "INSERT INTO setting (pinno, status, start, end, location, display) VALUES (?,?,?,?,?,?) 
            ON DUPLICATE KEY UPDATE status=VALUES(status), start=VALUES(start), end=VALUES(end), location=VALUES(location), display=VALUES(display)";
    
    $stmt = $pdo->prepare($sql);
    $data[0] = $pinno;
    $data[1] = $status;
    $data[2] = $start.':00';
    $data[3] = $end.':00';
    $data[4] = $location;
    $data[5] = $display;
	$stmt->execute($data);
    
    $pdo = null;

    //print $pinno.'<br>';
    //print $date.'<br>';
    //print $start.'<br>';
    //print $end.'<br>';
    //print $location.'<br>';
    //print $remarks.'<br>';
    //print $status.'<br>';

    //header('Location: ../main/main.php');
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
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<!-- Moment.js -->
<!-- moment.min.jsの方が上 -->
<!-- moment-with-locales.min.jsの方が下 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js" integrity="sha256-AdQN98MVZs44Eq2yTwtoKufhnU+uZ7v2kXnD5vqzZVo=" crossorigin="anonymous"></script>

<!-- Tempus Dominus -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />

<!-- Font Awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<title>SFD行動予定表</title>
</head>
<bod class="text-center">
    <div class="container-fluid">

        <br>
        登録完了しました<br>
        <br>
        <a href="../main/main.php?department=<?php print $_SESSION['department'] ?>" class="btn btn-outline-secondary text-dark" role="button">メイン画面へ</a>
        <a href="default_setting.php" class="btn btn-outline-secondary text-dark" role="button">デフォルト設定画面へ</a>

    </div>

</body>
</html>