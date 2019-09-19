<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

function debug($arg) {
    echo '<pre>';
    var_dump($arg);
    echo '<pre>';
    exit;
}