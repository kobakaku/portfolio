<?php
session_start();
require('../dbconnect.php');

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

if ($_POST) {
	if ($_POST['name'] === '') {
		$error['name'] = 'blank';
	}
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	} else {
		$error['email'] = 'miss';
	}
	
	if ($_POST['email'] === '') {
		$error['email'] = 'blank';	
	}
	if (strlen($_POST['password']) < 4) {
		$error['password'] = 'length';
	}
	if ($_POST['password'] === '') {
		$error['password'] = 'blank';
	}
	$filename = date('YmdHis') . $_FILES['image']['name'];
	$tempfile = $_FILES['image']['tmp_name'];
	if (is_uploaded_file($tempfile)) {
		$ext = substr($filename, -3);
		if ($ext != 'JPG' && $ext != 'gif' && $ext != 'png' && $ext != 'JPEG') {
			$error['image'] = 'type';
		}
	}

	//エラーの重複チェック
	if (empty($error)) {
		$statement = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$statement->execute(array($_POST['email']));
		$record = $statement->fetch();
		if ($record['cnt'] > 0) {
			$error['email'] = 'duplicate';
		}
	}
	if (empty($error)) {		
		move_uploaded_file($tempfile, '../member_picture/' . $filename);
		$_SESSION['join'] = $_POST; 
		$_SESSION['join']['image'] = $filename;
		header('Location: check.php');
		exit();
	}
	
}


if ($_REQUEST['action'] === 'rewrite' && isset($_SESSION['join'])) {
	$_POST = $_SESSION['join'];
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
<p>次のフォームに必要事項をご記入ください。</p>
<form action="" method="post" enctype="multipart/form-data">
	<dl>
		<dt>ニックネーム<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="name" size="35" maxlength="255" value="<?= h($_POST['name']); ?>" />
			<?php if ($error['name'] === 'blank'): ?>
			<p class="error">＊名前を入力してください。</p>
			<?php endif; ?>
		</dd>

		<dt>メールアドレス<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="email" size="35" maxlength="255" value="<?= h($_POST['email']); ?>" />
			<?php if ($error['email'] === 'blank'): ?>
			<p class="error">＊メールアドレスを入力してください。</p>
			<?php endif; ?>
			<?php if ($error['email'] === 'miss'): ?>
			<p class="error">＊正しい形式でメールアドレスを入力してください。</p>
			<?php endif; ?>
			<?php if ($error['email'] === 'duplicate'): ?>
			<p class="error">＊このメールアドレスは既に登録されています。新しいメールアドレスを入力してください。</p>
			<?php endif; ?>
		</dd>

		<dt>パスワード<span class="required">必須</span></dt>
		<dd>
        	<input type="password" name="password" size="10" maxlength="20" value="<?= h($_POST['password']); ?>" />
			<?php if ($error['password'] === 'blank'): ?>
			<p class="error">＊パスワードを入力してください。</p>
			<?php endif; ?>
			<?php if ($error['password'] === 'length'): ?>
			<p class="error">＊4文字以上でパスワードを入力してください。</p>
			<?php endif; ?>
        </dd>
		<dt>写真など</dt>
		<dd>
        	<input type="file" name="image" size="35" value="test"  />
			<?php if ($error['image'] === 'type'): ?>
			<p class="error">＊正しい書式で画像を選んでください。</p>
			<?php endif; ?>
			<?php if (!empty($error)): ?>
			<p class="error">＊恐れ入りますが画像を改めて指定してください。</p>
			<?php endif; ?>
		</dd>
	</dl>
	<div><input type="submit" value="入力内容を確認する" /></div>
</form>
</div>
</body>
</html>
