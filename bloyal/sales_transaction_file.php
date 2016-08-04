<?php

  require_once '../vendor/autoload.php';
  require_once "../src/config/bootstrap.php";
  require_once $_ENV['APP_ROOT'] . "/bloyal/controllers/sales_transaction.php";

  use wgm\bloyal\controllers\SalesTransaction as SalesTransactionController;

  $controller = new SalesTransactionController( );

  if( count($_POST) > 0 ) {
      $controller->setResultsTable("<h4>Preparing file " . basename($_FILES["csv_file"]["name"]) . "...</h4>");
      $file = $_ENV['UPLOADS_PATH'] . basename($_FILES["csv_file"]["name"]);
      $file_type = pathinfo($file,PATHINFO_EXTENSION); //looking for csv
      if( $file_type == 'csv' ){
        if( move_uploaded_file($_FILES['csv_file']['tmp_name'], $file) ){
          $controller->setResultsTable("<h4>Processing file " . basename($_FILES["csv_file"]["name"]) . "...</h4>");
          header("Refresh:1; url=" . $controller->getClassFileName() . "_file.php?index=0&file=" . basename($_FILES["csv_file"]["name"]));
          // $controller->setResultsTable("Begin Service ");
          // $controller->queueRecords($file, 0);
        }else{
          $controller->setResultsTable("<h4 style='color:red'>Service aborted. File did not upload properly.</h4>");
        }
      }else{
        $controller->setResultsTable("<h4 style='color:red'>Service aborted. Only CSV files are allowed!</h4>");
      }
  }elseif( isset($_GET['download']) ){
    header('Content-type: application/xml');
    header('Content-Disposition: attachment;filename="'.$_GET['download'].'"');
    $controller->setResultsTable("<h4>Download should begin shortly...</h4>");
  }elseif( !isset($_GET['file']) || !isset($_GET['index'])){
    $controller->setResultsTable("<h4 style='color:red'>Error: Unable to upload. File and/or index missing.</h4>");
  }else{
    $controller->queueRecords($_ENV['UPLOADS_PATH'] . $_GET['file'], $_GET['index']);
  }

?>

<html>

<header>
  <?php require_once $_ENV['APP_INCLUDES'] . "/header.php" ?>
</header>

<body class="body">
  <div class="container">

    <?php include $_ENV['BLOYAL_INCLUDES'] . "/nav.php" ?>

    <div class="page-header">
      <h1><?php echo $controller->getClassName() ?> </h1>
    </div>

    <div>
      <?php echo $controller->getResultsTable(); ?>
    </div>

  </div>

</body>

</html>
