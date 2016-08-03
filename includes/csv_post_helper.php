<?php

if( count($_POST) > 0 ) {
  if( $_POST['input_type'] == 'file' ){
    $file = $_ENV['UPLOADS_PATH'] . basename($_FILES["csv_file"]["name"]);
    $file_type = pathinfo($file,PATHINFO_EXTENSION); //looking for csv
    if( $file_type == 'csv' ){
      if( move_uploaded_file($_FILES['csv_file']['tmp_name'], $file) ){
        $controller->setResultsTable("Begin Service ");
        $controller->queueRecords($file, 0);
      }else{
        $controller->setResultsTable("Service aborted. File did not upload properly.");
      }
    }else{
      $controller->setResultsTable("Service aborted. Only CSV files are allowed!");
    }
  }else{
    //$v65_model->callService($_POST);
  }
// file reload (temporary)
}else if( isset($_GET['file']) ){
  $controller->setResultsTable("Continue Service at Record: " . $_GET['index']);
  $controller->queueRecords($_ENV['UPLOADS_PATH'] . $_GET['file'], $_GET['index']);
}else{
  header("Location: " . $_ENV['APP_HOST'] . "/list.php");
}

?>
