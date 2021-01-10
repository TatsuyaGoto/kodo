<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!-- bootstrapとcssファイル読み込み -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
<link rel="stylesheet" href="../css/home.css"></link>

<title>SFD行動予定表</title>
</head>
<body>

  <div class="container">
  <table class="table table-bordered">
    <tr>
		<th>ホット/コールド</th>
		<th>種類</th>
		<th>サイズ</th>
		<th>価格（円）</th>
	</tr>
	<tr>
		<th rowspan="2">ホット</th>
		<td>コーヒー</td>
		<td>S,M</td>
		<td>150,200</td>
	</tr>
	<tr>
		<!-- 上のセルと結合して消えるセル -->
		<td>紅茶</td>
		<td>Sサイズのみ</td>
		<td>170</td>
	</tr>
  </table>
  </div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>
