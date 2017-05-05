<?php

  require_once '../../vendor/autoload.php';
  require_once "../../src/config/bootstrap.php";
  require_once $_ENV['CONFIG_ROOT'] . "/db.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/validators/order_history_importer.php";

  use \wgm\vin65\validators\OrderHistoryImporter as OrderHistoryImporter;

  $validator = new OrderHistoryImporter($db);

  include $_ENV['V65_INCLUDES'] . "/validator_upload_helper.php";

  // $reader = new Excel();
  // if( $reader->readData($file) ){
  //   $r =  '<div class="table-responsive">';
  //   $r .=  '<table class="table"><thead><tr>';
  //   $r .= '<th>' . implode('</th><th>', $reader->getHeaders() ) . '</th>';
  //   $r .= '</tr></thead><tbody>';
  //   foreach ($reader->getRecords() as $value) {
  //     $r .= '<tr><td>' . implode('</td><td>', $value) . '</td></tr>';
  //   }
  //   $r .= '</tbody></table></div>';
  // }else{
  //   $r = "no go";
  // }

?><!DOCTYPE HTML>

<html>

<header>
  <?php include $_ENV['APP_INCLUDES'] . "/header.php"; ?>
</header>

<body>
  <div class="container">

    <?php include $_ENV['V65_INCLUDES'] . "/nav.php" ?>

    <div class="page-header">
      <h1><?php echo $validator->getClassName() ?> <small>Validator</small></h1>
    </div>

    <?php echo $validator->statusHTML() ?>
    <?php echo $validator->csvForm() ?>
    <?php
      if( $validator->getState()==OrderHistoryImporter::STATE_TESTS_COMPLETE ){
        echo $validator->resultsHTML();
      }
    ?>
  </div>
</body>

</html>
