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

try {
    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$current_datetime = new DateTime();
	$current_date = $current_datetime -> format('Y-m-d');

    $sql = "SELECT * from plan p1 where p1.pinno = ? AND p1.date = ?
            AND p1.updateTime = (SELECT MAX(p2.updateTime) FROM plan p2 WHERE p2.pinno = p1.pinno AND p2.date = p1.date)";
	$stmt = $pdo->prepare($sql);
    $data[0] = $_GET['pinno'];
    $data[1] = $current_date;
	$stmt->execute($data);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $pdo = null;

    if($result == false){
        //print 'データなし';
        $result['date'] = $current_date;
        $result['start'] = $default['start'];
        $result['end'] = $default['end'];
        $result['status'] = $default['status'];
        $result['location'] = $default['location'];
        $result['remarks'] = '';
        $result['flag'] = 0;
    }

    if($result['location']=='居室' || $result['location']=='住之江' || $result['location']=='貝塚' || $result['location']=='加西'
     || $result['location']=='姫路' || $result['location']=='F棟' || $result['location']=='C棟' || $result['location']=='PENA' || $result['location']==''){
        $location = $result['location'];
        $other = '';
    } else {
        $location = 'その他';
        $other = $result['location'];
    }

    //print $_SESSION['pinno'].'<br>';
    //print $result['date'].'<br>';
    //print $result['date'].' '.$result['start'].'<br>';
    //print $result['date'].' '.$result['end'].'<br>';
    //print $result['location'].'<br>';
    //print $location.'<br>';
    //print $result['remarks'].'<br>';
    //print $result['status'].'<br>';
    //
    //print $other;


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


<div class="container">
    <h3>勤務状況入力</h3><br>

    <form method="post" action="today_plan_check.php?pinno=<?php print $_GET['pinno'] ?>">
        <div class="row">
            <div class="col-sm-3">
                <label class="item">開始時間</label>
                <div class="form-group">
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" name="start" />
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker1').datetimepicker({
                        defaultDate: '<?php print $result['date'].' '.$result['start']; ?>',
                        format: 'HH:mm',
                        stepping: 5
                    });
                });
            </script>


            <div class="col-sm-3">
                <label class="item">終了予定</label>
                <div class="form-group">
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="end" />
                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker2').datetimepicker({
                        defaultDate: '<?php print $result['date'].' '.$result['end']; ?>',
                        format: 'HH:mm',
                        stepping: 5
                    });
                });
            </script>
        </div>

        <div class="form-group">
            <label class="item">場所</label>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-1" name="location" value="居室"
                <?php if($location=='居室'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-1">居室</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-2" name="location" value="F棟"
                <?php if($location=='F棟'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-2">F棟</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-3" name="location" value="C棟"
                <?php if($location=='C棟'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-3">C棟</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-4" name="location" value="住之江"
                <?php if($location=='住之江'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-4">住之江</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-5" name="location" value="貝塚"
                <?php if($location=='貝塚'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-5">貝塚</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-6" name="location" value="加西"
                <?php if($location=='加西'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-6">加西</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-7" name="location" value="姫路"
                <?php if($location=='姫路'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-7">姫路</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-8" name="location" value="姫路"
                <?php if($location=='PENA'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-8">PENA</label>
            </div>
            <div class="form-inline">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="custom-radio-9" name="location" value="その他"
                    <?php if($location=='その他'){ print 'checked';} ?>>
                    <label class="custom-control-label" for="custom-radio-9">その他　記入⇛</label>
                </div>
                    <input class="form-control" type="text" name="other" value=<?php print $other; ?> >
            </div>
        </div>

        <div>
            <label class="item">自由記述（30文字まで）</label><br>
            <textarea style="width:200px;" class="form-control" name="remarks"><?php print $result['remarks']; ?></textarea>
        </div>
        <br>

        <div><label class="item">勤務状態選択で登録</label></div>
        <input type="hidden" name="date" value="<?php print $result['date']; ?>">
        <div class="row">
            <button type="submit" class="mr-2 btn btn-outline-dark btn-lg <?php if($result['status']=='守口' && $result['flag']==1){print 'active';} ?>" name="status" value="守口">守口</button>
            <button type="submit" class="mr-2 btn btn-success btn-outline-dark btn-lg text-white <?php if($result['status']=='在宅' && $result['flag']==1){print 'active';} ?>" name="status" value="在宅">在宅</button>
            <button type="submit" class="mr-2 btn btn-warning btn-outline-dark btn-lg <?php if($result['status']=='出張' && $result['flag']==1){print 'active';} ?>" name="status" value="出張">出張</button>
            <button type="submit" class="mr-2 btn btn-danger btn-outline-dark btn-lg text-white <?php if($result['status']=='終業' && $result['flag']==1){print 'active';} ?>" name="status" value="終業">終業</button>
        </div>
        <div class="row">
            <button type="submit" class="mr-2 mt-2 btn btn-danger btn-outline-dark btn-lg text-white <?php if($result['status']=='年休' && $result['flag']==1){print 'active';} ?>" name="status" value="年休">年休</button>
            <button type="submit" class="mr-2 mt-2 btn btn-danger btn-outline-dark btn-lg text-white <?php if($result['status']=='午前休' && $result['flag']==1){print 'active';} ?>" name="status" value="午前休">午前休</button>
            <button type="submit" class="mr-2 mt-2 btn btn-danger btn-outline-dark btn-lg text-white <?php if($result['status']=='午後休' && $result['flag']==1){print 'active';} ?>" name="status" value="午後休">午後休</button>
        </div>

    </form>

    <div class="back">
        <br>
        <input type="button" onclick="history.back()" value="戻る">
    </div>
<div>

</body>
</html>
