<?php


  require_once __DIR__ . './../../vendor/autoload.php';
  require_once $_ENV['APP_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/upsert_order.php";

  use wgm\vin65\controllers\UpsertOrder as UpsertOrderController;

  $controller = new UpsertOrderController($_SESSION);

  if( count($_POST) > 0 ) {
    if( $_POST['input_type'] == 'file' ){
      $file = $_ENV['UPLOADS_PATH'] . basename($_FILES["csv_file"]["name"]);
      $file_type = pathinfo($file,PATHINFO_EXTENSION); //looking for csv
      if( $file_type == 'csv' ){
        if( move_uploaded_file($_FILES['csv_file']['tmp_name'], $file) ){
          header("Location: upsert_order_file.php?service=upsert_order&file=" . basename($_FILES["csv_file"]["name"]));
          // $controller->setResultsTable("Begin Service ");
          // $controller->queueRecords($file, 0);
        }else{
          $controller->setResultsTable("Service aborted. File did not upload properly.");
        }
      }else{
        $controller->setResultsTable("Service aborted. Only CSV files are allowed!");
      }
    }else{
      //$v65_model->callService($_POST);
    }
  // file reload (temporary)
  }


?>

<html>

  <header>
    <?php require $_ENV['APP_INCLUDES'] . "/header.php" ?>
  </header>

  <body class="body">
    <div class="container">

      <?php include $_ENV['APP_INCLUDES'] . "/nav.php" ?>

      <div class="page-header">
        <h1>UpsertOrder <small>for <?php echo $_SESSION['username'] ?></small></h1>
      </div>

      <div class="panel-group" id="choices-group">

        <div class="panel panel-default">
          <div class="panel-heading" id='upload-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#upload-content">
                Upload CSV of Orders
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='upload-content' role='tabPanel' aria-labelledby='upload-heading'>
            <div class="panel-body">
              <form method="post" enctype="multipart/form-data">
                <?php echo $controller->getCsvForm() ?>
              </form>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" id='input-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#input-content">
                Input Single Order
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='input-content' role='tabPanel' aria-labelledby='input-heading'>
            <div class="panel-body">
              <?php echo $controller->getInputForm() ?>
            </div>
          </div>
        </div>

        <!-- <div class="panel panel-default">
          <div class="panel-heading" id='result-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#result-content">
                Add/Update Note Results
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse in" id='result-content' role='tabPanel' aria-labelledby='result-heading'>
            <div class="panel-body">
              <?php //echo $controller->getResultsTable() ?>
            </div>
          </div>
        </div> -->

      </div>

      <div>
        <?php echo $controller->getResultsTable() ?>
      </div>

    </div>
  </body>

</html>
