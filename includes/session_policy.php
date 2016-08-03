<?php

  session_start();
  if( !isset($_SESSION['username']) && !isset($_SESSION['password']) ){
    header("Location: " . $_ENV['APP_HOST'] . "/index.php");
    exit();
  }

?>
