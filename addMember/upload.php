<?php
$flg = 0;

if (is_uploaded_file($_FILES["upFile"]["tmp_name"])) {
    $file_tmp_name = $_FILES["upFile"]["tmp_name"];
    $file_name = $_FILES["upFile"]["name"];
  
    //拡張子を判定
    if (pathinfo($file_name, PATHINFO_EXTENSION) != 'csv') {
      print 'CSVファイルのみ対応しています。';
      $flg = 1;
    } else {
      //ファイルをdataディレクトリに移動
      if (move_uploaded_file($file_tmp_name, "../data/csv/" . $file_name)) {
        //後で削除できるように権限を644に
        chmod("../data/csv/" . $file_name, 0644);
        //$msg = $file_name . "をアップロードしました。";
        $file = '../data/csv/'.$file_name;
        $fp   = fopen($file, "r");
  
        //配列に変換する
        while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
          $member_list[] = mb_convert_encoding($data, 'UTF-8', 'sjis-win');
        }
        fclose($fp);
        //ファイルの削除
        //unlink('../../data/uploaded/'.$file_name);
      } else {
        print "ファイルをアップロードできません。";
        $flg = 1;
      }
    }
  } else {
    print "ファイルが選択されていません。";
    $flg = 1;
  }

if($flg == 1){
  print '<center>';
  print '<input type="button" onclick="history.back()" value="戻る">';
  print '</center>';
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

<form method="post" action="add_member_done.php">
  <div class="form-group">
    <div class="col-6">
      <table class="table table-bordered">
        <thead class="table-info">
				  <tr>
					  <th>PinNo.</th>
            <th>名前</th>
            <th>順番</th>
          <tr>
        </thead>
        <tbody>
          <?php
          foreach ($member_list as $member){ ?>
            <tr>
              <td><?php print $member[0]; ?></td>
              <td><?php print $member[1]; ?></td>
              <td><?php print $member[2]; ?></td>
              <!-- <input type="hidden" name="memberList[<?php //print $member[0]; ?>]" value="<?php //print $member[1]; ?>"> -->
              <input type="hidden" name="pinno[]" value="<?php print $member[0]; ?>">
              <input type="hidden" name="name[]" value="<?php print $member[1]; ?>">
              <input type="hidden" name="row[]" value="<?php print $member[2]; ?>">
            <tr>
          <?php
          } ?>
        </tbody>
      </table>
    </div>

    <div class="ml-3 form-inline">
      <select class="form-control" name="department">
        <option value="" selected>部署選択</option>
        <option value="kikaku">モノづくり企画部</option>
        <option value="sfd">スマートファクトリー開発部</option>
        <option value="senko">先行開発部</option>
        <option value="koho1">工法開発部（守口）</option>
        <option value="koho2">工法開発部（住之江）</option>
      </select>

      <button type="submit" class="ml-2 btn btn-primary " style="height: 40px">登　録</button>

    </div>
  </div>

</form>
<br>
<div class="text-center">
  <a href="#" onclick="history.back()">戻る</a>
</div>
</body>
</html>