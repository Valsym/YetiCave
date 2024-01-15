<?php
use Imagine\Image\Box;
use Imagine\Gd\Imagine;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once 'vendor/autoload.php';
require_once 'config/config.php';

/**
 * Выводит параметр в консоль, аналогично JS-функции console.log
 * @param $data
 * @return void
 */
function console_log($data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

/**
 * Преобразует число в денежный формат и добавляет знак Рубля в конце
 * @param $sum
 * @return string
 */
function format_num($sum)
{
    $sum = ceil($sum);
    if ($sum > 1000) {
        $sum = number_format($sum, 0, '', ' ');
    }
    return $sum . ' ₽';
}

/**
 * Рассчитывает сколько часов и минут осталось от текущей метки времени до заданной даты
 * @param $datetime date, string - заданные дата и время истечения лота
 * @return array|int[] массив [hours, minutes]
 */
function get_dt_range($datetime)
{
    date_default_timezone_set("Europe/Moscow");
    if (!is_numeric($datetime)) {
        $datetime = strtotime($datetime);
    }
    $diff = $datetime - time();
    if ($diff <= 0) {
        return [0, 0];
    }
    $hours = floor($diff / 3600);
    $minutes = floor(($diff - $hours * 3600) / 60);
    $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
    $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
    return [$hours, $minutes];
}

/**
 * Меняет пароль на '123456' для пользователя с указанным email
 * @param $con
 * @param $email
 * @return void
 */
function update_pass($con, $email)
{
    $sql = "select id from users as u where u.email = '$email'";
    $res = mysqli_query($con, $sql);
    echo "\n sql=$sql \nres=";
    print_r($res);
    if ($res) {
        $newpass = password_hash('123456', PASSWORD_BCRYPT);
        $sql = "update users as u set u.user_password = '$newpass' where u.email = '$email'";
        echo "\nnewpass=$newpass";
        $res = mysqli_query($con, $sql);
        if (!$res) {
            $error = mysqli_error($con);
            print("\n" . $error);
        } else {
            echo " - OK!";
        }
    }
}

function add_img_into_bets($con)
{
    $sql = "select id, lot_id from bets";
    $res = mysqli_query($con, $sql);
    if (!$res) {
        $error = mysqli_error($con);
        print($error);
    }
    $imgs = mysqli_fetch_all($res, MYSQLI_ASSOC);
    foreach ($imgs as $img) {
        $lot_id = $img['lot_id'];
        $bet_img = 'uploads/rate' . $lot_id . '.jpg';
        if (!file_exists('c:/OSPanel/domains/yeti.cave/public_html/' . $bet_img)) {
            echo "\nФайл $bet_img не существует ";
            //continue;
        } else {
            echo "\nФайл $bet_img существует";
        }
//        $url = "http://yeti.cave/".$bet_img;
//        $Headers = @get_headers($url);
//// проверяем ли ответ от сервера с кодом 200 - ОК
////        if(preg_match("|200|", $Headers[0])) { // - немного дольше :)
//        if(strpos($Headers[0], '200')) {
//            echo "Файл $url существует -> ". $Headers[0];
//        } else {
//            echo "Файл $url не найден -> ". $Headers[0];
//        }
//        print_r($Headers);
//        continue;
        $sql = "update bets set bet_img = '$bet_img' where lot_id = $lot_id";
        //echo "\nnew_str=$new_str";
        $res = mysqli_query($con, $sql);
        if (!$res) {
            $error = mysqli_error($con);
            print("\n" . $error);
        } else {
            echo " update OK!";
        }
    }
}

/**
 * Замена path img/ на uploads/ в столбце img, в таблице lots
 * @return void
 */
function udate_img_path($con)
{
    $sql = "select id, img from lots";
    $res = mysqli_query($con, $sql);
    if (!$res) {
        $error = mysqli_error($con);
        print($error);
    }
    $imgs = mysqli_fetch_all($res, MYSQLI_ASSOC);
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
            print("\n" . $error);
        } else {
            echo " - OK!";
        }
    }
}

