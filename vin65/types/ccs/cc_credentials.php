<?php

require_once '../../../vendor/autoload.php';
require_once "../../../src/config/bootstrap.php";
require_once $_ENV['V65_INCLUDES'] . "/session_policy.php";

  if( isset($_GET['service']) ){
    $form_path = $_GET['service'] . ".php";
  }else{
    $form_path = "add_update_cc.php";
  }

  if( count($_POST) > 0 ){
    if( isset($_POST['service']) ){
      $_SESSION['CC_KEY'] = $_POST['cc_key'];
      $_SESSION['CC_SALT'] = $_POST['cc_salt'];
      header("Location: " . $_POST['service']);
    }
  }

?>

<html>

<header>
  <?php require_once $_ENV['APP_INCLUDES'] . "/header.php" ?>
</header>

<body class="body">
  <div class="container">

    <?php include $_ENV['V65_INCLUDES'] . "/nav.php" ?>

    <div class="page-header">
      <h1>AddUpdateCreditCard <small>for <?php echo $_SESSION['username'] ?></small></h1>
    </div>

    <h4>Enter CreditCard Access Key and Salt for Customer.</h4>

    <form method="post">
      <div class="form-group">
        <label for="cc_key">Key</label>
        <input type="text" class="form-control" id="cc_key" name="cc_key">
      </div>
      <div class="form-group">
        <label for="cc_salt">Salt</label>
        <input type="text" class="form-control" id="cc_salt" name="cc_salt">
      </div>
      <?php echo "<input type=\"hidden\" id=\"input_type\" name=\"service\" value=\"{$form_path}\">"; ?>
      <button class="btn btn-primary" type="submit">Submit</button>
    </form>

  </div>
</body>

</html>
