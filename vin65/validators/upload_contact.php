<?php

  require_once '../../vendor/autoload.php';
  require_once "../../src/config/bootstrap.php";
  require_once $_ENV['CONFIG_ROOT'] . "/db.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/validators/upload_contact.php";

  use wgm\vin65\validators\AbstractValidator as AbstractValidator;
  use wgm\vin65\validators\UploadContact as UploadContactValidator;

  $validator = new UploadContactValidator($db);

  if( count($_FILES)>0 ){
    $validator->setState(AbstractValidator::STATE_TEST);
    $file = $_ENV['UPLOADS_PATH'] . basename($_FILES["csv_file"]["name"]);
    $file_type = pathinfo($file,PATHINFO_EXTENSION); //looking for csv
    if( $file_type == 'csv' ){
      if( move_uploaded_file($_FILES['csv_file']['tmp_name'], $file) ){
        header("Refresh:1; url=" . $_SERVER['REQUEST_URI']);
        // $controller->setResultsTable("Begin Service ");
        // $controller->queueRecords($file, 0);
      }else{
        // $controller->setResultsTable("<h4 style='color:red'>Service aborted. File did not upload properly.</h4>");
      }
    }else{
      // $controller->setResultsTable("<h4 style='color:red'>Service aborted. Only CSV files are allowed!</h4>");
    }
  }

  if( !$validator->createTables() ){
    die("Unable to create database");
    // $validator->dropTables();
  }

?>

<html>

  <header>
    <?php include $_ENV['APP_INCLUDES'] . "/header.php"; ?>
  </header>

  <body>
    <div class="container">

      <?php include $_ENV['V65_INCLUDES'] . "/nav.php" ?>

      <div class="page-header">
        <h1>Customer Importer Validator</h1>
      </div>

      <?php echo $validator->csvForm(); ?>


    </div>
  </body>

</html>
