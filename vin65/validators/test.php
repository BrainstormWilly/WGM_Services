<?php

  require_once '../../vendor/autoload.php';
  require_once "../../src/config/bootstrap.php";
  require_once $_ENV['CONFIG_ROOT'] . "/db.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/validators/upload_contact.php";

  use \wgm\vin65\validators\UploadContact as UploadContact;

  $validator = new UploadContact($db);

  if( count($_FILES) > 0 ){
    $validator->uploadFile( $_FILES['data_file'] );
    if( $validator->getState()==UploadContact::STATE_UPLOAD_COMPLETE ){
      $validator->runTests();
    }
  }

  // $reader = new Excel();
  // if( $reader->readData($file) ){
  //   $r =  '<div class="table-responsive">';
  //   $r .=  '<table class="table"><thead><tr>';
  //   $r .= '<th>' . implode('</th><th>', $reader->getHeaders() ) . '</th>';
  //   $r .= '</tr></thead><tbody>';
  //   foreach ($reader->getRecords() as $value) {
  //     $r .= '<tr><td>' . implode('</td><td>', $value) . '</td></tr>';
  //   }
  //   $r .= '</tbody></table></div>';
  // }else{
  //   $r = "no go";
  // }

?>

<html>

<header>
  <?php include $_ENV['APP_INCLUDES'] . "/header.php"; ?>
</header>

<body>
  <div class="container">
    <?php echo $validator->statusHTML() ?>
    <?php echo $validator->csvForm() ?>
    <?php
      if( $validator->getState()==UploadContact::STATE_TESTS_COMPLETE ){
        echo $validator->resultsHTML();
      }
    ?>
  </div>
</body>

</html>
