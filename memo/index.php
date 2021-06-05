<!doctype html>
<html lang="ja">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="css/style.css">

<title>PHP</title>
</head>
<body>
<header>
<h1 class="font-weight-normal">PHP</h1>    
</header>

<main>
<h2>Practice</h2>

<?php
require('dbconnect.php');

/*$count = $db->exec('INSERT INTO my_items SET maker_id=1, item_name="もも", price=210, keyword="缶詰,ピンク,甘い"');
//var_dump($db->Info());
echo $count . '件のデータを挿入しました';*/

/*$records = $db->query('SELECT * FROM my_items');
while ($record = $records->fetch()) {
    echo $record['item_name'] . "\n";
}*/

if (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) {
    $page = $_REQUEST['page'];  
} else {
    $page = 1;
}

$start = 5 * ($page - 1);

$memos = $db->prepare('SELECT * FROM memos ORDER BY id DESC LIMIT ?, 5');
$memos->bindValue(1, $start, PDO::PARAM_INT);
$memos->execute();
?>

<article>
    <?php while ($memo = $memos->fetch()): ?>
    <p><a href="memo.php?id=<?= $memo['id']; ?>"><?= mb_substr($memo['memo'], 0, 10); ?></a></p>
    <time><?= $memo['created_at']; ?></time>
    <hr>
    <?php endwhile; ?>
    <?php if ($page >= 2 ): ?>
    <a href="index.php?page=<?= $page -1; ?>"><?= $page -1; ?>ページ目へ</a>
    <?php endif; ?>
    |
    <?php 
    $counts = $db->query('SELECT COUNT(*) as cnt FROM memos');
    $count = $counts->fetch();
    $max_page = ceil($count['cnt'] / 5);
    if ($page < $max_page):
    ?>
    <a href="index.php?page=<?= $page +1; ?>"><?= $page +1; ?>ページ目へ</a>
    <?php endif; ?>
</article>


</main>
</body>    
</html>