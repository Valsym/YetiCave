<?php
function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
}
function numberSum($sum) {
    $sum = ceil($sum);
    if ($sum > 1000) {
        $sum = number_format($sum, 0, '', ' ');
    }
    return $sum . ' ₽';
}

function getDtRange($datetime) {
    date_default_timezone_set("Europe/Moscow");
    if (!is_numeric($datetime) ) {
        $datetime = strtotime($datetime);
    }
    $diff = $datetime - time();
    if ($diff <= 0) return [0, 0];
    $hours = floor($diff / 3600);
    $minutes = floor(($diff - $hours * 3600) / 60);
    $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
    $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
    return [$hours, $minutes];
}

function udate_img_path()
{
    $sql = "select id, img from lots";
    $res = mysqli_query($con, $sql);
    if (!$res) {
        $error = mysqli_error($con);
        print($error);
    }
    $imgs = mysqli_fetch_all($res, MYSQLI_ASSOC);
//print_r($imgs);
    foreach ($imgs as $img) {
        $old_img = $img['img'];
        $id = $img['id'];
        if (!str_contains($old_img, 'img')) {
            echo "\n id=$id - continue";
            continue;
        }
        $new_str = str_replace('img', 'uploads', $old_img);
        $sql = "update lots set img = '$new_str' where id = $id";
        echo "\nnew_str=$new_str";
        $res = mysqli_query($con, $sql);
        if (!$res) {
            $error = mysqli_error($con);
            print("\n".$error);
        } else {
            echo " - OK!";
        }
//    exit;
    }
}

/**
 * Проверяет что введенное число целое и больше ноля
 * @param $num - число введенное пользователем
 * @return string|void|null - текст сообщение об ошибке или null
 */
function validate_number($num): ?string {
    if (!empty($num)) {
        $num = (int) $num;
        if (is_int($num) && $num > 0) {
            return null;
        }
        return "Введите целое число больше ноля";
    }
}

/**
 * Возвращает ассоциативный массив категорий
 * @param $con - ресурс соединения с БД
 * @return array|string
 */
function get_categories($con, $cat_table) {
    $sql = "select id, name from $cat_table";
    $res = mysqli_query($con, $sql);
    if (!$res) {
        return mysqli_error($con);
    } else {
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
}

/**
 * Проверяет поле категирии на соответсвие списку категорий
 * @param $cat_id - категория, которую ввел пользователь
 * @param $cat_ids - список существующих категорий
 * @return string|void - текст ошибки
 */
function validate_category($cat_id, $cat_ids) {
    if (!in_array($cat_id, $cat_ids)) {
        return "Указана несуществующая категория";
    }
}

/**
 * Проверяет валидность даты завершения лота
 * @param $date введенная пользователем дата
 * @return string|void строка ошибки
 */
function validate_date($date) {
    if (is_date_valid($date)) {

    } else {
        return "Содержимое поля «дата завершения» должно быть датой в формате «ГГГГ-ММ-ДД»";
    }
}
?>