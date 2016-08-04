<?php

  require_once '../../../vendor/autoload.php';
  require_once "../../../src/config/bootstrap.php";
  require_once $_ENV['V65_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/upsert_order.php";

  use wgm\vin65\controllers\UpsertOrder as UpsertOrderController;

  $controller = new UpsertOrderController($_SESSION);

  include $_ENV['V65_INCLUDES'] . '/service_view.php';

?>
