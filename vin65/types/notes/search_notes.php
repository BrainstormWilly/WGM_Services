<?php

  require_once '../../../vendor/autoload.php';
  require_once "../../../src/config/bootstrap.php";
  require_once $_ENV['V65_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/search_notes.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/search_notes.php";

  use wgm\vin65\models\SearchNotes as SearchNotesModel;
  use wgm\vin65\controllers\SearchNotes as SearchNotesController;

  $model = new SearchNotesModel( $_SESSION );
  $controller = new SearchNotesController( $model );

  if( count($_POST) > 0 ){
    //print_r($_POST);
    $model->callService($_POST);
  }


?>

<html>

  <header>
    <?php require_once $_ENV['APP_INCLUDES'] . "/header.php"; ?>
  </header>

  <body class="body">
    <div class="container">

      <?php include $_ENV['V65_INCLUDES'] . "/nav.php" ?>

      <div class="page-header">
        <h1>SearchNotes <small>for <?php echo $_SESSION['username'] ?></small></h1>
      </div>

      <div class="panel-group" id="choices-group">

        <div class="panel panel-default">
          <div class="panel-heading" id='input-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#input-content">
                Input Search Criteria
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse in" id='input-content' role='tabPanel' aria-labelledby='input-heading'>
            <div class="panel-body">
              <?php echo $controller->getInputForm() ?>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" id='result-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#result-content">
                Search Results
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse in" id='result-content' role='tabPanel' aria-labelledby='result-heading'>
            <div class="panel-body">
              <?php echo $controller->getResultsTable() ?>
            </div>
          </div>
        </div>

      </div>

    </div>
  </body>

</html>
