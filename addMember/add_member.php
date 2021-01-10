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
<h3>メンバー追加</h3>
<br>

<form method="post" action="upload.php" enctype="multipart/form-data">

    <div class="form-group">
        <label>CSVから一括登録</label>
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="upFile" name="upFile" accept=".csv,.xlsx">
            <label class="custom-file-label" style="width:500px;" for="upFile" data-browse="参照">ファイルを選択（ドロップも可能）</label>
        </div>

    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-lg">読　込</button><br>
        <a href="#" onclick="history.back()">戻る</a>
    </div>
</form>
</div>
<br>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.js"></script>
<script>
    bsCustomFileInput.init();
</script>
</body>
</html>