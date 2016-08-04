<?php

  require_once '../../../vendor/autoload.php';
  require_once "../../../src/config/bootstrap.php";
  require_once $_ENV['V65_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/add_update_cc.php";

  use wgm\vin65\controllers\AddUpdateCC as AddUpdateCCController;

  $controller = new AddUpdateCCController($_SESSION);

  // initial file load
  require $_ENV['V65_INCLUDES'] . '/csv_post_helper.php';

  include $_ENV['V65_INCLUDES'] . '/service_file_view.php';
?>
