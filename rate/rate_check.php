<?php
session_start();
if(isset($_SESSION['pinno'])==false){
    print '<center>';
    print 'ログインされていません<br>';
    print '<a href="../login/login.html">ログイン画面へ</a>';
    print '</center>';
    exit();
}

require_once('../common/common.php');

$servername = "localhost";
$username = "root";
$password = "";
$datetime = new DateTime();
$status = array('守口', '在宅', '出張', '年休', '合計');

try{

    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "SELECT date, COUNT(*) as 合計, COUNT(status='守口' or null) as '守口', COUNT(status='在宅' or null) as '在宅',
        COUNT(status='出張' or null) as '出張', COUNT(status='年休' or null) as '年休' FROM plan where flag=0 and date between ? and ? GROUP BY date";
	$stmt = $pdo->prepare($sql);
	$data[0] = '2020-09-01';
	$data[1] = '2020-09-30';
    $stmt->execute($data);

    $result = $stmt->fetchAll();

    $pdo = null;

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

<link rel="stylesheet" href="../css/rate.css"></link>

<title>SFD行動予定表</title>
</head>
<body>
<div class="container-fluid">
<h3>出勤率</h3>
<br>
<form method="post" action="rate_check.php">

<div class="text-center">
    <a href="../main/main.php">戻る</a>
</div>
<br>

<table class="table table-bordered table-striped">
<thead class="table-info">
	<tr>
    <th style="width:100px">勤務状態</th>
		<?php
        for ($i=0; $i < count($result); $i++) {
            if(holiday($result[$i]['date']) == 1){ ?>
                <th class="text-danger"><?php print date('m/d', strtotime($result[$i]['date'])); ?></th>
            <?php
            } else { ?>
                <th><?php print date('m/d', strtotime($result[$i]['date'])); ?></th>
            <?php
            } 
        } ?>
	</tr>
</thead>
<tbody>
    <?php
    foreach ($status as $st) { ?>
    <tr>
        <th>
        <?php
        switch($st){
            case '守口': ?>
                <button type="button" class="btn btn-outline-dark" role="button"><?php print $st; ?></button>
                <?php break;
            case '在宅': ?>
                <button type="button" class="btn btn-success btn-outline-dark text-white" role="button"><?php print $st; ?></button>
                <?php break;
            case '出張': ?>
                <button type="button" class="btn btn-warning btn-outline-dark" role="button"><?php print $st; ?></a>
                <?php break;
            case '年休': ?>
                <button type="button" class="btn btn-danger btn-outline-dark text-white" role="button"><?php print $st; ?></button>
                <?php break;
            default:
                print $st;
                break;
        } ?>
                 
        </th>

        <?php
        for ($i=0; $i < count($result); $i++) {
            if(holiday($result[$i]['date']) == 1){ ?>
                <td></td>
            <?php
            } else { ?>
                <td><?php print $result[$i][$st]; ?></td>
            <?php
            }
        } ?>
    </tr>
    <?php
    } ?>

    <tr>
    <th>出社率%</th>
    <?php
    for ($i=0; $i < count($result); $i++) { ?>
        <td><?php print (intval($result[$i]['守口']) / intval($result[$i]['合計']) * 100); ?></td>
    <?php
    } ?>

</tbody>

</table>
<div class="text-center">
    <a href="../main/main.php">戻る</a>
</div>
</form>
<br>
</body>
</html>
