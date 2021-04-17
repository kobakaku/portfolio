<?php

//DB接続PDO

function insertContact($request){
require 'db_connection.php';


//入力　DB保存 prepare  execute

$params = [
    'id' => null,
    'your_name' => $request['your_name'],
    'email' => $request['email'],
    'url' => $request['url'],
    'gender' => $request['gender'],
    'age' => $request['age'],
    'contact' => $request['contact'],
    'created_at' => null
];

/*$params = [
    'id' => null,
    'your_name' => 'こばかく',
    'email' => 'test@test.com',
    'url' => 'http://test.com',
    'gender' => '1',
    'age' => '2',
    'contact' => 'こんにちは',
    'created_at' => null
]; */

$count = 0;
$columns = '';
$values = '';

foreach(array_keys($params) as $key){
    if($count++ >0/*この書き方特殊*/){
        $columns .= ',';
        $values .= ',';
    }
    $columns .= $key;
    $values .= ':' . $key;
}
$sql = 'insert into contacts ('. $columns .')values('. $values .')';

//var_dump($sql);

$stmt = $pdo->prepare($sql);//プリペアードステートメント
//$stmt->bindValue('id', 3, PDO::PARAM_INT);//紐づけ
$stmt->execute($params); //実行


}