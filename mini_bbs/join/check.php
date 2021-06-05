<?php 

session_start();
require('../dbconnect.php');

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

if (!isset($_SESSION['join'])) {
	header('Location: index.php');
	exit();
}

if (!empty($_POST['action'])) {
	$statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
	$statement->execute(array(
		$_SESSION['join']['name'], $_SESSION['join']['email'],
		password_hash($_SESSION['join']['password'], PASSWORD_DEFAULT), $_SESSION['join']['image']
	));
	unset($_SESSION['join']);
	header('Location: thanks.php');
	exit();
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>会員登録</h1>
</div>

<div id="content">
<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
<form action="" method="post">
	<!-- <input type="hidden" name="acion" value="submit" /> -->
	<dl>
		<dt>ニックネーム</dt>
		<dd>
		<?= $_SESSION['join']['name']; ?>
        </dd>
		<dt>メールアドレス</dt>
		<dd>
		<?= $_SESSION['join']['email']; ?>
        </dd>
		<dt>パスワード</dt>
		<dd>
		【表示されません】
		</dd>
		<dt>写真など</dt>
		<dd>
			<?php if (!empty($_SESSION['join']['image'])): ?>
				<img src="../member_picture/<?= h($_SESSION['join']['image']); ?>" alt="" width=150 height=220>
			<?php endif; ?>
		</dd>
	</dl>
	<div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" name="action" value="登録する" /></div>
</form>
</div>

</div>
</body>
</html>
