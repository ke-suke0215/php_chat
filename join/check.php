<?php
session_start();
require_once('../library.php');
$form = $_SESSION['form'];
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
  <h1>アカウント登録 確認画面</h1>
  <?php var_dump($form) ?>
</body>
</html>