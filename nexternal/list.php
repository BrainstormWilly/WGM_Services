<?php

  require_once "../vendor/autoload.php";
  require_once "../src/config/bootstrap.php";
  require $_ENV['NEX_INCLUDES'] . "/session_policy.php";

?>


<html>
  <header>
    <?php require $_ENV['APP_INCLUDES'] . "/header.php"; ?>
  </header>

  <body class="body">
    <div class="container">

      <?php include $_ENV['NEX_INCLUDES'] . "/nav.php" ?>

      <div class="page-header">
        <h1>Nexternal Web Services</br>
        <small>for <?php echo $_SESSION['account'] ?></small></h1>
      </div>

      <div class="panel panel-default">
        <div class="panel-body">
          <div class="list-group">
            <a class="list-group-item" href='customer_query_request.php'>Download Customer Data<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
            <a class="list-group-item" href='additional_addresses_query_request.php'>Download Additional Address Data<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
          </div>
        </div>
      </div>

  </div>


</html>
