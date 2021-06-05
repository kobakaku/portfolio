<?php 
session_start();
require('dbconnect.php');

$id = $_REQUEST['id'];

if (!empty($id)) {
    $members = $db->prepare('SELECT * FROM posts WHERE id=?');
    $members->execute(array($id));
    $member = $members->fetch();

    if ($member['member_id'] === $_SESSION['id']) {
        $del = $db->prepare('DELETE FROM posts WHERE id=?');
        $del->execute(array($id));
        header('Location: index.php');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }

    
} else {
    header('Location: index.php');
    exit();
}