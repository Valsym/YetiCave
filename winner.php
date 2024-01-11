<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';

$old_lots = get_old_lots($con);
//print_r($old_lots);
foreach ($old_lots as $lot) {
//    echo "\n";//lot=$lot";
//    print_r($lot['id']);
    $lot_id = $lot['id'];
    $last_bet = get_last_bet($con, $lot_id);
    if ($last_bet) {
//        echo " -> last_bet = ";
//        print_r($last_bet);
        echo "\n Лот №$lot_id (" . $lot['title'] . ") со ставкой " .
            format_num($last_bet['price_bet']) .
            " выиграл " . $last_bet['user_name'] . "!";
    }

}