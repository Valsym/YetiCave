<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'models.php';

session_start();
if (!$is_auth) {
    $cats = get_categories($con, 'categories');
    $header = include_template('header.php',
        ['cats' => $cats]);
    $page_content = include_template('403.php',
        ['header' => $header]);
    $page_layout = include_template('layout.php', [
        'title' => 'Доступ запрещен',
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'content' => $page_content,
        'cats' => $cats
    ]);

    print($page_layout);
//    http_response_code(403);
    exit;
}

//udate_img_path();
$cats = get_categories($con, 'categories');
$cat_id = array_column($cats, 'id');
$lot = $lot ?? [];
$page_content = include_template('main-add.php',
    ['cats' => $cats]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = [
        'lot-name',
        'category',
        'message',
        'lot_img',
        'lot-rate',
        'lot-step',
        'lot-date'
    ];
    $errors = [];
    $rules = [
        'category' => function ($value) use ($cat_id) {
            return validate_category($value, $cat_id);
        },
        'lot-rate' => function ($value) {
            return validate_number($value);
        },
        'lot-step' => function ($value) {
            return validate_number($value);
        },
        'lot-date' => function ($value) {
            return validate_date($value);
        }
    ];

    foreach ($required_fields as $key) {
        $arg[$key] = FILTER_DEFAULT;
    }

    $lot = filter_input_array(INPUT_POST, $arg, true);
    $lot['category'] = (int)$lot['category'];

    foreach ($lot as $field => $value) {
        if (isset($rules[$field])) {
            $rule = $rules[$field];
            $errors[$field] = $rule($value);
        }

        if (empty($value) && in_array($field, $required_fields)) {
            $errors[$field] = "Поле $field нужно заполнить";
            if ('lot_img' === $field) {
                $errors[$field] = '';
            }
        }
    }

    if (!empty($_FILES["lot_img"]["name"])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tmp_name = $_FILES['lot_img']['tmp_name'];
        $file_path = __DIR__ . '/uploads/';
        $file_size = $_FILES['lot_img']['size'];
        $file_type = finfo_file($finfo, $tmp_name);

        $test = getimagesize($tmp_name);
        $width = $test[0];
        $height = $test[1];
        if ($width !== 350 || $height !== 260) {
            $errors['lot_img'] = "Размеры изображения должны быть: 350*260px";
        }

        $ext = null;
        if ($file_type === 'image/jpeg') {
            $ext = 'jpg';
        } elseif ($file_type === 'image/png') {
            $ext = 'png';
        }

        if ($file_size > 91000) {
            $errors['lot_img'] = "Максимальный размер файла: 91 Кб";
        }

        if ($ext && $file_size <= 91000) {
            $file_name = uniqid() . ".$ext";
            console_log('tmp_name: ' . $tmp_name);
            move_uploaded_file($tmp_name, $file_path . $file_name);
            $lot['lot_img'] = "uploads/" . $file_name;
        } else {
            $errors['lot_img'] = "Допустимые форматы файла: jpg, jpeg, png";
        }

    } else {
        $errors['lot_img'] = "Вы не загрузили изображение";
        console_log($errors);
        console_log($_FILES);
    }

    $errors = array_filter($errors);


    if (count($errors)) {
        // показать ошибки валидации
        $page_content = include_template('main-add.php', [
            'cats' => $cats,
            'lot' => $lot,
            'error' => $errors
        ]);
    } else {
        // Сохранить новый лот в БД
        $user_id = 2;
        $sql = "insert into lots (title, category_id, lot_description, img, start_price, " .
            "step, date_finish, author_id) values " .
            "(?, ?, ?, ?, ?, ?, ? , $user_id)";
        $stmt = db_get_prepare_stmt($con, $sql, $lot);
        $res = mysqli_stmt_execute($stmt);

        // Перенаправить на страницу просмотра лота
        if ($res) {
            $lot_id = mysqli_insert_id($con);
            //$file_name = uniqid() . ".$ext";
            $file_name_new = "lot-$lot_id.$ext";
            console_log('file_name_new: ' . $file_name_new);
            rename($file_path . $file_name, $file_path . $file_name_new);
            echo "rename($file_path$file_name -> $file_path$file_name_new)";
            $lot_img = "uploads/" . $file_name_new;
            $lot['lot_img'] = $lot_img;
            $sql = "update lots as l set l.img = '$lot_img' where l.id = $lot_id";
            $res = mysqli_query($con, $sql);
            if (!$res) {
                $error = mysqli_error($con);
//                print("\n".$error);
                console_log("\n" . $error);
            } else {
                console_log("\n update lots img=$lot_img - OK");
                if (($bet_img = make_bet_img($con, $lot_id, $lot_img))) {
                    console_log("\n make_bet_img=$bet_img - OK");
                } else {
                    console_log("\n make_bet_img=$bet_img - not OK");
                }
            }
            header("Location: http://yeti.cave/lot.php?lot=$lot_id");
        } else {
            $error = mysqli_error($con);
            console_log($error);
        }
    }
    if (isset($error)) {
        console_log($error);
    }
}


$page_layout = include_template('layout-add.php', [
    'title' => 'Добавление лота',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $page_content,
    'cats' => $cats
]);

print($page_layout);
