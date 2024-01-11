<?php
session_start();
$is_auth = !empty($_SESSION['user']);
//print_r($_SESSION);
if ($is_auth) {
    $user_name = $_SESSION['user']['user_name'];
    $user_id = $_SESSION['user']['id'];
} else {
    $user_name = '';
}
?>