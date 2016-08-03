<?php

  require_once __DIR__ . "./../../vendor/autoload.php";
  require_once $_ENV['APP_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/upsert_order.php";

  use wgm\vin65\controllers\UpsertOrder as UpsertOrderController;
  $controller = new UpsertOrderController( $_SESSION );

  if( !isset($_GET['file']) ){
    header("Location: upsert_order.php");
    exit;
  }

  $controller->queueRecords($_ENV['UPLOADS_PATH'] . $_GET['file'], 0);

  echo $controller->getResultsTable();

?>
