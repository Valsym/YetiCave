<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'models.php';

session_start();
//update_pass($con, 'test2@test.com');

$sql = "select codename, name from categories";
$res = mysqli_query($con, $sql);
if (!$res) {
    $error = mysqli_error($con);
} else {
    $cats = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

$page_content = include_template('login.php',
    ['cats' => $cats]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['email', 'password'];
    $errors = [];
    $rules = [
        'email' => function ($value) use ($con) {
            if (validate_email_not_repeat($con, $value) === null) {
                return "Пользователь с таким e-mail не найден";
            }
        },
    ];


    $login = filter_input_array(INPUT_POST,
        [
            "email"=>FILTER_DEFAULT,
            "password"=>FILTER_DEFAULT,
        ], true);
    $email = $login['email'] = mysqli_real_escape_string($con, $login['email']);

    foreach ($login as $field => $value) {
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
        $page_content = include_template('login.php', [
            'cats' => $cats,
            'login' => $login,
            'error' => $errors]);
    } else {
//        $email = mysqli_real_escape_string($con, $login['email']);
        $sql = "select user_password from users as u where u.email = '$email'";
        $res = mysqli_query($con, $sql);
        if ($res) {
            [$hash] = mysqli_fetch_array($res, MYSQLI_NUM);
            console_log($hash);//exit;
            if (!password_verify($login['password'], $hash)) {
                $errors['password'] = "Вы ввели неверный пароль".$login['password'];
                $page_content = include_template('login.php', [
                    'cats' => $cats,
                    'login' => $login,
                    'error' => $errors]);
            } else {
                //session_start();
                $sql = "select * from users where email = '$email'"; // !!! '$email' - без таких кавычек не работает
                $res = mysqli_query($con, $sql);
                $user_real = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
                $_SESSION['user'] = $user_real;

                header('Location: http://yeti.cave/index.php');
            }
        } else {
            $error = "Ошибка Базы данных";
            $errors['email'] = $error;
            $page_content = include_template('login.php', [
                'cats' => $cats,
                'login' => $login,
                'error' => $errors]);
        }

    }
}

$page_layout = include_template('layout.php', [
    'title' => 'Вход',
    'is_auth' => 0,
    'user_name' => '',
    'content' => $page_content,
    'cats' => $cats]);

print($page_layout);