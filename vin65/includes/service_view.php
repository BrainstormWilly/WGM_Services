<html>

  <header>
    <?php require $_ENV['APP_INCLUDES'] . "/header.php" ?>
  </header>

  <body class="body">
    <div class="container">

      <?php include $_ENV['V65_INCLUDES'] . "/nav.php" ?>

      <div class="page-header">
        <h1><?php echo $controller->getClassName() ?> <small>for <?php echo $_SESSION['username'] ?></small></h1>
      </div>

      <div class="panel-group" id="choices-group">

        <div class="panel panel-default">
          <div class="panel-heading" id='upload-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#upload-content">
                Upload CSV of Items
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse in" id='upload-content' role='tabPanel' aria-labelledby='upload-heading'>
            <div class="panel-body">
              <?php
                echo '<form action="' . $controller->getClassFileName() . '_file.php" method="post" enctype="multipart/form-data">';
                echo $controller->getCsvForm();
                echo '</form>';
              ?>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading" id='input-heading'>
            <h4 class="panel-title">
              <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#input-content">
                Input Single Item
              </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id='input-content' role='tabPanel' aria-labelledby='input-heading'>
            <div class="panel-body">
              <?php echo $controller->getInputForm() ?>
            </div>
          </div>
        </div>

      </div>

      <div>
        <?php echo $controller->getResultsTable() ?>
      </div>

    </div>
  </body>

</html>
