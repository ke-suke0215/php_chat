<?php
session_start();
require_once dirname(__FILE__).'/library.php';

// セッションに値が無い場合はログイン画面へ
if (empty($_SESSION)) {
  header('Location: ./login.php');
  exit();
} else {
  // セッションを受け取る
  $member_id = $_SESSION['id'];
  $name = $_SESSION['name'];
}

// メッセージを表示する

// データベースに接続
$db = dbconnect();

// メッセージを登録する
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
  if ($message !== '') {
    $stmt = $db->prepare('INSERT INTO post (message, member_id) VALUES(?, ?)');
    if (!$stmt) {
      die($db->error);
    }
    // 投稿の内容とメンバーidををSQLに組み込む
    $stmt->bind_param('si', $message, $member_id);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }

    // メッセージを送ったら、フォームの値をリセット
    header('Location: index.php');
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
  <h1>投稿一覧</h1>
  <p><a href="./logout.php">ログアウト</a></p>
  <form action="" method="post">
    <h3>メッセージを入力</h3>
    <input type="text" size="40" name="message"/>
    <input type="submit" value="投稿">
  </form>
  <?php
    // 投稿の一覧を取得する処理
    $stmt = $db->prepare('SELECT post.id, post.message, post.member_id, post.created, members.name FROM post, members WHERE post.member_id = members.id ORDER BY id DESC');
    if (!$stmt) {
      die($db->error);
    }
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $stmt->bind_result($id, $message, $member_id, $created, $name);
    while ($stmt->fetch()): 
  ?>
  <div>
    <p>
      <?php echo "{$name} {$message} {$created}"; ?>
    </p>
  </div>
  <?php endwhile; ?>
</body>
</html>