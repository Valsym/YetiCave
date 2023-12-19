<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';



//print("Соединение установлено");
// выполнение запросов
$sql = get_query_list_lots();
$res = mysqli_query($con, $sql);
if (!$res) {
    $error = mysqli_error($con);
} else {
    $lots = mysqli_fetch_all($res, MYSQLI_NUM);
}

$sql = "select codename, name from categories";
$res = mysqli_query($con, $sql);
if (!$res) {
    $error = mysqli_error($con);
} else {
    $cats = mysqli_fetch_all($res, MYSQLI_ASSOC);
}


$pageContent = include_template('main.php',
    ['cats' => $cats, 'lots' => $lots]);

$pageLayout = include_template('layout.php', [
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $pageContent,
    'cats' => $cats]);

print($pageLayout);

?>