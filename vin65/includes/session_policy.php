<?php

  session_start();

  if( !isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['service']) ){
    unset($_SESSION);
    session_destroy();
    header("Location: " . $_ENV['V65_HOST'] . "/index.php");
    exit();
  }elseif ($_SESSION['service'] != 'Vin65') {
    unset($_SESSION);
    session_destroy();
    header("Location: " . $_ENV['V65_HOST'] . "/index.php");
    exit();
  }

?>
