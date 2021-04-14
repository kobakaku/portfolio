<?php

require 'db_connection.php';

// ユーザー入力なし query
$sql = 'select * from contacts where id = 2';
$stmt = $pdo->query($sql);

$result = $stmt->fetchall();

echo '<pre>';
var_dump($result);
echo '</pre>';

// ユーザー入力あり prepare bind execute
$sql = 'select * from contacts where id = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue('id', 3, PDO::PARAM_INT);
$stmt->execute();
$result2 = $stmt->fetchall();
echo '<pre>';
var_dump($result2);
echo '</pre>';