<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';

if (!$is_auth) {
    page_code_error(404, $con, $is_auth, $user_name, $cats);
//    http_response_code(404);
    exit;
}
//$user_id = $_SESSION['user']['id'];
$cats = get_categories($con, 'categories');
$bets = get_bets($con, $user_id);

$pageContent = include_template('my-bets.php',
    [
        'cats' => $cats,
        'bets' => $bets,
        'is_auth' => $is_auth,
        'error' => $error ?? ''
    ]);

$pageLayout = include_template('layout.php', [
    'title' => 'Мои ставки',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $pageContent,
    'cats' => $cats
]);

print($pageLayout);