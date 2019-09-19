<?php

return [
    "" => [
        "controller" => "main",
        "action" => "index",
    ],
    "index.php" => [
        "controller" => "main",
        "action" => "index",
    ],
    "login" => [
        "controller" => "account",
        "action" => "login",
    ],
    "register" => [
        "controller" => "account",
        "action" => "register",
    ],
    "logout" => [
        "controller" => "account",
        "action" => "logout",
    ],
    "add" => [
        "controller" => "add",
        "action" => "add",
    ],
    "history" => [
        "controller" => "history",
        "action" => "history",
    ],
    "rates" => [
        "controller" => "rates",
        "action" => "rates",
    ],
    "lot/{id:\d+}" => [
        "controller" => "lot",
        "action" => "lot",
    ],
    "search\\?query={search:(\w+|)}" => [
        "controller" => "search",
        "action" => "search",
    ],
    "category/{category:(boards|mounting|boots|clothes|instruments|other)}" => [
        "controller" => "lotsByCategory",
        "action" => "lotsByCategory",
    ],
];