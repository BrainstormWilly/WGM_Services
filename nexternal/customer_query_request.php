<?php

  require_once "../vendor/autoload.php";
  require_once "../src/config/bootstrap.php";
  require $_ENV['NEX_INCLUDES'] . "/session_policy.php";

  require_once $_ENV['APP_ROOT'] . "/nexternal/models/customer_query_request.php";

  use wgm\nexternal\models\CustomerQueryRequest as CustomerQueryRequestModel;

  if( isset($_GET['page']) ){
    $page = $_GET['page'];
  }else{
    $page = 1;
  }

  $status = "Loading...";

  // print_r($_SESSION['key']);
  $query = new CustomerQueryRequestModel($_SESSION, $page);
  $query->processService();
  print_r( $query->getOutputToXml()->asXML());
  exit;
  $v65 = $query->getOutputToV65Array();
  $csv = $query->convertOutputToCsv($v65);
  if( $query->writeOutputToCsv($_ENV['UPLOADS_PATH'] . $_SESSION['account'] . "_customer_query.csv", $csv, $page) ){
    $status = "<h4>SUCCESS: " . strval(count($csv) * $page) . " records loaded.</h4>";
    if( $query->hasNextPage() ){
      $status .= "<p>Loading page " . ++$page . "...</p>";
      header("Refresh:1; url=customer_query_request.php?page=" . $page);
    }else{
      $status .= "<p>Service Complete</p>";
    }
  }else{
    $status = "<h4>FAIL: </h4>";
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
        <h1>Nexternal Web Services -> Customer Query</br>
        <small>for <?php echo $_SESSION['account'] ?></small></h1>
      </div>

      <div>
        <?php echo $status ?>
      </div>

  </div>


</html>
