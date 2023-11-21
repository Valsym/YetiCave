<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';

//$res = getDtRange('2023-11-24');
//echo "\nres=";
//print_r($res);
//exit;

$is_auth = rand(0, 1);
$user_name = 'User3548'; // укажите здесь ваше имя

$pageContent = include_template('main.php', ['cats' => $cats, 'lots' => $lots]);

$pageLayout = include_template('layout.php', [
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $pageContent,
    'cats' => $cats]);

print($pageLayout);

?>