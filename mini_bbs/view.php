<?php
session_start();
require('dbconnect.php');

function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

if (!empty($_REQUEST['id'])) {
  $posts = $db->prepare('SELECT m.name, m.picture, p.* 
  FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
  $posts->bindValue(1, $_REQUEST['id'], PDO::PARAM_INT);
  $posts->execute();
  $post = $posts->fetch();
} else {
  header('Location: index.php');
  exit();
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ひとこと掲示板</h1>
  </div>
  <div id="content">
  <p>&laquo;<a href="index.php">一覧にもどる</a></p>
  <?php if (!empty($post)): ?>
    <div class="msg">
    <img src="member_picture/<?= h($post['picture']); ?>" width="100" height="120" alt="" />
    <p><?= h($post['message']); ?><span class="name">（<?= h($post['name']); ?>）</span></p>
    <p class="day"><?= h($post['created']); ?></p>
    </div>
  <?php else: ?>
	<p>その投稿は削除されたか、URLが間違えています</p>
  <?php endif; ?>
  </div>
</div>
</body>
</html>
