<?php

  require_once "./vendor/autoload.php";

  session_start();

  if( isset($_GET['logout']) ){
    unset($_SESSION);
    session_destroy();
  }

?>

<html>

  <header>
    <?php require "./includes/header.php"; ?>
  </header>

  <body>
    <div class="container">

      <div class="jumbotron">
        <?php

          if( isset($_SESSION['service']) ){
            echo "<small class=\"pull-right\">{$_SESSION['service']}: {$_SESSION['username']} | <a href=\"index.php?logout=1\">Logout</a></small>";
          }

        ?>

        <h2>Wine Glass Marketing Data Web Services</h2>
        <p>Choose which services you wish to use</p>
        <p>
          <a class="btn btn-primary btn-lg" href="vin65/index.php">Vin65</a>
          <a class="btn btn-primary btn-lg" href="bloyal/index.php">bLoyal</a>
          <a class="btn btn-primary btn-lg" href="nexternal/index.php">Nexternal</a>
          <a class="btn btn-default btn-lg" href="#">Utilities</a>
        </p>

      </div>

    </div>
  </body>

</html>
