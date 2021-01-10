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
$department = "sfd";

try {
    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$current_datetime = new DateTime();
	$current_date = $current_datetime -> format('Y-m-d');

    $sql = "SELECT member.name, member.pinno, plan.status, plan.flag,
            DATE_FORMAT(plan.start, '%k:%i') as start,
			DATE_FORMAT(plan.end, '%k:%i') as end, plan.location, plan.remarks
            FROM member LEFT JOIN plan ON member.pinno = plan.pinno
        	AND plan.date = ? AND plan.updateTime = (SELECT MAX(p2.updateTime) FROM plan p2 WHERE p2.pinno = plan.pinno AND p2.date = plan.date)
            WHERE member.department = ? ORDER BY member.row asc";
	$stmt = $pdo->prepare($sql);
    $data1[0] = $current_date;
    $data1[1] = $department;
	$stmt->execute($data1);

    $result = $stmt->fetchAll();
    //var_dump($result);

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
<!-- bootstrapとcssファイル読み込み -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
<link rel="stylesheet" href="../css/moni.css"></link>

<title>SFD行動予定表</title>
</head>
<body>
<div class="container-fluid">
<h1>SFD行動予定表</h1>

<div class="row">
    <div class="col-3">
        <table class="table table-bordered table-sm">
            <thead class="table-active">
	            <tr>
		            <th style="width: 50%;">名前</th>
                    <th style="width: 50%;">状態</th> 
	            </tr>
            </thead>
            <tbody>
                
                <?php
                for ($i=0; $i < 3; $i++) { ?>
                    
                <tr>
                    <td>
                    <?php
		            if ($result[$i]['flag']==0){ ?>
			            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
		            <?php
		            } else {

			            switch($result[$i]['status']){
				            case '守口': ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-outline-dark btn-lg" role="button"><?php print $result[$i]['name']; ?></a>
					            <?php break;
				            case '在宅': ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-success btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
					            <?php break;
				            case '出張': ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-warning btn-outline-dark btn-lg" role="button"><?php print $result[$i]['name']; ?></a>
					            <?php break;
				            default: ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
				                <?php break;
			            }

		            }?>
                    </td>
                    <?php
                        switch($result[$i]['status']){
                            case '守口': ?>
                                <td style="font-size: 1vw">
                                <?php break;
                            case '在宅': ?>
                                <td style="font-size: 1vw" class="table-success">
                                <?php break;
                            case '出張': ?>
                                <td style="font-size: 1vw" class="table-warning">
                                <?php break;
                            case '年休': ?>
                                <td style="font-size: 1vw" class="table-danger">
                                <?php break;
                            default: ?>
                                <td style="font-size: 1vw" class="table-secondary">
                                <?php break;
                    }?>
                        <?php print '勤務状態：'.$result[$i]['status']; ?><br>
  				        <?php print '勤務予定：'.$result[$i]['start']; ?>-
  				        <?php print $result[$i]['end']; ?><br>
  				        <?php print '現在位置：'.$result[$i]['location']; ?><br>
  				        <?php print '備考　　：'.nl2br($result[$i]['remarks']); ?>
                    </td>
                </tr>

                <?php
                } ?>

                <td class="table-primary" colspan="2" style="font-size: 1vw;">エンジニアリング 1課</td>

                <?php
                for ($i=3; $i < 6; $i++) { ?>
                    
                    <tr>
                        <td>
                        <?php
                        if ($result[$i]['flag']==0){ ?>
                            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
                        <?php
                        } else {
    
                            switch($result[$i]['status']){
                                case '守口': ?>
                                    <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-outline-dark btn-lg" role="button"><?php print $result[$i]['name']; ?></a>
                                    <?php break;
                                case '在宅': ?>
                                    <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-success btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
                                    <?php break;
                                case '出張': ?>
                                    <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-warning btn-outline-dark btn-lg" role="button"><?php print $result[$i]['name']; ?></a>
                                    <?php break;
                                default: ?>
                                    <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
                                    <?php break;
                            }
    
                        }?>
                        </td>
                        <?php
                        switch($result[$i]['status']){
                            case '守口': ?>
                                <td style="font-size: 1vw">
                                <?php break;
                            case '在宅': ?>
                                <td style="font-size: 1vw" class="table-success">
                                <?php break;
                            case '出張': ?>
                                <td style="font-size: 1vw" class="table-warning">
                                <?php break;
                            case '年休': ?>
                                <td style="font-size: 1vw" class="table-danger">
                                <?php break;
                            default: ?>
                                <td style="font-size: 1vw" class="table-secondary">
                                <?php break;
                        }?>
                            <?php print '勤務状態：'.$result[$i]['status']; ?><br>
                              <?php print '勤務予定：'.$result[$i]['start']; ?>-
                              <?php print $result[$i]['end']; ?><br>
                              <?php print '現在位置：'.$result[$i]['location']; ?><br>
                              <?php print '備考　　：'.nl2br($result[$i]['remarks']); ?>
                        </td>
                    </tr>
    
                    <?php
                    } ?>

            </tbody>

        </table>
    </div>


    <div class="col-3">
        <table class="table table-bordered table-sm">
            <thead class="table-active">
	            <tr>
		            <th style="width: 50%;">名前</th>
                    <th style="width: 50%;">状態</th> 
	            </tr>
            </thead>
            <tbody>

                <td class="table-primary" colspan="2" style="font-size: 1vw;">エンジニアリング 1課</td>
                
                <?php
                for ($i=6; $i < 12; $i++) { ?>
                    
                <tr>
                    <td>
                    <?php
		            if ($result[$i]['flag']==0){ ?>
			            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
		            <?php
		            } else {

			            switch($result[$i]['status']){
				            case '守口': ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-outline-dark btn-lg" role="button"><?php print $result[$i]['name']; ?></a>
					            <?php break;
				            case '在宅': ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-success btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
					            <?php break;
				            case '出張': ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-warning btn-outline-dark btn-lg" role="button"><?php print $result[$i]['name']; ?></a>
					            <?php break;
				            default: ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
				                <?php break;
			            }

		            }?>
                    </td>
                    <?php
                    switch($result[$i]['status']){
                        case '守口': ?>
                            <td style="font-size: 1vw">
                            <?php break;
                        case '在宅': ?>
                            <td style="font-size: 1vw" class="table-success">
                            <?php break;
                        case '出張': ?>
                            <td style="font-size: 1vw" class="table-warning">
                            <?php break;
                        case '年休': ?>
                            <td style="font-size: 1vw" class="table-danger">
                            <?php break;
                        default: ?>
                            <td style="font-size: 1vw" class="table-secondary">
                            <?php break;
                    }?>
                        <?php print '勤務状態：'.$result[$i]['status']; ?><br>
  				        <?php print '勤務予定：'.$result[$i]['start']; ?>-
  				        <?php print $result[$i]['end']; ?><br>
  				        <?php print '現在位置：'.$result[$i]['location']; ?><br>
  				        <?php print '備考　　：'.nl2br($result[$i]['remarks']); ?>
                    </td>
                </tr>

                <?php
                } ?>

            </tbody>

        </table>
    </div>

    <div class="col-3">
        <table class="table table-bordered table-sm">
            <thead class="table-active">
	            <tr>
		            <th style="width: 50%;">名前</th>
                    <th style="width: 50%;">状態</th> 
	            </tr>
            </thead>
            <tbody>

            <td class="table-success" colspan="2" style="font-size: 1vw;">エンジニアリング 2課</td>
                
                <?php
                for ($i=12; $i < 18; $i++) { ?>
                    
                <tr>
                    <td>
                    <?php
		            if ($result[$i]['flag']==0){ ?>
			            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
		            <?php
		            } else {

			            switch($result[$i]['status']){
				            case '守口': ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-outline-dark btn-lg" role="button"><?php print $result[$i]['name']; ?></a>
					            <?php break;
				            case '在宅': ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-success btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
					            <?php break;
				            case '出張': ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-warning btn-outline-dark btn-lg" role="button"><?php print $result[$i]['name']; ?></a>
					            <?php break;
				            default: ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
				                <?php break;
			            }

		            }?>
                    </td>
                    <?php
                        switch($result[$i]['status']){
                            case '守口': ?>
                                <td style="font-size: 1vw">
                                <?php break;
                            case '在宅': ?>
                                <td style="font-size: 1vw" class="table-success">
                                <?php break;
                            case '出張': ?>
                                <td style="font-size: 1vw" class="table-warning">
                                <?php break;
                            case '年休': ?>
                                <td style="font-size: 1vw" class="table-danger">
                                <?php break;
                            default: ?>
                                <td style="font-size: 1vw" class="table-secondary">
                                <?php break;
                    }?>
                        <?php print '勤務状態：'.$result[$i]['status']; ?><br>
  				        <?php print '勤務予定：'.$result[$i]['start']; ?>-
  				        <?php print $result[$i]['end']; ?><br>
  				        <?php print '現在位置：'.$result[$i]['location']; ?><br>
  				        <?php print '備考　　：'.nl2br($result[$i]['remarks']); ?>
                    </td>
                </tr>

                <?php
                } ?>

            </tbody>

        </table>
    </div>

    <div class="col-3">
        <table class="table table-bordered table-sm">
            <thead class="table-active">
	            <tr>
		            <th style="width: 50%;">名前</th>
                    <th style="width: 50%;">状態</th> 
	            </tr>
            </thead>
            <tbody>

                <td class="table-success" colspan="2" style="font-size: 1vw;">エンジニアリング 2課</td>
                
                <?php
                for ($i=18; $i < count($result); $i++) { ?>
                    
                <tr>
                    <td>
                    <?php
		            if ($result[$i]['flag']==0){ ?>
			            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
		            <?php
		            } else {

			            switch($result[$i]['status']){
				            case '守口': ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-outline-dark btn-lg" role="button"><?php print $result[$i]['name']; ?></a>
					            <?php break;
				            case '在宅': ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-success btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
					            <?php break;
				            case '出張': ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-warning btn-outline-dark btn-lg" role="button"><?php print $result[$i]['name']; ?></a>
					            <?php break;
				            default: ?>
					            <a href="../main/today_plan.php?pinno=<?php print $result[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark btn-lg text-white" role="button"><?php print $result[$i]['name']; ?></a>
				                <?php break;
			            }

		            }?>
                    </td>
                    <?php
                        switch($result[$i]['status']){
                            case '守口': ?>
                                <td style="font-size: 1vw">
                                <?php break;
                            case '在宅': ?>
                                <td style="font-size: 1vw" class="table-success">
                                <?php break;
                            case '出張': ?>
                                <td style="font-size: 1vw" class="table-warning">
                                <?php break;
                            case '年休': ?>
                                <td style="font-size: 1vw" class="table-danger">
                                <?php break;
                            default: ?>
                                <td style="font-size: 1vw" class="table-secondary">
                                <?php break;
                    }?>
                        <?php print '勤務状態：'.$result[$i]['status']; ?><br>
  				        <?php print '勤務予定：'.$result[$i]['start']; ?>-
  				        <?php print $result[$i]['end']; ?><br>
  				        <?php print '現在位置：'.$result[$i]['location']; ?><br>
  				        <?php print '備考　　：'.nl2br($result[$i]['remarks']); ?>
                    </td>
                </tr>

                <?php
                } ?>

            </tbody>

        </table>
    </div>


</div>

<div class="text-center">
    <a href="../main/main.php?department=sfd">戻る</a>
</div>

</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>
