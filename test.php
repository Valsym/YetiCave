<?php
//echo "\n test";
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';
require_once("config/config.php");
require('vendor/autoload.php');

// https://github.com/symfony/mailer
// Конфигурация траспорта
$dsn = "smtp://$log:$pass@smtp.yandex.ru:465?encryption=SSL";

$transport = Transport::fromDsn($dsn);
$mailer = new Mailer($transport);


$mailto = $mail;
//echo "\n$k -> $mailto -> $mes";
// Формирование сообщения
$email = (new Email())
    ->to("$mailto")
    ->from("$mailfrom")
    ->subject("Уведомление от сервиса «Yeti.cave»")
    ->text("test");
// Отправка сообщения
$mailer->send($email);
