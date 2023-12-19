<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'models.php';

$is_auth = false;
$sql = "select codename, name from categories";
$res = mysqli_query($con, $sql);
if (!$res) {
    $error = mysqli_error($con);
} else {
    $cats = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

$page_content = include_template('sign-up.php',
    ['cats' => $cats]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['email', 'name', 'password', 'message'];
    $errors = [];
    $rules = [
        'email' => function ($value) use ($con) {
            return validate_email($con, $value);
        },
    ];

    foreach ($required_fields as $key) {
        $arg[$key] = FILTER_DEFAULT;
    }
    //print_r($arg);
    $sign_data = filter_input_array(INPUT_POST, $arg, true);

//    print_r($lot);//exit;

    foreach ($sign_data as $field => $value) {
        if (isset($rules[$field])) {
            $rule = $rules[$field];
            $errors[$field] = $rule($value);
        }

        if (empty($value) && in_array($field, $required_fields)) {
            $errors[$field] = "Поле $field нужно заполнить";
        }
    }

    $errors = array_filter($errors);

    if (count($errors)) {
        // показать ошибки валидации
        $page_content = include_template('sign-up.php', [
            'cats' => $cats,
            'sign_up' => $sign_data,
            'error' => $errors]);
    } else {
        // Сохранить новый лот в БД
//        $sql = "SET FOREIGN_KEY_CHECKS = 0";
//        $res = mysqli_query($con, $sql);
        $user_id = 2;
//$required_fields = ['lot-name', 'category', 'message', 'lot-img', 'lot-rate', 'lot-step', 'lot-date'];
        $sql = "insert into users (email, user_name, user_password, contacts) values " .
            "(?, ?, ?, ?)";
        $pass = password_hash($sign_data['password'], PASSWORD_BCRYPT);
//        var_dump($lot);
//        console_log($sql);
        $stmt = db_get_prepare_stmt($con, $sql, [$sign_data['email'],
            $sign_data['name'], $pass, $sign_data['message']]);
//        var_dump($stmt);//exit;
//        console_log($stmt);
        $res = mysqli_stmt_execute($stmt);

        // Перенаправить на страницу просмотра лота
        if ($res) {
            $user_id = mysqli_insert_id($con);
            header("Location: http://yeti.cave/login.php");
        } else {
            $error = mysqli_error($con);
            console_log($error);
        }
    }
    if (isset($error)) {
        console_log($error);
    }
}



$page_layout = include_template('layout.php', [
    'title' => 'Регистрация',
    'is_auth' => $is_auth,
    'user_name' => '',
    'content' => $page_content,
    'cats' => $cats]);

print($page_layout);
