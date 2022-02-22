<?php
session_start();
require_once('../library.php');

// 変更ボタンが押されてこの画面に遷移してきたとき
if (isset($_GET['action']) && $_GET['action'] === 'rewrite') {
  $form = $_SESSION['form'];
} else {
  $form = [
    'name' => '',
    'email' => '',
    'password' => ''
  ];
}

$error = [];

// サーバーからPOSTリクエストが送られてきたとき
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $form['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  if ($form['name'] === '') {
    $error['name'] = 'blank';
  } else {
    if (strlen($form['name']) > 50) {
      $error['name'] = 'length';
    }
  }
  
  $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  if ($form['email'] === '') {
    $error['email'] = 'blank';
  } else {
    // メールアドレスが重複していないか確認
      $db = dbconnect();
      $stmt = $db->prepare('SELECT COUNT(*) FROM members WHERE email=?');
      if (!$stmt) {
        die($db->error);
      }
      $stmt->bind_param('s', $form['email']);
      $success = $stmt->execute();
      if (!$success) {
        die($db->error);
      }
      $stmt->bind_result($cnt);
      $stmt->fetch();
      if ($cnt > 0) {
        $error['email'] = 'duplicate';
      }
  }

  $form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  if ($form['password'] === '') {
    $error['password'] = 'blank';
  } else {
    if (strlen($form['password']) < 4) {
      $error['password'] = 'length';
    }
  }

  // 入力に問題が無いとき、確認画面に移動
  if (empty($error)) {
    $_SESSION['form'] = $form;
    header('location: check.php');
    exit();
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
  <h1>アカウント登録画面</h1>
  <form action="" method="post">
    <label>
      <h3>名前</h3>
      <input type="text" name="name" size="35" value="<?php echo h($form['name']) ?>"/>
    </label>
    <?php if (isset($error['name']) && $error['name'] === 'blank'): ?>
      <p>名前を入力してくだいさい</p>
    <?php endif; ?>
    <?php if (isset($error['name']) && $error['name'] === 'length'): ?>
    <p>名前は50文字以内にしてください</p>
    <?php endif; ?>
    <label>
      <h3>メールアドレス</h3>
      <input type="text" name="email" size="35" value="<?php echo h($form['email']) ?>"/>
    </label>
    <?php if (isset($error['email']) && $error['email'] === 'blank'): ?>
      <p>メールアドレスを入力してください</p>
    <?php endif;?>
    <?php if (isset($error['email']) && $error['email'] === 'duplicate'): ?>
      <p>そのメールアドレスはすでに使用されています</p>
    <?php endif; ?>
    <label>
      <h3>パスワード</h3>
      <input type="password" name="password" size="35" value="<?php echo h($form['password']) ?>"/>
    </label>
    <?php if (isset($error['password']) && $error['password'] === 'blank'): ?>
      <p>パスワードを入力してください</p>
    <?php endif ?>
    <?php if (isset($error['password']) && $error['password'] === 'length'): ?>
    <p>パスワードは4文字以上に設定してください</p>
    <?php endif; ?>
    <div>
      <input type="submit" value="内容確認">
    </div>
  </form>
</body>
</html>