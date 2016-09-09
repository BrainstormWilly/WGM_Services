<?php

  require_once '../../../vendor/autoload.php';
  require_once "../../../src/config/bootstrap.php";
  require_once $_ENV['V65_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/post_pickup.php";

  use wgm\vin65\controllers\PostPickup as PostPickupController;

  $controller = new PostPickupController($_SESSION);

  include $_ENV['V65_INCLUDES'] . "/csv_post_helper.php";
  include $_ENV['V65_INCLUDES'] . "/service_file_view.php";



?>
