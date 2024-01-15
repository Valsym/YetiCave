<?php
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';
require_once 'config/config.php';
require('vendor/autoload.php');
function winner($wins)
{
    [$con, $log, $pass, $mail] = [$wins['con'], $wins['log'], $wins['pass'], $wins['mail']];
// Найти все лоты без победителей, дата истечения которых меньше или равна текущей дате.
    $old_lots = get_old_lots($con);

    foreach ($old_lots as $lot) {
        $lot_id = $lot['id'];
        // Для каждого такого лота найти последнюю ставку.
        $last_bet = get_last_bet($con, $lot_id);
        if ($last_bet) {
            $user_name = $last_bet['user_name'];
            console_log("\n Лот №$lot_id (" . $lot['title'] . ") со ставкой " .
                format_num($last_bet['price_bet']) .
                " выиграл " . $last_bet['user_name'] . "!");
            // Записать в лот победителем автора последней ставки.
            $winner_id = $last_bet['user_id'];
            $sql = "update lots set winner_id = $winner_id where id = $lot_id";
            $res = mysqli_query($con, $sql);
            if (!$res) {
                $error = mysqli_error($con);
                console_log("\n" . $error);
            } else {
                console_log(" winner_id = $winner_id - OK!");
                $dsn = "smtp://$log:$pass@smtp.yandex.ru:465?encryption=SSL";
                $transport = Transport::fromDsn($dsn);
                $mailer = new Mailer($transport);
                $mailfrom = $mailto = $mail; // должно быть $last_bet['email'], если указаны реальные
                // Формирование сообщения
                $email = (new Email())
                    ->to("$mailto")
                    ->from("$mailfrom")
                    ->subject("Уведомление от сервиса «Yeti.cave»")
                    ->text("Уважаемый $user_name. Поздравляем! \nВаша ставка победила в лоте №$lot_id (" .
                        $lot['title'] . "). Ваша ставка " .
                        format_num($last_bet['price_bet']));
                // Отправка сообщения
                $mailer->send($email);
            } // if ($res)
        } // if ($last_bet)
        else {
            console_log("\n Лот №$lot_id - нет просроченных ставок");
        }
    } // foreach
}