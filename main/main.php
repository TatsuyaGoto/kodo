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

$default = get_default($_SESSION['pinno']);
$department = $_GET['department'];
$display = $_SESSION['display'];

if(isset($_GET['from'])==false){
    $from = new DateTime();
    $to = new DateTime();
    $to -> modify('+6 days');
} else {
    $from = new DateTime(str_replace('/', '-', $_GET['from']));
    $to = new DateTime(str_replace('/', '-', $_GET['to']));
}

$diff = $from->diff($to);
$str_from = $from -> format('Y-m-d');
$str_to = $to -> format('Y-m-d');

try {
    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//メンバーの当日の予定を取得　row順番
	$sql = "SELECT member.name, member.pinno, plan.status, plan.flag,
	DATE_FORMAT(plan.start, '%k:%i') as start,
	DATE_FORMAT(plan.end, '%k:%i') as end, plan.location, plan.remarks
	FROM member LEFT JOIN plan ON member.pinno = plan.pinno
	AND plan.date = ? AND plan.updateTime = (SELECT MAX(p2.updateTime) FROM plan p2 WHERE p2.pinno = plan.pinno AND p2.date = plan.date)
	WHERE member.department = ? ORDER BY member.row asc";
	$stmt = $pdo->prepare($sql);
	$data3[0] = date('Y-m-d');
	$data3[1] = $department;
	$stmt->execute($data3);

	$today_plan = $stmt->fetchAll();


	//メンバーの1週間の予定を取得　自分優先
    $sql = "SELECT pinno, name, row from member where department = ? ORDER BY pinno = ? desc, member.row asc;";
    $stmt = $pdo->prepare($sql);
    $data1[0] = $department;
    $data1[1] = $_SESSION['pinno'];
    $stmt->execute($data1);

	$member = $stmt->fetchAll();
	//$row = $member['row'];

    $plan = array();

    foreach ($member as $value) {
        //print $value['pinno'];

        $current_datetime = clone $from;

        for ($i=0; $i < $diff->days +1; $i++) { 

            $current_date = $current_datetime -> format('Y-m-d');
            
            $sql = "SELECT member.pinno, member.name, plan.date, plan.status,
					DATE_FORMAT(plan.start, '%k:%i') as start,
					DATE_FORMAT(plan.end, '%k:%i') as end, plan.location, plan.remarks, plan.flag
					FROM member INNER JOIN plan
					ON member.pinno = plan.pinno where member.pinno = ? AND plan.date = ?
                    AND plan.updateTime = (SELECT MAX(p2.updateTime) FROM plan p2 WHERE p2.pinno = plan.pinno AND p2.date = plan.date)";

            $stmt = $pdo->prepare($sql);

            $data2[0] = $value['pinno'];
            $data2[1] = $current_date;
	        $stmt->execute($data2);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if($result == ''){
                $result['pinno'] = $value['pinno'];
                $result['name'] = $value['name'];
				$result['status'] = '----';
				$result['start'] = '';
				$result['end'] = '';
				$result['location'] = '';
                $result['remarks'] = '';
                $result['flag'] = 0;
            }
            
            $plan[$value['pinno']][$i] = $result;
            $current_datetime -> modify('+1 days');

        }

    }

} catch(PDOException $e) {
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

<link rel="stylesheet" href="../css/main.css"></link>

<title>SFD行動予定表</title>
</head>
<body>
<div class="container-fluid">

<div class="d-flex align-items-center mb-2 px-4">
	<h1 class="my-0 mr-auto font-weight-normal">SFD行動予定表</h1>
	<a href="../useful/default_setting.php" class="mr-2 btn btn-outline-secondary text-dark" role="button">デフォルト設定</a>
	<a href="../addMember/add_member.php" class="mr-2 btn btn-outline-secondary text-dark" role="button">メンバー追加・編集</a>
  	<a href="../rate/rate.php" class="mr-2 btn btn-outline-secondary text-dark" role="button">出勤率確認</a>
	<a href="future_plan.php" class="btn btn-outline-secondary text-dark" role="button">勤務予定登録</a>
</div>

<nav>
	<div class="nav nav-tabs" id="nav-tab" role="tablist">
    	<a class="nav-item nav-link <?php if($display==2){print 'active';}?>" id="nav-today-tab" data-toggle="tab" href="#nav-today" role="tab" aria-controls="nav-today" aria-selected="true">当日全体表示</a>
    	<a class="nav-item nav-link <?php if($display==1){print 'active';}?>" id="nav-week-tab" data-toggle="tab" href="#nav-week" role="tab" aria-controls="nav-week" aria-selected="true">1週間縦表示</a>
	</div>
</nav>
<div class="tab-content" id="nav-tabContent">

	<div class="tab-pane fade <?php if($display==2){print 'show active';}?>" id="nav-today" role="tabpanel" aria-labelledby="nav-today-tab">
	<!-- 全体表示　当日分のみ-->
		<div class="row">
		<div class="col-3">
        	<table class="my-2 table table-bordered table-sm">
            	<thead class="table-active">
	            	<tr>
		            	<th style="width: 50%; font-size: 0.9vw;">名前</th>
                    	<th style="width: 50%; font-size: 0.9vw;">状態</th> 
	            	</tr>
            	</thead>
            	<tbody>
                
                	<?php
                	for ($i=0; $i < 6; $i++) {

						if ($i==3) {
					?>
							<td class="table-primary" colspan="2" style="font-size: 0.9vw;">エンジニアリング 1課</td>
					<?php
						}
					?>    
                	<tr>
                    	<td>
						<?php
							if ($today_plan[$i]['flag']==0){
						?>
								<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark text-white" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php
							} else {

								switch($today_plan[$i]['status']){
									case '守口':
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-outline-dark" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;

									case '在宅':
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-success btn-outline-dark text-white" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;

									case '出張':
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-warning btn-outline-dark" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;

									default:
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark text-white" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;
								}
							}
						?>
                    	</td>
						<?php
                    	switch($today_plan[$i]['status']){
                        	case '守口': ?>
                            	<td style="font-size: 0.9vw">
                            	<?php break;
                        	case '在宅': ?>
                            	<td style="font-size: 0.9vw" class="table-success">
                            	<?php break;
                        	case '出張': ?>
                            	<td style="font-size: 0.9vw" class="table-warning">
                            	<?php break;
                        	case '年休': ?>
                            	<td style="font-size: 0.9vw" class="table-danger">
								<?php break;
							case '午前休': ?>
                            	<td style="font-size: 0.9vw" class="table-danger">
								<?php break;
							case '午後休': ?>
                            	<td style="font-size: 0.9vw" class="table-danger">
								<?php break;
                        	default: ?>
                            	<td style="font-size: 0.9vw" class="table-secondary">
                            	<?php break;
                    	}?>
                        	<?php print '勤務状態：'.$today_plan[$i]['status']; ?><br>
  				        	<?php print '勤務予定：'.$today_plan[$i]['start']; ?>-
  				        	<?php print $today_plan[$i]['end']; ?><br>
  				        	<?php print '現在位置：'.$today_plan[$i]['location']; ?><br>
  				        	<?php print '備考　　：'.nl2br($today_plan[$i]['remarks']); ?>
                    	</td>
                	</tr>

					<?php	
					}
					?>

            	</tbody>

        	</table>

    	</div>

		<div class="col-3">
        	<table class="my-2 table table-bordered table-sm">
            	<thead class="table-active">
	            	<tr>
		            	<th style="width: 50%; font-size: 0.9vw;">名前</th>
                    	<th style="width: 50%; font-size: 0.9vw;">状態</th> 
	            	</tr>
            	</thead>
            	<tbody>
                
                	<?php
                	for ($i=6; $i < 12; $i++) {

						if ($i==6) {
					?>
							<td class="table-primary" colspan="2" style="font-size: 0.9vw;">エンジニアリング 1課</td>
						<?php
						}
						?>    
                	<tr>
                    	<td>
						<?php
							if ($today_plan[$i]['flag']==0){
						?>
								<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark text-white" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php
							} else {

								switch($today_plan[$i]['status']){
									case '守口':
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-outline-dark" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;

									case '在宅':
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-success btn-outline-dark text-white" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;

									case '出張':
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-warning btn-outline-dark" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;

									default:
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark text-white" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;
								}
							}
						?>
                    	</td>
						<?php
                    	switch($today_plan[$i]['status']){
                        	case '守口': ?>
                            	<td style="font-size: 0.9vw">
                            	<?php break;
                        	case '在宅': ?>
                            	<td style="font-size: 0.9vw" class="table-success">
                            	<?php break;
                        	case '出張': ?>
                            	<td style="font-size: 0.9vw" class="table-warning">
                            	<?php break;
                        	case '年休': ?>
                            	<td style="font-size: 0.9vw" class="table-danger">
								<?php break;
							case '午前休': ?>
                            	<td style="font-size: 0.9vw" class="table-danger">
								<?php break;
							case '午後休': ?>
                            	<td style="font-size: 0.9vw" class="table-danger">
								<?php break;
                        	default: ?>
                            	<td style="font-size: 0.9vw" class="table-secondary">
                            	<?php break;
                    	}?>
                        	<?php print '勤務状態：'.$today_plan[$i]['status']; ?><br>
  				        	<?php print '勤務予定：'.$today_plan[$i]['start']; ?>-
  				        	<?php print $today_plan[$i]['end']; ?><br>
  				        	<?php print '現在位置：'.$today_plan[$i]['location']; ?><br>
  				        	<?php print '備考　　：'.nl2br($today_plan[$i]['remarks']); ?>
                    	</td>
                	</tr>

					<?php
					}
					?>

            	</tbody>

        	</table>

    	</div>

		<div class="col-3">
        	<table class="my-2 table table-bordered table-sm">
            	<thead class="table-active">
	            	<tr>
		            	<th style="width: 50%; font-size: 0.9vw;">名前</th>
                    	<th style="width: 50%; font-size: 0.9vw;">状態</th> 
	            	</tr>
            	</thead>
            	<tbody>
                
                	<?php
                	for ($i=12; $i < 18; $i++) {

						if ($i==12) {
					?>
							<td class="table-success" colspan="2" style="font-size: 0.9vw;">エンジニアリング 2課</td>
						<?php
						}
						?>    
                	<tr>
                    	<td>
						<?php
							if ($today_plan[$i]['flag']==0){
						?>
								<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark text-white" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php
							} else {

								switch($today_plan[$i]['status']){
									case '守口':
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-outline-dark" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;

									case '在宅':
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-success btn-outline-dark text-white" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;

									case '出張':
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-warning btn-outline-dark" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;

									default:
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark text-white" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;
								}
							}
						?>
                    	</td>
						<?php
                    	switch($today_plan[$i]['status']){
                        	case '守口': ?>
                            	<td style="font-size: 0.9vw">
                            	<?php break;
                        	case '在宅': ?>
                            	<td style="font-size: 0.9vw" class="table-success">
                            	<?php break;
                        	case '出張': ?>
                            	<td style="font-size: 0.9vw" class="table-warning">
                            	<?php break;
                        	case '年休': ?>
                            	<td style="font-size: 0.9vw" class="table-danger">
								<?php break;
							case '午前休': ?>
                            	<td style="font-size: 0.9vw" class="table-danger">
								<?php break;
							case '午後休': ?>
                            	<td style="font-size: 0.9vw" class="table-danger">
								<?php break;
                        	default: ?>
                            	<td style="font-size: 0.9vw" class="table-secondary">
                            	<?php break;
                    	}?>
                        	<?php print '勤務状態：'.$today_plan[$i]['status']; ?><br>
  				        	<?php print '勤務予定：'.$today_plan[$i]['start']; ?>-
  				        	<?php print $today_plan[$i]['end']; ?><br>
  				        	<?php print '現在位置：'.$today_plan[$i]['location']; ?><br>
  				        	<?php print '備考　　：'.nl2br($today_plan[$i]['remarks']); ?>
                    	</td>
                	</tr>

					<?php
					}
					?>

            	</tbody>

        	</table>

    	</div>

		<div class="col-3">
        	<table class="my-2 table table-bordered table-sm">
            	<thead class="table-active">
	            	<tr>
		            	<th style="width: 50%; font-size: 0.9vw;">名前</th>
                    	<th style="width: 50%; font-size: 0.9vw;">状態</th> 
	            	</tr>
            	</thead>
            	<tbody>
                
                	<?php
                	for ($i=18; $i < count($today_plan); $i++) {

						if ($i==18) {
					?>
							<td class="table-success" colspan="2" style="font-size: 0.9vw;">エンジニアリング 2課</td>
						<?php
						}
						?>    
                	<tr>
                    	<td>
						<?php
							if ($today_plan[$i]['flag']==0){
						?>
								<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark text-white" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php
							} else {

								switch($today_plan[$i]['status']){
									case '守口':
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-outline-dark" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;

									case '在宅':
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-success btn-outline-dark text-white" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;

									case '出張':
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-warning btn-outline-dark" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;

									default:
						?>
										<a href="today_plan.php?pinno=<?php print $today_plan[$i]['pinno']; ?>" class="btn btn-danger btn-outline-dark text-white" role="button"><?php print $today_plan[$i]['name']; ?></a>
						<?php			break;
								}
							}
						?>
                    	</td>
						<?php
                    	switch($today_plan[$i]['status']){
                        	case '守口': ?>
                            	<td style="font-size: 0.9vw">
                            	<?php break;
                        	case '在宅': ?>
                            	<td style="font-size: 0.9vw" class="table-success">
                            	<?php break;
                        	case '出張': ?>
                            	<td style="font-size: 0.9vw" class="table-warning">
                            	<?php break;
                        	case '年休': ?>
                            	<td style="font-size: 0.9vw" class="table-danger">
								<?php break;
							case '午前休': ?>
                            	<td style="font-size: 0.9vw" class="table-danger">
								<?php break;
							case '午後休': ?>
                            	<td style="font-size: 0.9vw" class="table-danger">
								<?php break;
                        	default: ?>
                            	<td style="font-size: 0.9vw" class="table-secondary">
                            	<?php break;
                    	}?>
                        	<?php print '勤務状態：'.$today_plan[$i]['status']; ?><br>
  				        	<?php print '勤務予定：'.$today_plan[$i]['start']; ?>-
  				        	<?php print $today_plan[$i]['end']; ?><br>
  				        	<?php print '現在位置：'.$today_plan[$i]['location']; ?><br>
  				        	<?php print '備考　　：'.nl2br($today_plan[$i]['remarks']); ?>
                    	</td>
                	</tr>

					<?php
					}
					?>

            	</tbody>

        	</table>

    	</div>

		</div>
  	</div>

	<div class="tab-pane fade <?php if($display==1){print 'show active';}?>" id="nav-week" role="tabpanel" aria-labelledby="nav-week-tab">
	<!-- 縦表示　1週間　-->
		<form method="post" action="date_check.php?action=main">
    	<div class="mt-2 form-inline">
        	<div><label class="item">開始</label></div>
        	<div>
                <div class="form-group">
            	    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                	    <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" name="from" />
                    	<div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                        	<div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    	</div>
                	</div>
            	</div>
        	</div>
        	<script type="text/javascript">
            	$(function () {
                	$('#datetimepicker1').datetimepicker({
						defaultDate: '<?php print $str_from; ?>',
                    	format: 'YYYY/MM/DD'
                	});
            	});
        	</script>
        	<label class="item">　　～　　終了</label>
        	<div>
            	<div class="form-group">
                	<div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                    	<input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="to" style="width: 200px" />
                    	<div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                        	<div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    	</div>
                	</div>
            	</div>
        	</div>
        	<script type="text/javascript">
            	$(function () {
                	$('#datetimepicker2').datetimepicker({
                    	defaultDate: '<?php print $str_to; ?>',
                    	format: 'YYYY/MM/DD'
                	});
            	});
        	</script>
        	<div class="row ml-3 col-auto">
        		<button type="submit" class="btn btn-primary" style="height: 40px">表示</button>
        	</div>
    	</div>
		</form>
		<br>


		<table class="my-2 table table-bordered" style="white-space: nowrap;">
		<!-- <table class="my-2 table table-bordered" style="table-layout: fixed"> -->
			<thead class="table-info">
				<tr>
					<th style="width: 200px;">名前</th>

					<?php
					$datetime = clone $from;

					$date = $datetime -> format('Y-m-d');
					if(holiday($date) == 1){
					?>
						<th class="text-danger"><?php print get_date($date); ?></th>
					<?php
					} else {
					?>
						<th style="width: 300px;"><?php print get_date($date); ?></th>
					<?php
					}

					for ($i=0; $i < $diff->days ; $i++) {

						$datetime -> modify('+1 days');
						$date = $datetime -> format('Y-m-d');
						if(holiday($date) == 1){
					?>
							<th style="width: 150px;" class="text-danger"><?php print get_date($date); ?></th>
					<?php
						} else {
					?>
							<th style="width: 150px;"><?php print get_date($date); ?></th>
					<?php
						}

					}
					?>

				</tr>
			</thead>

			<tbody>
				<?php
				foreach ($member as $value) {
				?>
					<tr>
						<th>
						<?php
							if ($plan[$value['pinno']][0]['flag']==0){
						?>
								<a href="today_plan.php?pinno=<?php print $value['pinno']; ?>" class="btn btn-danger btn-outline-dark btn-lg text-white" role="button"><?php print $value['name']; ?></a>
						<?php
							} else {

								switch($plan[$value['pinno']][0]['status']){
									case '守口':
						?>
										<a href="today_plan.php?pinno=<?php print $value['pinno']; ?>" class="btn btn-outline-dark btn-lg" role="button"><?php print $value['name']; ?></a>
						<?php			break;

									case '在宅':
						?>
										<a href="today_plan.php?pinno=<?php print $value['pinno']; ?>" class="btn btn-success btn-outline-dark btn-lg text-white" role="button"><?php print $value['name']; ?></a>
						<?php			break;

									case '出張':
						?>
										<a href="today_plan.php?pinno=<?php print $value['pinno']; ?>" class="btn btn-warning btn-outline-dark btn-lg" role="button"><?php print $value['name']; ?></a>
						<?php			break;

									default:
						?>
										<a href="today_plan.php?pinno=<?php print $value['pinno']; ?>" class="btn btn-danger btn-outline-dark btn-lg text-white" role="button"><?php print $value['name']; ?></a>
						<?php			break;
								}
							}
						?>
						</th>

						<?php
						for ($i=0; $i < $diff->days +1; $i++) { 
						
							if ($i==0) {
								switch($plan[$value['pinno']][$i]['status']){
									case '守口': ?>
										<th style="font-size: 20px">
										<?php break;
									case '在宅': ?>
										<th style="font-size: 20px" class="table-success">
										<?php break;
									case '出張': ?>
										<th style="font-size: 20px" class="table-warning">
										<?php break;
									case '年休': ?>
										<th style="font-size: 20px" class="table-danger">
										<?php break;
									case '午前休': ?>
										<th style="font-size: 20px" class="table-danger">
										<?php break;
									case '午後休': ?>
										<th style="font-size: 20px" class="table-danger">
										<?php break;
									default: ?>
										<th style="font-size: 20px" class="table-secondary">
										<?php break;
								}?>
          							<?php print '勤務状態：　'.$plan[$value['pinno']][$i]['status']; ?><br>
  									<?php print '勤務予定：　'.$plan[$value['pinno']][$i]['start']; ?>-
  									<?php print $plan[$value['pinno']][$i]['end']; ?><br>
  									<?php print '現在位置：　'.$plan[$value['pinno']][$i]['location']; ?><br>
  									<?php print '備考<br>'.nl2br($plan[$value['pinno']][$i]['remarks']); ?>
        						</th>
						<?php
							} else {
						?>
								<td class="table-active">
									<?php print $plan[$value['pinno']][$i]['status']; ?><br>
									<?php print $plan[$value['pinno']][$i]['start']; ?>-
									<?php print $plan[$value['pinno']][$i]['end']; ?><br>
									<?php print $plan[$value['pinno']][$i]['location']; ?><br>
									<?php print nl2br($plan[$value['pinno']][$i]['remarks']); ?>
								</td>
						<?php
							}
						  
						} ?>

					<tr>
				<?php
				}
				?>


			</tbody>
		</table>
	</div>

</div>



</div>
</body>
</html>
