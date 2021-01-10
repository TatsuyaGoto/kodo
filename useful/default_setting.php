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

$date = date("Y-m-d");

$servername = "localhost";
$username = "root";
$password = "";

try{
    //$pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
	//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//$sql = "SELECT * from setting where pinno =?";
            
	//$stmt = $pdo->prepare($sql);
	//$data[0] = $_SESSION['pinno'];
	//$stmt->execute($data);
		
    //$result = $stmt->fetch(PDO::FETCH_ASSOC);
        
    //if($result == ''){            
        //$result['status'] = '';
        //$result['start'] = '08:30:00';
        //$result['end'] = '17:00:00';
        //$result['location'] = '';
    //}

    $result = get_default($_SESSION['pinno']);

    if($result['location']=='居室' || $result['location']=='住之江' || $result['location']=='貝塚' || $result['location']=='加西'
     || $result['location']=='姫路' || $result['location']=='F棟' || $result['location']=='C棟' || $result['location']=='PENA' || $result['location']==''){
        $other = '';
    } else {
        $other = $result['location'];
        $result['location'] = 'その他';
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
<div class="container">
<h3>デフォルト設定編集</h3>
<br>
<p>デフォルトで表示させる項目を設定できます</p>

<form method="post" action="default_setting_check.php">

    <div class="form-group">
        <label class="item">初期画面表示</label>
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="custom-radio-dp2" name="display" value=2
            <?php if($result['display']==2){ print 'checked';} ?>>
            <label class="custom-control-label" for="custom-radio-dp2">当日全体表示：　メンバーの当日予定を１画面で表示</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="custom-radio-dp1" name="display" value=1
            <?php if($result['display']==1){ print 'checked';} ?>>
            <label class="custom-control-label" for="custom-radio-dp1">1週間縦表示：　メンバーの1週間の予定を縦に並べて表示</label>
        </div>
    </div>

    <div class="form-group">
        <label class="item">勤務状態</label>
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="custom-radio-st1" name="status" value="守口"
            <?php if($result['status']=='守口'){ print 'checked';} ?>>
            <label class="custom-control-label" for="custom-radio-st1">守口</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="custom-radio-st2" name="status" value="在宅"
            <?php if($result['status']=='在宅'){ print 'checked';} ?>>
            <label class="custom-control-label" for="custom-radio-st2">在宅</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="custom-radio-st3" name="status" value="出張"
            <?php if($result['status']=='出張'){ print 'checked';} ?>>
            <label class="custom-control-label" for="custom-radio-st3">出張</label>
        </div>
    </div>

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
                        defaultDate: '<?php print $date.' '.$result['start']; ?>',
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
                        defaultDate: '<?php print $date.' '.$result['end']; ?>',
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
                <?php if($result['location']=='居室'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-1">居室</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-2" name="location" value="F棟"
                <?php if($result['location']=='F棟'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-2">F棟</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-3" name="location" value="C棟"
                <?php if($result['location']=='C棟'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-3">C棟</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-4" name="location" value="住之江"
                <?php if($result['location']=='住之江'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-4">住之江</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-5" name="location" value="貝塚"
                <?php if($result['location']=='貝塚'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-5">貝塚</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-6" name="location" value="加西"
                <?php if($result['location']=='加西'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-6">加西</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-7" name="location" value="姫路"
                <?php if($result['location']=='姫路'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-7">姫路</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="custom-radio-8" name="location" value="姫路"
                <?php if($result['location']=='PENA'){ print 'checked';} ?>>
                <label class="custom-control-label" for="custom-radio-8">PENA</label>
            </div>
            <div class="form-inline">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="custom-radio-9" name="location" value="その他"
                    <?php if($result['location']=='その他'){ print 'checked';} ?>>
                    <label class="custom-control-label" for="custom-radio-9">その他　記入⇛</label>
                </div>
                    <input class="form-control" type="text" name="other" value=<?php print $other; ?> >
            </div>
        </div>



    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-lg">登　録</button><br>
        <a href="#" onclick="history.back()">戻る</a>
    </div>
</form>
</div>
<br>
</body>
</html>