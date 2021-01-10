<?php
session_start();
if(isset($_SESSION['pinno'])==false){
    print '<center>';
    print 'ログインされていません<br>';
    print '<a href="../login/login.html">ログイン画面へ</a>';
    print '</center>';
    exit();
}

switch($_GET['department']){
    case 'sfd':
        header('Location: ../moni/sfd.php');
        break;
    case 'koho':
        header('Location: ../moni/koho.php');
        break;
    case 'senko':
        header('Location: ../moni/senko.php');
        break;
    case 'kikaku':
        header('Location: ../moni/kikaku.php');
        break;
    default:
        break;
}


?>