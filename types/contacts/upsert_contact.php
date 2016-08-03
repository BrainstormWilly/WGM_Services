 <?php

  require_once "../../vendor/autoload.php";
  require_once $_ENV['APP_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/upsert_contact.php";

  use wgm\vin65\controllers\UpsertContact as UpsertContactController;

  $controller = new UpsertContactController($_SESSION);

?>

<html>

  <header>
    <?php require $_ENV['APP_INCLUDES'] . "/header.php" ?>
  </header>

  <body class="body">
    <div class="container">

      <?php include $_ENV['APP_INCLUDES'] . "/nav.php" ?>

      <div class="page-header">
        <h1>UpsertContact <small>for <?php echo $_SESSION['username'] ?></small></h1>
      </div>

      <div class="panel-group" id="choices-group">

        <div class="panel panel-default">
          <div class="panel-heading" id='upload-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#upload-content">
                Upload CSV of Contacts
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='upload-content' role='tabPanel' aria-labelledby='upload-heading'>
            <div class="panel-body">
              <form action="upsert_contact_file.php" method="post" enctype="multipart/form-data">
                <?php echo $controller->getCsvForm() ?>
              </form>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" id='input-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#input-content">
                Input Single Address
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

    </div>
  </body>

</html>
