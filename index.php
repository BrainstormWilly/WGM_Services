<?php

  require_once "./vendor/autoload.php";

  session_start();

  if( isset($_GET['logout']) ){
    session_destroy();
  }

  if( isset($_SESSION['username']) ){
    header("Location:" . $_ENV['APP_HOST'] . "/list.php");
    exit();
  }elseif( isset($_POST['username']) && isset($_POST['password']) ) {
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    header("Location:" . $_ENV['APP_HOST'] . "/list.php");
    exit();
  }


?>

<html>

  <header>
    <?php require "./includes/header.php"; ?>
  </header>

  <body>
    <div class="container">

      <div class="page-header">
        <h1>Wine Glass Marketing Vin65 Web Services</h1>
      </div>

      <h4>Enter Customer's WebService User Credentials.</h4>

      <form action="index.php" method="post">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" name="username">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password">
        </div>
        <button class="btn btn-primary" type="submit">Submit</button>
      </form>

    </div>
  </body>

</html>
