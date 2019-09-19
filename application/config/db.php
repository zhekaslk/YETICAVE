<?php

return [
    "host" => "localhost",
    "user" => "phpmyadmin",
    "password" => "seaways17",
    "name" => "yeticave",
    "charset" => 'utf8',
    "opt" => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];