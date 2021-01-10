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
$status = array('守口', '在宅', '出張', '年休', '合計(人)');

$department = $_SESSION['department'];

if(isset($_GET['from'])==false){
    $datetime = new DateTime();
    $from = $datetime -> format('Y-m-d');
    $datetime -> modify('+30 days');
    $to = $datetime -> format('Y-m-d');
} else {
    $from = str_replace('/', '-', $_GET['from']);
    $to = str_replace('/', '-', $_GET['to']);
}

try{

    $pdo = new PDO("mysql:dbname=kodo;host=$servername", $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "SELECT date, COUNT(*) as '合計(人)', COUNT(status='守口' or null) as '守口', COUNT(status='在宅' or null) as '在宅',
        COUNT(status='出張' or null) as '出張', COUNT(status in('年休','午前休','午後休') or null) as '年休' FROM plan where flag=0 and date between ? and ? GROUP BY date";
	$stmt = $pdo->prepare($sql);
	$data[0] = $from;
	$data[1] = $to;
    $stmt->execute($data);

    $result = $stmt->fetchAll();


    $sql = "SELECT date, COUNT(*) as '合計(人)', COUNT(status='守口' or null) as '守口', COUNT(status='在宅' or null) as '在宅',
        COUNT(status='出張' or null) as '出張', COUNT(status in('年休','午前休','午後休') or null) as '年休' FROM plan where flag=0 and date between ? and ? GROUP BY WEEK(date)";
	$stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    $result_week = $stmt->fetchAll();

    $pdo = null;

    //$_SESSION['csv'] = $result;

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
<h3>出勤率確認</h3>
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
                    defaultDate: '<?php print $from; ?>',
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
                    defaultDate: '<?php print $to; ?>',
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

<div class="form-inline">
    <h4>日集計&emsp;</h4>
    <a id="download" href="#" class="btn btn-primary" download="rate.csv" onclick="handleDownload('table1', 'rate_day.csv')">csvダウンロード</a><br><br>
</div>
<table id="table1" class="table table-bordered table-striped">
<thead class="table-info">
	<tr>
    <th style="width:150px">勤務状態</th>
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
    <th>出勤率(出張含)%</th>
    <?php
    for ($i=0; $i < count($result); $i++) { ?>
        <td><?php print round(((intval($result[$i]['守口']) + intval($result[$i]['出張'])) / intval($result[$i]['合計(人)']) * 100), 2); ?></td>
    <?php
    } ?>
    </tr>

    <tr>
    <th>守口出社%</th>
    <?php
    for ($i=0; $i < count($result); $i++) { ?>
        <td><?php print round((intval($result[$i]['守口']) / intval($result[$i]['合計(人)']) * 100), 2); ?></td>
    <?php
    } ?>
    </tr>

    <tr>
    <th>在宅率%</th>
    <?php
    for ($i=0; $i < count($result); $i++) { ?>
        <td><?php print round((intval($result[$i]['在宅']) / intval($result[$i]['合計(人)']) * 100), 2); ?></td>
    <?php
    } ?>
    </tr>

    <tr>
    <th></th>
    <?php
    for ($i=0; $i < count($result); $i++) { ?>
        <td>
            <!-- <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">確認</button> -->
            <a href="#" class="check" data-toggle="modal" data-target="#memberCheck<?php print $i; ?>">確認</a>
            <div class="modal fade" id="memberCheck<?php print $i; ?>" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><?php print date('m/d', strtotime($result[$i]['date'])); ?>　登録状況確認</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php $check_list = get_member_check($result[$i]['date'], $department); ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 30%">状態</th>
                                        <th style="width: 70%">名前</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>
                                            <button type="button" class="btn btn-outline-dark" role="button">守口</button>
                                        </th>
                                        <td>
                                            <?php print join(',', $check_list['守口']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                        <button type="button" class="btn btn-success btn-outline-dark text-white" role="button">在宅</button>
                                        </th>
                                        <td>
                                            <?php print join(',', $check_list['在宅']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                        <button type="button" class="btn btn-warning btn-outline-dark" role="button">出張</a>
                                        </th>
                                        <td>
                                            <?php print join(',', $check_list['出張']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                        <button type="button" class="btn btn-danger btn-outline-dark text-white" role="button">年休</button>
                                        </th>
                                        <td>
                                            <?php print join(',', $check_list['年休']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <button type="button" class="btn btn-secondary btn-outline-dark text-white" role="button">未登録</button>
                                        </th>
                                        <td>
                                            <?php
                                            //foreach($check_list['未登録'] as $mb){
                                            //    echo $mb.',';
                                            //}
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    <?php
    } ?>
    </tr>

</tbody>

</table>


<div class="container">
<div class="form-inline">
    <h4>週集計&emsp;</h4>
    <a id="download" href="#" class="btn btn-primary" download="rate.csv" onclick="handleDownload('table2', 'rate_week.csv')">csvダウンロード</a><br><br>
</div>
<table id="table2" class="table table-bordered table-striped" style="table-layout:fixed;">
<thead class="table-info">
	<tr>
    <th>勤務状態</th>
		<?php
        for ($i=0; $i < count($result_week); $i++) { ?>
            <th><?php print date('m/d', strtotime($result_week[$i]['date'])); ?></th>
             
        <?php
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
        for ($i=0; $i < count($result_week); $i++) { ?>
                <td><?php print $result_week[$i][$st]; ?></td>
        <?php
        } ?>
    </tr>
    <?php
    } ?>

    <tr>
    <th>出勤率(出張含)%</th>
    <?php
    for ($i=0; $i < count($result_week); $i++) { ?>
        <td><?php print round(((intval($result_week[$i]['守口']) + intval($result_week[$i]['出張'])) / intval($result_week[$i]['合計(人)']) * 100), 2); ?></td>
    <?php
    } ?>
    </tr>

    <tr>
    <th>守口出社%</th>
    <?php
    for ($i=0; $i < count($result_week); $i++) { ?>
        <td><?php print round((intval($result_week[$i]['守口']) / intval($result_week[$i]['合計(人)']) * 100), 2); ?></td>
    <?php
    } ?>
    </tr>

    <tr>
    <th>在宅率%</th>
    <?php
    for ($i=0; $i < count($result_week); $i++) { ?>
        <td><?php print round((intval($result_week[$i]['在宅']) / intval($result_week[$i]['合計(人)']) * 100), 2); ?></td>
    <?php
    } ?>
    </tr>

</tbody>
</div>
</table>



<div class="text-center">
    <a href="../main/main.php?department=<?php print $department; ?>">戻る</a>
</div>

<script>

function handleDownload(tb, nm) {
        var bom = new Uint8Array([0xEF, 0xBB, 0xBF]);//文字コードをBOM付きUTF-8に指定
        var table = document.getElementById(tb);//id=table1という要素を取得
        var data_csv="";//ここに文字データとして値を格納していく

        for(var i = 0;  i < table.rows.length; i++){
          for(var j = 0; j < table.rows[i].cells.length; j++){
            data_csv += table.rows[i].cells[j].innerText;//HTML中の表のセル値をdata_csvに格納
            if(j == table.rows[i].cells.length-1) data_csv += "\n";//行終わりに改行コードを追加
            else data_csv += ",";//セル値の区切り文字として,を追加
          }
        }

        var blob = new Blob([ bom, data_csv], { "type" : "text/csv" });//data_csvのデータをcsvとしてダウンロードする関数
        if (window.navigator.msSaveBlob) { //IEの場合の処理
            window.navigator.msSaveBlob(blob, nm); 
            //window.navigator.msSaveOrOpenBlob(blob, "test.csv");// msSaveOrOpenBlobの場合はファイルを保存せずに開ける
        } else {
            document.getElementById("download").href = window.URL.createObjectURL(blob);
        }

        delete data_csv;//data_csvオブジェクトはもういらないので消去してメモリを開放
    }
    //ここまでCSV出力＆ダウンロード
</script>


<br>
</body>
</html>