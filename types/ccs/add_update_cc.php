<?php

  require_once "../../vendor/autoload.php";
  require $_ENV['APP_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/add_update_cc.php";

  use wgm\vin65\controllers\AddUpdateCC as AddUpdateCCController;

  $controller = new AddUpdateCCController($_SESSION);

  if( count($_POST) > 0 ) {
    if( $_POST['input_type']=='cc_credentials' ){
      $_SESSION['CC_KEY'] = $_POST['cc_key'];
      $_SESSION['CC_SALT'] = $_POST['cc_salt'];
    }
  }

  if( !isset($_SESSION['CC_KEY']) ){
    header("Location: cc_credentials.php?service=add_update_cc");
    exit;
  }

?>

<html>

<header>
  <?php require_once $_ENV['APP_INCLUDES'] . "/header.php" ?>
</header>

<body class="body">
  <div class="container">

    <?php include $_ENV['APP_INCLUDES'] . "/nav.php" ?>

    <div class="page-header">
      <h1>AddUpdateCreditCard <small>for <?php echo $_SESSION['username'] ?></small></h1>
    </div>

    <div class="panel-group" id="choices-group">

      <div class="panel panel-default">
        <div class="panel-heading" id='upload-heading'>
          <h4 class="panel-title">
            <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#upload-content">
              Upload CSV of Credit Cards
            </a>
          </h4>
        </div>
        <div class="panel-collapse collapse" id='upload-content' role='tabPanel' aria-labelledby='upload-heading'>
          <div class="panel-body">
            <form action="add_update_cc_file.php" method="post" enctype="multipart/form-data">
              <?php echo $controller->getCsvForm() ?>
            </form>
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading" id='input-heading'>
          <h4 class="panel-title">
            <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#input-content">
              Input Single Credit Card
            </a>
          </h4>
        </div>
        <div class="panel-collapse collapse" id='input-content' role='tabPanel' aria-labelledby='input-heading'>
          <div class="panel-body">
            <?php echo $controller->getInputForm() ?>
          </div>
        </div>
      </div>

    </div> <!-- end panel-group -->
  </div> <!-- end container -->
</body>



</html>
