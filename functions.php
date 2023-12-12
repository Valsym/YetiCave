<?php

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

?>