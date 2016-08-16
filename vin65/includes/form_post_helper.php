<?php

if( count($_POST) > 0 ) {
  $controller->setResultsTable("<h4>Processing Data...</h4>");
  $controller->inputRecord($_POST);
}

?>
