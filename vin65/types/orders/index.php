<?php
require_once '../../../vendor/autoload.php';
require_once "../../../src/config/bootstrap.php";
require_once $_ENV['V65_INCLUDES'] . "/session_policy.php";
header("Location: " . $_ENV['V65_HOST']);
exit;
?>
