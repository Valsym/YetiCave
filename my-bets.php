<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';

if (!$is_auth) {
    http_response_code(404);
    exit;
}
$user_id = $_SESSION['user']['id'];
$cats = get_categories($con, 'categories');
$bets = get_bets($con, $user_id);
//print_r($bets);
//exit;

$pageContent = include_template('my-bets.php',
    [
        'cats' => $cats,
        'bets' => $bets,
//        'curr_price' => $curr_price,
        'is_auth' => $is_auth,
        'error' => $error ?? ''
    ]);

$pageLayout = include_template('layout.php', [
    'title' => 'Мои ставки',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $pageContent,
    'cats' => $cats]);

print($pageLayout);