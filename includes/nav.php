<nav class="navbar navbar-default">
  <div class="navbar-header">
    <?php echo '<a class="navbar-brand" href="' . $_ENV['APP_HOST'] . '">Home</a>' ?>
  </div>
    <?php
      if( isset($_SESSION['username']) ){
        echo '<ul style="margin-right: 10px" class="nav navbar-nav navbar-right">' .
                '<li><a href="' . $_ENV['APP_HOST'] . '?logout=1' . '">Logout</a></li>' .
              '</ul>';
      }
    ?>
</nav>
