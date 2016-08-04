<?php

  require_once '../vendor/autoload.php';
  require_once "../src/config/bootstrap.php";
  require_once $_ENV['APP_ROOT'] . "/bloyal/controllers/sales_transaction.php";

  use wgm\bloyal\controllers\SalesTransaction as SalesTransactionController;

  $controller = new SalesTransactionController( );

?>

<html>

  <header>
    <?php require $_ENV['APP_INCLUDES'] . "/header.php" ?>
  </header>

  <body class="body">
    <div class="container">

      <?php include $_ENV['BLOYAL_INCLUDES'] . "/nav.php" ?>

      <div class="page-header">
        <h1><?php echo $controller->getClassName() ?></h1>
      </div>

      <div class="panel-group" id="choices-group">

        <div class="panel panel-default">
          <div class="panel-heading" id='upload-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#upload-content">
                Upload CSV of Items
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='upload-content' role='tabPanel' aria-labelledby='upload-heading'>
            <div class="panel-body">
              <form action="sales_transaction_file.php" method="post" enctype="multipart/form-data">
                <?php echo $controller->getCsvForm() ?>
              </form>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" id='input-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#input-content">
                Input Single Item
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='input-content' role='tabPanel' aria-labelledby='input-heading'>
            <div class="panel-body">
              <?php echo $controller->getInputForm() ?>
            </div>
          </div>
        </div>

      </div>

    </div>
  </body>

</html>
