<?php

$con = mysqli_connect('localhost', 'admin', 'admin', 'yeticave');
mysqli_set_charset($con, "utf8");
if ($con === false) {
    $error = mysqli_connect_error();
    print($error);
    exit;
}