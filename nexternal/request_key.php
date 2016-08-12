<?php

  require_once "../vendor/autoload.php";
  require_once "../src/config/bootstrap.php";

  session_start();

  if( count($_SESSION) > 0 ){
    if( $_SESSION['service'] == 'Nexternal' ){
      if( !isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['account']) ){
        header("Location: " . $_ENV['NEX_HOST'] . "/index.php");
        exit();
      }elseif( isset($_SESSION['key']) ){
        header("Location: " . $_ENV['NEX_HOST'] . "/list.php");
        exit();
      }
    }else{
      unset($_SESSION);
      session_destroy();
      header("Location: " . $_ENV['NEX_HOST'] . "/index.php");
      exit;
    }
  }

  require_once $_ENV['APP_ROOT'] . "/nexternal/models/test_submit_request.php";
  require_once $_ENV['APP_ROOT'] . "/nexternal/models/test_verify_request.php";

  use wgm\nexternal\models\TestSubmitRequest as TestSubmitRequestModel;
  use wgm\nexternal\models\TestVerifyRequest as TestVerifyRequestModel;

  $status = "Sending Submit Request...";

  $submit = new TestSubmitRequestModel($_SESSION);
  $submit->processService();
  $submit_result = $submit->getOutputToArray();

  $status = "Sending Verify Request...";
  if( isset($submit_result['TestKey']) ){
    $_SESSION['key'] = $submit_result["TestKey"];
    $verify = new TestVerifyRequestModel($_SESSION);
    $verify->processService();
    $verify_result = $verify->getOutputToArray();
    if( isset($verify_result['ActiveKey']) ){
      $_SESSION['key'] = $verify_result['ActiveKey'];
      header("Location: " . $_ENV['NEX_HOST'] . "/list.php");
      exit;
    }

    $status = "Verification Failure: " . $verify->getOutputToXml();
  }else{
    $status = "Submit Failure: " . $submit->getOutputToXml();
  }
?>


<html>
  <header>
    <?php require $_ENV['APP_INCLUDES'] . "/header.php"; ?>
  </header>

  <body class="body">
    <div class="container">

      <?php include $_ENV['NEX_INCLUDES'] . "/nav.php" ?>

      <div class="page-header">
        <h1>Nexternal Web Services</br>
        <small>for <?php echo $_SESSION['account'] ?></small></h1>
      </div>

      <div>
        <h4><?php echo $status ?></h4>
      </div>

  </div>


</html>
