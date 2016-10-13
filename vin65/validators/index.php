<?php

require_once '../../vendor/autoload.php';
require_once "../../src/config/bootstrap.php";

?>

<html>

  <header>
    <?php include $_ENV['APP_INCLUDES'] . "/header.php"; ?>
  </header>

  <body>
    <div class="container">

      <?php include $_ENV['V65_INCLUDES'] . "/nav.php" ?>

      <div class="page-header">
        <h1>Vin65 Validators</h1>
      </div>

      <div class="panel-group" id="services-group">

        <div class="panel panel-default">
          <div class="panel-heading" id='upload-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#services-group' href="#upload-content">
                Upload Validators
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='upload-content' role='tabPanel' aria-labelledby='upload-heading'>
            <div class="panel-body">
              <div class="list-group">
                <a class="list-group-item" href='upload_contact.php'>Customer Importer<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" id='web-service-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#services-group' href="#web-service-content">
                Web Service Validators
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='web-service-content' role='tabPanel' aria-labelledby='web-service-heading'>
            <div class="panel-body">
              <div class="list-group">
                <a class="list-group-item" href='#'>AddContactTypeToContact<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </body>

</html>
