<?php
  require_once __DIR__ . "./../../vendor/autoload.php";
  require_once $_ENV['APP_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/update_gift_card.php";

  use wgm\vin65\controllers\UpdateGiftCard as UpdateGiftCardController;
  $controller = new UpdateGiftCardController( $_SESSION );

  if( count($_POST) > 0 ) {
    if( $_POST['input_type'] == 'file' ){
      $controller->setResultsTable("<h4>Preparing file " . basename($_FILES["csv_file"]["name"]) . "...</h4>");
      $file = $_ENV['UPLOADS_PATH'] . basename($_FILES["csv_file"]["name"]);
      $file_type = pathinfo($file,PATHINFO_EXTENSION); //looking for csv
      if( $file_type == 'csv' ){
        if( move_uploaded_file($_FILES['csv_file']['tmp_name'], $file) ){
          $controller->setResultsTable("<h4>Processing file " . basename($_FILES["csv_file"]["name"]) . "...</h4>");
          header("Refresh:1; url=update_gift_card_file.php?index=0&file=" . basename($_FILES["csv_file"]["name"]));
          // $controller->setResultsTable("Begin Service ");
          // $controller->queueRecords($file, 0);
        }else{
          $controller->setResultsTable("<h4 style='color:red'>Service aborted. File did not upload properly.</h4>");
        }
      }else{
        $controller->setResultsTable("<h4 style='color:red'>Service aborted. Only CSV files are allowed!</h4>");
      }
    }else{
      //$v65_model->callService($_POST);
    }
  // file reload (temporary)
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

    <?php include $_ENV['APP_INCLUDES'] . "/nav.php" ?>

    <div class="page-header">
      <h1>UpdateGiftCard <small>for <?php echo $_SESSION['username'] ?></small></h1>
    </div>

    <div>
      <?php echo $controller->getResultsTable(); ?>
    </div>

  </div>

</body>

</html>
