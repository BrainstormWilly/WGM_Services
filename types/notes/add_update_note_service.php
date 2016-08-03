<?php
  require_once __DIR__ . "./../../vendor/autoload.php";
  require_once $_ENV['APP_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/add_update_note.php";

  use wgm\vin65\controllers\AddUpdateNote as AddUpdateNoteController;

  $controller = new AddUpdateNoteController( $_SESSION );

  if( !isset($_GET['file']) ){
    header("Location: add_update_note.php");
    exit;
  }

  $controller->queueRecords($_ENV['UPLOADS_PATH'] . $_GET['file'], 0);

  echo $controller->getResultsTable();
?>
