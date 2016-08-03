<?php

require_once "../../vendor/autoload.php";
require_once $_ENV['APP_INCLUDES'] . "/session_policy.php";


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

    <div>
      <?php
        echo $controller->getResultsTable()
      ?>
    </div>

  </div>
</body>

<?php
  include $_ENV['APP_INCLUDES'] . '/polling_script.php';
?>

</html>
