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
    $user_id = $_SESSION['user']['id'];
} else {
    $is_auth = false;
    $user_name = '';
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
$curr_price= get_current_price($con, $lot_id);
$curr_price = $curr_price ? $curr_price : $lot['start_price'];
//echo "  curr_price=$curr_price  ";
//print_r($lot);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cost = $_POST['cost'] ?? 0;
    $cost = (int) mysqli_real_escape_string($con, $cost);
    if (!$is_auth) {
        $error = "Пользователь должен быть залогинен";
    } else if ($user_id === $lot['author_id']) {
        $error = "Инициатор лота не может делать ставки";
    } else {
        if (!$cost) {
            $error = "Не заполнено поле «ваша ставка»";
        } else {
            $error = validate_number($cost);
            if (!$error) {
                $sql = "select price_bet from bets where lot_id = $lot_id";
                $res = mysqli_query($con, $sql);
//                print_r($res);
                if (!$curr_price || $lot['start_price'] === $curr_price) { // Первая ставка
                    if ($cost < $lot['start_price'] + $lot['step']) {
                        $error = "Ставка должна быть больше или равна минимальной ставки";
                    }
                } else {
//                    [$price_bet] = mysqli_fetch_array($res);
                    if ($cost < $curr_price + $lot['step']) {
                        $error = "Значение должно быть больше или равно Мин.ставка";
                    }
                }

            }
            if(!$error) {
                // Сохраняем ставку в БД
                if ($lot['start_price'] === $curr_price) {
//                    if (isset($lot['img'])) {
//                        $bet_img = make_bet_img($con, $lot['id'], $lot['img']);
//                    } else {
                        $bet_img = '';
                    console_log("\nФайл bet_img='' ");
//                    }
                } else {
                    $bet_img = 'uploads/rate'.$lot_id.'.jpg';
                    if (!file_exists(//'c:/OSPanel/domains/yeti.cave/public_html/'
                            __DIR__ . "/" . $bet_img)) {
                        console_log("\nФайл $bet_img не существует ");
                        //continue;
                        $bet_img = '';
                    } else {
                        console_log("\nФайл $bet_img существует");
                    }
                }
                $sql = "insert into bets (price_bet,user_id, lot_id, bet_img) 
                            values (?, ?, ?, ?)";
                $stmt = db_get_prepare_stmt($con, $sql, [$cost, $user_id, $lot['id'], $bet_img]);
//                echo "  Try inser into bets";
                $res = mysqli_stmt_execute($stmt);
                if ($res) {
                    $curr_price = $cost;
                }
            }

        }
    }
}

if ($is_auth) {
    $history_bets = get_history_bets($con, $lot_id);

}


$pageContent = include_template('lot.php',
    [
        'cats' => $cats,
        'lot' => $lot,
        'curr_price' => $curr_price,
        'is_auth' => $is_auth,
        'history_bets' => $history_bets ?? [],
        'error' => $error ?? ''
    ]);

$pageLayout = include_template('layout.php', [
    'title' => $lot['title'],
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $pageContent,
    'cats' => $cats]);

print($pageLayout);