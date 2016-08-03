<?php

  require_once "../../vendor/autoload.php";
  require_once $_ENV['APP_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/upsert_shipping_address.php";

  use wgm\vin65\controllers\UpsertShippingAddress as UpsertShippingAddress;
  $controller = new UpsertShippingAddress( $_SESSION );

  include $_ENV['APP_INCLUDES'] . '/csv_post_helper.php';

?>

<html>

<header>
  <?php require $_ENV['APP_INCLUDES'] . "/header.php" ?>
</header>

<body class="body">
  <div class="container">

    <?php include $_ENV['APP_INCLUDES'] . "/nav.php" ?>

    <div class="page-header">
      <h1>UpsertShippingAddress <small>for <?php echo $_SESSION['username'] ?></small></h1>
    </div>

    <div>
      <?php echo $controller->getResultsTable() ?>
    </div>

  </div>
</body>

</html>
