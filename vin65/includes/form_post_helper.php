<?php

if( isset($_GET['download']) ){
  require $_ENV['V65_INCLUDES'] . "/file_uploader.php";
  //file_put_contents($_GET['download'], fopen($_ENV['UPLOADS_PATH'] . $_GET['download'], 'r'));
}elseif( count($_POST) > 0 ) {
  $controller->setResultsTable("<h4>Processing Data...</h4>");
  $controller->inputRecord($_POST);
}

?>
