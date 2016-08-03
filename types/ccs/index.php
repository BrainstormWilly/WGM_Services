<?php
require_once __DIR__ . './../../vendor/autoload.php';
require_once $_ENV['APP_INCLUDES'] . "/session_policy.php";
header("Location: " . $_ENV['APP_HOST']);
 ?>
