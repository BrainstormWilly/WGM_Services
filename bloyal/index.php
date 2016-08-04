<?php

  require_once "../vendor/autoload.php";
  require_once "../src/config/bootstrap.php";

?>


<html>
  <header>
    <?php require $_ENV['APP_INCLUDES'] . "/header.php"; ?>
  </header>

  <body class="body">
    <div class="container">

      <?php include $_ENV['BLOYAL_INCLUDES'] . "/nav.php" ?>

      <div class="page-header">
        <h1>bLoyal File Services
      </div>


      <div class="panel-group" id="services-group">

        <div class="panel panel-default">
          <div class="panel-heading" id='contact-services-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#services-group' href="#contact-services-content">
                Sales Services
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='contact-services-content' role='tabPanel' aria-labelledby='contact-services-heading'>
            <div class="panel-body">
              <div class="list-group">
                <a class="list-group-item" href='sales_transaction.php'>Sales Transactions<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>


</html>