/**
 * Создание уменьшенного изображения лота для списка Мои ставки
 *
 * @param $con ресурс соединения
 * @param $lot_id ид лота
 * @param $lot_img путь к большому изображению лота
 * @return string путь к уменьшенному изображению или пустая строка
 */
function make_bet_img($con, $lot_id, $lot_img)
{
//    require_once 'vendor/autoload.php';

    $imagine = new Imagine();

    $img = $imagine->open(__DIR__ . "/$lot_img");
    $box = new Box(54, 40);
    $img->resize($box);
    $bet_img = "rate$lot_id.jpg";
    $img->save(__DIR__ . "/uploads/" . $bet_img);

    if (file_exists(//'c:/OSPanel/domains/yeti.cave/public_html/'
        __DIR__ . "uploads/" . $bet_img)) {
        return $bet_img;
    }

    return '';
}

/**
 * Проверяет, что введенное число целое и больше ноля
 * @param $num - число введенное пользователем
 * @return string|void|null - текст сообщение об ошибке или null
 */
function validate_number(?string $num): ?string
{
    if (is_numeric($num)) {
        $num *= 1; // преобразуем строку в числовой тип
        if (is_int($num) && $num > 0) {
            return null; // ошибок нет - возвращаем null
        }

    }

    return 'Содержимое должно быть целым числом больше ноля';
}

/**
 * Возвращает ассоциативный массив категорий
 * @param $con - ресурс соединения с БД
 * @return array|string
 */
function get_categories($con, $cat_table)
{
    $sql = "select id, name, codename from $cat_table";
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
function validate_category($cat_id, $cat_ids)
{
    if (!in_array($cat_id, $cat_ids)) {
        return "Указана несуществующая категория";
    }
}

/**
 * Проверяет валидность даты завершения лота
 * @param $date введенная пользователем дата
 * @return string|void строка ошибки
 */
function validate_date($date)
{
    if (is_date_valid($date)) {
        [$hours, $minutes] = get_dt_range($date);
        if ($hours * 24 + $minutes <= 24 * 24) {
            return "Содержимое поля должно быть больше текущей даты, хотя бы на один день";
        }
    } else {
        return "Содержимое поля «дата завершения» должно быть датой в формате «ГГГГ-ММ-ДД»";
    }
}

/**
 * Проверка валидности e-mail и на существование в базе
 * @param $con ресурс соединения с БД
 * @param $email который ввел пользователь
 * @return string|null строка ошибки или null
 */
function validate_email_not_repeat($con, $email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = "select email from users where users.email = '$email'";
        $res = mysqli_query($con, $sql);
        if (!$res) {
            return null;
        }
    }
    return "Поле email не валидно или такой адрес уже существует";
}

function get_old_lots($con)
{
    $sql = "select id, title from lots as l
                where l.winner_id is null and l.date_finish <= now()";
    $res = mysqli_query($con, $sql);
    if (!$res) {
        return [];
    }
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function get_last_bet($con, $lot_id)
{
    $sql = "select price_bet, user_id, u.user_name, u.email from bets as b
                join users as u 
                    on u.id = user_id
                where b.lot_id = $lot_id";
    $res = mysqli_query($con, $sql);
    if (!$res) {
        return 0;
    }
    $bet = mysqli_fetch_array($res, MYSQLI_ASSOC);
    return $bet;//['price_bet'] ?? 0;
}

function send_email($email, $user_name, $lot_id, $log, $pass)
{
//    echo "\nlog, pass = $log, $pass";

    $dsn = "smtp://$log:$pass@smtp.yandex.ru:465?encryption=SSL";
    $transport = Transport::fromDsn($dsn);
    $mailer = new Mailer($transport);

    $mailto = $mailfrom = $email;

    // Формирование сообщения
    $email = (new Email())
        ->to("$mailto")
        ->from("$mailfrom")
        ->subject("Уведомление от сервиса «Yeti.cave»")
        ->text("Уважаемый $user_name. Поздравляем! Ваша ставка победила в лоте №$lot_id");
    // Отправка сообщения
    $mailer->send($email);

}
?>