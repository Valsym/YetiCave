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

$search = trim(mysqli_real_escape_string($con, $_GET['search']));
if ($search) {
    $items_count = get_count_lots($con, $search);
    $page_items = 3;
    $curr_page = $_GET['page'] ?? 1;
    $page_count = ceil($items_count/$page_items);
    $pages = range(1, $page_count);
    $offset = ($curr_page - 1) * $page_items;
    $goods = get_found_lots($con, $search, $page_items, $offset);

    $cats = get_categories($con, 'categories');

    $pageContent = include_template('search.php',
        [
            'cats' => $cats,
            'search' => $search,
            'goods' => $goods,
            'page_count' => $page_count,
            'curr_page' => $curr_page,
            'pages' => $pages,
        ]);

    $pageLayout = include_template('layout.php', [
        'title' => 'Результаты поиска',
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'content' => $pageContent,
        'cats' => $cats]);

    print($pageLayout);
}