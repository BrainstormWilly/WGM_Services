<?php
  
  require_once "../vendor/autoload.php";
  require_once "../src/config/bootstrap.php";

  session_start();

  if( isset($_GET['logout']) ){
    unset($_SESSION);
    session_destroy();
  }

  if( isset($_SESSION['service']) ){
    if( $_SESSION['service']=='Vin65' ){
      header("Location: list.php");
      exit;
    }else{
      session_destroy();
    }
  }elseif( isset($_POST['username']) && isset($_POST['password']) ) {
    $_SESSION['service'] = 'Vin65';
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    header("Location:" . "list.php");
    exit();
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
