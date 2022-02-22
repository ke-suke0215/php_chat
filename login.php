<?php
session_start();
require('./library.php');

$form = [
  $email = '',
  $password = '',
];
$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  if ($form['email'] === '') {
    $error['email'] = 'blank';
  }
  $form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  if ($form['password'] === '') {
    $error['password'] = 'blank';
  }
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
  <h1>ログイン画面</h1>
  <form action="" method="post">
    <label>
      <p>メールアドレス</p>
      <input type="text" name="email" size="35" value="<?php echo h($form['email']) ?>"/>
      <?php if (isset($error['email']) && $error['email'] === 'blank'): ?>
        <p>メールアドレスを入力してください</p>
      <?php endif; ?>
    </label>
    <label>
      <p>パスワード</p>
      <input type="password" name="password" size="35" value="<?php  echo h($form['password'])?>"/>
      <?php if (isset($error['password']) && $error['password'] === 'blank'): ?>
        <p>パスワードを入力してください</p>
      <?php endif; ?>
    </label>
    <div>
      <input type="submit" value="ログイン">
      <p><a href="./join/index.php">会員登録</a></p>
    </div>
  </form>
</body>
</html>