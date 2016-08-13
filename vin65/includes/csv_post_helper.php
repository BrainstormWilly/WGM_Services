<?php

if( count($_POST) > 0 ) {
  $controller->setResultsTable("<h4>Preparing file " . basename($_FILES["csv_file"]["name"]) . "...</h4>");
  $file = $_ENV['UPLOADS_PATH'] . basename($_FILES["csv_file"]["name"]);
  $file_type = pathinfo($file,PATHINFO_EXTENSION); //looking for csv
  if( $file_type == 'csv' ){
    if( move_uploaded_file($_FILES['csv_file']['tmp_name'], $file) ){
      $controller->setResultsTable("<h4>Processing file " . basename($_FILES["csv_file"]["name"]) . "...</h4>");
      header("Refresh:1; url=" . $controller->getClassFileName() . "_file.php?index=0&file=" . basename($_FILES["csv_file"]["name"]));
      // $controller->setResultsTable("Begin Service ");
      // $controller->queueRecords($file, 0);
    }else{
      $controller->setResultsTable("<h4 style='color:red'>Service aborted. File did not upload properly.</h4>");
    }
  }else{
    $controller->setResultsTable("<h4 style='color:red'>Service aborted. Only CSV files are allowed!</h4>");
  }
}elseif( !isset($_GET['file']) || !isset($_GET['index'])){
  $controller->setResultsTable("<h4 style='color:red'>Error: Unable to upload. File and/or index missing.</h4>");
}else{
  $controller->queueRecords($_ENV['UPLOADS_PATH'] . $_GET['file'], $_GET['index']);
}

?>
