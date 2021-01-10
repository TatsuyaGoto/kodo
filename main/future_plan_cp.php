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

$default = get_default($_SESSION['pinno']);

$term = array();
$status = array();
$location = array();
$start = array();
$end = array();
$remarks = array();
$other = array();
$updateTime = array();
$servername = "localhost";
$username = "root";
$password = "";

if(isset($_GET['from'])==false){
    $from = new DateTime();
    $from -> modify('+1 days');
    $to = new DateTime();
    $to -> modify('+31 days');
} else {
    $from = new DateTime(str_replace('/', '-', $_GET['from']));
    $to = new DateTime(str_replace('/', '-', $_GET['to']));
}

$diff = $from->diff($to);
$str_from = $from -> format('Y-m-d');
$str_to = $to -> format('Y-m-d');


try{
    for ($i=0; $i <= $diff->days; $i++) { 

        //$datetime -> modify('+1 days');
        $date = $from -> format('Y-m-d');

        $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		$sql = "SELECT pinno, date, status, location, remarks, updateTime,
				DATE_FORMAT(plan.start, '%H:%i') as start,
				DATE_FORMAT(plan.end, '%H:%i') as end
				FROM plan where pinno = ? AND date = ?";
		$stmt = $pdo->prepare($sql);
		$data[0] = $_SESSION['pinno'];
		$data[1] = $date;
		$stmt->execute($data);
		
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($result == ''){            
            $result['status'] =  $default['status'];
            $result['start'] =  $default['start'];
            $result['end'] =  $default['end'];
            $result['location'] =  $default['location'];
            $result['remarks'] = '';
            $result['other'] = '';
            $result['updateTime'] = date('Y-m-d H:i:s');
        } 

        $term[] = $date;
        $status[] = $result['status'];
        //$location[] = $result['location'];
        $start[] = $result['start'];
        $end[] = $result['end'];
        $remarks[] = $result['remarks'];
        $updateTime[] = $result['updateTime'];

        if($result['location']=='住之江' || $result['location']=='貝塚' || $result['location']=='加西' || $result['location']=='姫路' || $result['location']==''){
            $location[] = $result['location'];
            $other[] = '';
        } else {
            $location[] = 'その他';
            $other[] = $result['location'];
        }
        
        $from -> modify('+1 days');
    }

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

<link rel="stylesheet" href="../css/today.css"></link>

<title>SFD行動予定表</title>
</head>
<body>
<div class="container-fluid">
<h3>勤務予定登録</h3>
<br>

<form method="post" action="date_check.php">
    <div class="form-inline">
        <div><label class="item">開始</label></div>
        <div>
            <!-- <label class="item">開始</label> -->
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
            <!-- <label class="item">終了</label> -->
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
        <label>　　</label>
        <div class="row col-auto">
        <button type="submit" class="btn btn-primary" style="height: 40px">表示</button>
        </div>
    </div>
</form>
<br>

<form method="post" action="future_plan_check.php">

<div class="text-center">
    <button type="submit" class="btn btn-primary btn-lg">登　録</button><br>
    <a href="main.php?department=<?php print $_SESSION['department'] ?>">戻る</a>
</div>
<br>
<table class="table table-bordered table-striped" style="table-layout: fixed">
<thead class="table-info">
	<tr>
		<th style="width:5%">日付</th>
        <th style="width:5%">勤務予定</th>
        <th style="width:8%">業務開始</th>
        <th style="width:8%">終了予定</th>
        <th style="width:8%">出張先</th>
        <th style="width:10%">その他出張先</th>
        <th style="width:20%">自由記述</th>	
	</tr>
</thead>
<tbody>
    <?php for ($i=0; $i <= $diff->days; $i++) { ?>
        <?php if(holiday($term[$i]) != 1) { ?>
        <tr>
            <td><?php print get_date($term[$i]); ?></td>

            <td>
                <select class="form-control" name="status<?php print $i; ?>">
                <option value="守口" <?php if($status[$i]=='守口'){print 'selected';} ?> >守口</option>
                <option value="在宅" <?php if($status[$i]=='在宅'){print 'selected';} ?> >在宅</option>
                <option value="出張" <?php if($status[$i]=='出張'){print 'selected';} ?> >出張</option>
                <option value="年休" <?php if($status[$i]=='年休'){print 'selected';} ?> >年休</option>
                <option value="----" <?php if(holiday($term[$i]) == 1) { print 'selected';} ?> >----</option>
                </select>
            </td>

            <td>
                <div>
                    <div class="input-group date" id="datetimepicker1<?php print $i; ?>" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1<?php print $i; ?>" name="start<?php print $i; ?>" />
                        <div class="input-group-append" data-target="#datetimepicker1<?php print $i; ?>" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                    </div>
                </div>
                </div>
                <script type="text/javascript">
                    $(function () {
                        $('#datetimepicker1<?php print $i; ?>').datetimepicker({
                            defaultDate: '<?php print $term[$i].' '.$start[$i]; ?>',
                            format: 'HH:mm',
                            stepping: 5
                        });
                    });
                </script>
            </td>

            <td>
                <div>
                    <div class="input-group date" id="datetimepicker2<?php print $i; ?>" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2<?php print $i; ?>" name="end<?php print $i; ?>" />
                        <div class="input-group-append" data-target="#datetimepicker2<?php print $i; ?>" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    $(function () {
                        $('#datetimepicker2<?php print $i; ?>').datetimepicker({
                            defaultDate: '<?php print $term[$i].' '.$end[$i]; ?>',
                            format: 'HH:mm',
                            stepping: 5
                        });
                    });
                </script>
            </td>

            <td>
                <select class="form-control" name="location<?php print $i; ?>">
                <option value=""></option>
                <option value="住之江" <?php if($location[$i]=='住之江'){print 'selected';} ?> >住之江</option>
                <option value="貝塚" <?php if($location[$i]=='貝塚'){print 'selected';} ?> >貝塚</option>
                <option value="加西" <?php if($location[$i]=='加西'){print 'selected';} ?> >加西</option>
                <option value="姫路" <?php if($location[$i]=='姫路'){print 'selected';} ?> >姫路</option>
                <option value="その他" <?php if($location[$i]=='その他'){print 'selected';} ?> >その他</option>
                </select>
            </td>

            <td>
                <input class="form-control" type="text" name="other<?php print $i; ?>" value="<?php print $other[$i]; ?>">
            </td>
            <td>
                <input class="form-control" type="text" name="remarks<?php print $i; ?>" value="<?php print $remarks[$i]; ?>">
            </td>
        </tr>
    
        <?php
        } else { ?>
        <tr>
            <td class="text-danger"><?php print get_date($term[$i]); ?></td>
            <td><input type="hidden" name="status<?php print $i; ?>" value="----"></td>
            <td><input type="hidden" name="start<?php print $i; ?>" value="00:00"></td>
            <td><input type="hidden" name="end<?php print $i; ?>" value="00:00"></td>
            <td><input type="hidden" name="location<?php print $i; ?>" value=""></td>
            <td><input type="hidden" name="other<?php print $i; ?>" value=""></td>
            <td><input type="hidden" name="remarks<?php print $i; ?>" value=""></td>
        </tr>
        <?php 
        } ?>
        <input type="hidden" name="date<?php print $i; ?>" value="<?php print $term[$i]; ?>">
        <input type="hidden" name="updateTime<?php print $i; ?>" value="<?php print $updateTime[$i]; ?>">
        
    <?php } ?>

</tbody>

</table>
<div class="text-center">
    <input type="hidden" name="count" value="<?php print count($term); ?>">
    <button type="submit" class="btn btn-primary btn-lg">登　録</button><br>
    <a href="main.php?department=<?php print $_SESSION['department'] ?>">戻る</a>
</div>
</form>
<br>
</body>
</html>