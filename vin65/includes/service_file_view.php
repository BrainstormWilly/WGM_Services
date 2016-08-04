<html>

<header>
  <?php require_once $_ENV['APP_INCLUDES'] . "/header.php" ?>
</header>

<body class="body">
  <div class="container">

    <?php include $_ENV['V65_INCLUDES'] . "/nav.php" ?>

    <div class="page-header">
      <h1><?php echo $controller->getClassName() ?> <small>for <?php echo $_SESSION['username'] ?></small></h1>
    </div>

    <div>
      <?php echo $controller->getResultsTable(); ?>
    </div>

  </div>

</body>

</html>
