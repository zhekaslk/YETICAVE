<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 22.05.19
 * Time: 6:15
 */
session_start();
unset($_SESSION["user"]);
header("Location: /index.php");