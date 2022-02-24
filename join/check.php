<?php
session_start();
require_once('../library.php');

// セッションの情報が無いときはindex.phpに移動
if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  header('Location: index.php');
  exit();
}

// データベースに登録する処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db = dbconnect();

  $password = password_hash($form['password'], PASSWORD_DEFAULT);
  $stmt = $db->prepare('INSERT INTO members (name, email, password) VALUE (?, ?, ?)');
  if (!$stmt) {
    die($db->error);
  }
  $stmt->bind_param('sss', $form['name'], $form['email'], $password);
  $success = $stmt->execute();
  if (!$success) {
    die($db->error);
  }
  unset($_SESSION['form']);

  $stmt = $db->prepare('SELECT id, name FROM members WHERE email=?');
  if (!$stmt) {
    die ($db->error);
  }
  $stmt->bind_param('s', $form['email']);
  $success = $stmt->execute();
  if (!$success) {
    die ($db->error);
  }
  $stmt->bind_result($id, $name);
  $stmt->fetch();

  // 投稿一覧画面にidと名前をセッションを通して渡す
  $_SESSION['id'] = $id;
  $_SESSION['name'] = $name;

  header('Location: ../index.php');
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
  <h1>アカウント登録 確認画面</h1>
  <h3>名前</h3>
  <p><?php echo h($form['name'])?></p>
  <h3>メールアドレス</h3>
  <p><?php echo h($form['email'])?></p>
  <h3>パスワード</h3>
  <p>
    <?php for ($i = 0; $i < strlen($form['password']); $i++): ?>
      <?php echo '*';?>
    <?php endfor; ?>
  </p>
    <form action="" method="post">
      <input type="submit" value="登録">
      <p><a href="index.php?action=rewrite">変更する</a></p>
    </form>
</body>
</html>