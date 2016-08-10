<?php

  require_once "../vendor/autoload.php";
  require_once "../src/config/bootstrap.php";

  session_start();

  if( isset($_GET['logout']) ){
    session_destroy();
  }

  if( isset($_SESSION['key']) ){
    header("Location: list.php");
    exit();
  }elseif( isset($_SESSION['username']) ){
    header("Location: request_key.php");
    exit();
  }elseif( isset($_POST['username']) && isset($_POST['password']) && isset($_POST['account'])) {
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['account'] = $_POST['account'];
    header("Location:" . "request_key.php");
    exit();
  }




?>

<html>

  <header>
    <?php include $_ENV['APP_INCLUDES'] . "/header.php"; ?>
  </header>

  <body>
    <div class="container">

      <?php include $_ENV['NEX_INCLUDES'] . "/nav.php" ?>

      <div class="page-header">
        <h1>Wine Glass Marketing Nexternal Web Services</h1>
      </div>

      <h4>Enter Customer's Nexternal User Credentials.</h4>

      <form action="index.php" method="post">
        <div class="form-group">
          <label for="account">Account Name</label>
          <input type="text" class="form-control" id="account" name="account">
        </div>
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
