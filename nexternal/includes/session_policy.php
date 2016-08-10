<?php

  session_start();

  if( count($_SESSION) > 0 ){
    if( $_SESSION['service'] == 'Nexternal' ){
      if( !isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['account']) ){
        header("Location: " . $_ENV['NEX_HOST'] . "/index.php");
        exit();
      }elseif( !isset($_SESSION['key']) ){
        header("Location: " . $_ENV['NEX_HOST'] . "/request_key.php");
        exit();
      }
    }else{
      unset($_SESSION);
      session_destroy();
      header("Location: " . $_ENV['NEX_HOST'] . "/index.php");
      exit;
    }
  }else{
    unset($_SESSION);
    session_destroy();
    header("Location: " . $_ENV['NEX_HOST'] . "/index.php");
    exit;
  }

?>
