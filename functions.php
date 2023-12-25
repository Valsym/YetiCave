<?php
/**
 * Выводит параметр в консоль, аналогично JS-функции console.log
 * @param $data
 * @return void
 */
function console_log( $data )
{
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
}

/**
 * Преобразует число в денежный формат и добавляет знак Рубля в конце
 * @param $sum
 * @return string
 */
function number_sum($sum)
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
            print("\n".$error);
        } else {
            echo " - OK!";
        }
    }
}

/**
 * Замена path img/ на uploads/ в столбце img, в таблице lots
 * @return void
 */
function udate_img_path()
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
            print("\n".$error);
        } else {
            echo " - OK!";
        }
    }
}

/**
 * Проверяет, что введенное число целое и больше ноля
 * @param $num - число введенное пользователем
 * @return string|void|null - текст сообщение об ошибке или null
 */
function validate_number(?string $num): ?string
{
    if (is_numeric($num) ) {
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
    if( filter_var( $email ,FILTER_VALIDATE_EMAIL )) {
        $sql = "select email from users where users.email = '$email'";
        $res = mysqli_query($con, $sql);
        if (!$res) {
            return null;
        }
    }
    return "Поле email не валидно или такой адрес уже существует";
}
?>