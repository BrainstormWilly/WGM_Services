<?php

  $db = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);

  if( $db->connect_error ){
    die('ERROR: Unable to connect to database. ' . $db->connect_error);
  }

?>
