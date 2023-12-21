<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'models.php';

session_start();
if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user_name = $_SESSION['user']['user_name'];
    http_response_code(403);
    exit;
} else {
    $is_auth = false;
    $user_name = '';
}

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
            return validate_email_not_repeat($con, $value);
        },
    ];

    foreach ($required_fields as $key) {
        $arg[$key] = FILTER_DEFAULT;
    }

    $sign_data = filter_input_array(INPUT_POST, $arg, true);

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
        // Сохранить нового юзера в БД
        $sql = "insert into users (email, user_name, user_password, contacts) values " .
            "(?, ?, ?, ?)";
        $pass = password_hash($sign_data['password'], PASSWORD_BCRYPT);
        $stmt = db_get_prepare_stmt($con, $sql, [$sign_data['email'],
            $sign_data['name'], $pass, $sign_data['message']]);

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
    'user_name' => $user_name,
    'content' => $page_content,
    'cats' => $cats]);

print($page_layout);
