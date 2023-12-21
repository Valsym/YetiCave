<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';

session_start();
if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user_name = $_SESSION['user']['user_name'];
} else {
    $is_auth = false;
    $user_name = '';
}

if ($con == false) {
    $error = mysqli_connect_error();
    print($error);
    exit;
}

$lot_id = $_GET['lot'] ?? 0;
if (!$lot_id) {
    http_response_code(404);
    exit;
}
// Проверяем существование параметра запроса с ID лота.
$sql = "select id from lots where lots.id = $lot_id";
$res = mysqli_query($con, $sql);
$records_count = mysqli_num_rows($res);
if (0 === $records_count) {
    http_response_code(404);
    exit;
}


$sql = get_query_lot($lot_id);
$res = mysqli_query($con, $sql);
//    print_r($res);
if (!$res) {
    $error = mysqli_error($con);
    print_r($error);
    http_response_code(404);
    exit;
} else {
    $lot = mysqli_fetch_array($res);
}

$cats = get_categories($con, 'categories');


$pageContent = include_template('lot.php',
    ['cats' => $cats, 'lot' => $lot, 'is_auth' => $is_auth]);

$pageLayout = include_template('layout.php', [
    'title' => $lot['title'],
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $pageContent,
    'cats' => $cats]);

print($pageLayout);