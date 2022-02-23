<?php
session_start();

// セッションに値が無い場合はログイン画面へ
if (empty($_SESSION)) {
  header('Location: ./login.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h1>投稿一覧</h1>
  <p><a href="./logout.php">ログアウト</a></p>
</body>
</html>