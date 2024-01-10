<?php
use Imagine\Image\Box;
use Imagine\Gd\Imagine;
//Источник: https://internet-34.ru/ispolzovanie-biblioteki-imagine-dlya-raboty-s-izobrazeniyami-na-php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';

//udate_img_path($con);
//add_img_into_bets($con);
require('vendor/autoload.php');

$imagine = new Imagine();
//$img = Imagine\Gd\Imagine::open(__DIR__ . "uploads/" . "lot-1.jpg");
//Источник: https://internet-34.ru/ispolzovanie-biblioteki-imagine-dlya-raboty-s-izobrazeniyami-na-php

$img = $imagine->open(__DIR__ . "/uploads/" . "659d873b95d32.jpg");
$box = new Box(54, 40);
$img->resize($box);
$bet_img = "rate-123.jpg";
$img->save(__DIR__ . "/uploads/" . $bet_img);

if (file_exists(//'c:/OSPanel/domains/yeti.cave/public_html/'
    __DIR__ . "/uploads/" . $bet_img)) {
    echo "\n$bet_img - OK";
} else {
    echo "\n$bet_img - error";
}