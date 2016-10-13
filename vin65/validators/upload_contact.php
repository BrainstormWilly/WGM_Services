<?php

  require_once '../../vendor/autoload.php';
  require_once "../../src/config/bootstrap.php";
  require_once $_ENV['CONFIG_ROOT'] . "/db.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/validators/upload_contact.php";

  use wgm\vin65\validators\UploadContact as UploadContactValidator;

  $validator = new UploadContactValidator($db);

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



    </div>
  </body>

</html>
