<?php

  session_start();
  if( !isset($_SESSION['username']) && !isset($_SESSION['password']) && !isset($_SESSION['account']) ){
    header("Location: " . $_ENV['NEX_HOST'] . "/index.php");
    exit();
  }elseif( !isset($_SESSION['key']) ){
    header("Location: " . $_ENV['NEX_HOST'] . "/request_key.php");
    exit();
  }

?>
