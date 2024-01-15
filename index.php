<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';

require_once 'config/config.php';
require_once 'winner.php';

//session_start();
//if (isset($_SESSION['user'])) {
//    $is_auth = true;
//    $user_name = $_SESSION['user']['user_name'];
//} else {
//    $is_auth = false;
//    $user_name = '';
//}

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

$wins = [
    'con' => $con,
    'log' => $log,
    'pass' => $pass,
    'mail' => $mail
];

$pageContent = include_template('main.php',
    [
        'cats' => $cats,
        'lots' => $lots,
        'wins' => $wins
    ]);

$class_container = 'container';

$pageLayout = include_template('layout.php', [
    'title' => 'Главная',
    'class_container' => $class_container,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $pageContent,
    'cats' => $cats
]);

print($pageLayout);

?>