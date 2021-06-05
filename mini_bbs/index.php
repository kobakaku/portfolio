<?php
session_start();
require('dbconnect.php');

function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


if (!empty($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();
  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members-> fetch();
} else {
  header('Location: login.php');
  exit();
}
if (!empty($_POST)) {
  if (($_POST['message'] !== '')) {
    if ($_POST['reply_post_id'] === '') {
      $_POST['reply_post_id'] = 0;
    }
    $message = $db->prepare('INSERT INTO posts SET message=?,
    member_id=?, reply_message_id=?, created=NOW()');
    $message->execute(array(
      $_POST['message'],
      $member['id'],
      $_POST['reply_post_id']
    ));

    header('Location: index.php');
    exit();
  }
}

if (!empty($_REQUEST['res'])) {
  $ress = $db->prepare('SELECT m.name, m.picture, p.* 
  FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
  $ress->execute(array($_REQUEST['res']));
  $res = $ress-> fetch();
  $table = '@' .  h($res['name']) . '   ' .  h($res['message']);
}

$page = $_REQUEST['page'];
if ($_REQUEST['page'] === '') {
  $page = 1;
}

$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
$count = $counts->fetch();
$maxPage = ceil($count['cnt'] / 5);

$page = min($page, $maxPage);
$page = max($page, 1);

$start = ($page - 1) * 5;

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p 
WHERE m.id=p.member_id ORDER BY created DESC LIMIT ?,5');
$posts->bindValue(1, $start, PDO::PARAM_INT);
$posts->execute();



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
  	<div style="text-align: right"><a href="logout.php">ログアウト</a></div>
    <form action="" method="post">
      <dl>
        <dt><?= $member['name']; ?>さん、メッセージをどうぞ</dt>
        <dd>
          <textarea name="message" cols="50" rows="3"><?= h($table); ?></textarea>
          <input type="hidden" name="reply_post_id" value="<?= h($res['id']); ?>" />
        </dd>
      </dl>
      <div>
        <p>
          <input type="submit" value="投稿する" />
        </p>
      </div>
    </form>
<?php foreach($posts as $post): ?>
    <div class="msg">
    <img src="member_picture/<?= h($post['picture']); ?>" width="40" height="60" alt="" />
    <?= h($post['message']); ?>
    <p><span class="name">（<?= h($post['name']); ?>）</span>[<a href="index.php?res=<?= h($post['id']); ?>">Re</a>]</p>
    <p class="day"><a href="view.php?id=<?= h($post['id']); ?>"><?= h($post['created']); ?></a>
    <?php if ($post['reply_message_id'] > 0): ?>
    <a href="view.php?id=<?= h($post['reply_message_id']); ?>">
    返信元のメッセージ</a>
    <?php endif; ?>
    <?php if ($_SESSION['id'] === $post['member_id']): ?>
    [<a href="delete.php?id=<?= h($post['id']); ?>"
    style="color: #F33;">削除</a>]
    <?php endif; ?>
    </p>
    </div>
<?php endforeach; ?>
<ul class="paging">
<?php if ($page > 1): ?>
<li><a href="index.php?page=<?= $page-1; ?>">前のページへ</a></li>
<?php else: ?>
<li>前のページへ</li>
<?php endif; ?>
<?php if ($page < $maxPage): ?>
<li><a href="index.php?page=<?= $page+1; ?>">次のページへ</a></li>
<?php else: ?>
<li>次のページへ </li>
<?php endif; ?>
</ul>
  </div>
</div>
</body>
</html>
