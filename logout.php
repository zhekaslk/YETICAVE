<?php
//разлогин
require_once "vendor/autoload.php";
session_start();
unset($_SESSION["user"]);
header("Location: /index.php");