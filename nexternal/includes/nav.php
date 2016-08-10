<nav class="navbar navbar-default">
  <div class="navbar-header">
    <?php echo '<a class="navbar-brand" href="' . $_ENV['APP_HOST'] . '">WGM</a>' ?>
  </div>

    <?php
      echo '<ul class="nav navbar-nav"><li><a href="' .  $_ENV['NEX_HOST'] . '">Nexternal</a></li></ul>';
      if( isset($_SESSION['username']) ){
        echo '<ul style="margin-right: 10px" class="nav navbar-nav navbar-right">' .
                '<li><a href="' . $_ENV['NEX_HOST'] . '?logout=1' . '">Logout</a></li>' .
              '</ul>';
      }
    ?>
</nav>
