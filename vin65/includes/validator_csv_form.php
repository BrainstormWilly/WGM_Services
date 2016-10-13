

  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        Upload CSV of Items
      </h4>
    </div>
    <div class="panel-body">
      <?php
        echo '<form action="' . $controller->getClassFileName() . '_file.php" method="post" enctype="multipart/form-data">';
        echo $controller->getCsvForm();
        echo '</form>';
      ?>
    </div>
  </div>
