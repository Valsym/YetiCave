<?php

$cats = [
    'boards' => 'Доски и лыжи',
    'mounts' => 'Крепления',
    'boots' => 'Ботинки',
    'clothes' => 'Одежда',
    'tools' => 'Инструменты',
    'others' => 'Разное'
];

$lots = [
    ['2014 Rossignol District Snowboard', $cats['boards'], 10999, 'img/lot-1.jpg', strtotime("+2 weeks")],
    ['DC Ply Mens 2016/2017 Snowboard',	$cats['boards'],	159999,	'img/lot-2.jpg', strtotime("+1 weeks")],
    ['Крепления Union Contact Pro 2015 года размер L/XL', $cats['mounts'], 8000, 'img/lot-3.jpg', strtotime("+2 days")],
    ['Ботинки для сноуборда DC Mutiny Charocal', $cats['boots'],	10999,	'img/lot-4.jpg', strtotime("+1 days + 10 minutes")],
    ['Куртка для сноуборда DC Mutiny Charocal',	$cats['clothes'],	7500,	'img/lot-5.jpg',strtotime("+2 hours")],
    ['Маска Oakley Canopy',	$cats['others'],	5400,	'img/lot-6.jpg', strtotime("+55 minutes")],
];

?>