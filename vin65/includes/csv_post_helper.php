<?php
$page_limit = 25;
$display_limit = 50;
$set_limit = 1;
$record_cnt = 0;

if( isset($_GET['download']) ){
  require_once $_ENV['V65_INCLUDES'] . "/file_uploader.php";
}elseif( count($_POST) > 0 ) {

  if( isset($_POST['page_limit']) ) $page_limit = intval($_POST['page_limit']);
  if( isset($_POST['display_limit']) ) $display_limit = intval($_POST['display_limit']);
  if( isset($_POST['set_limit']) ){
    if( intval($_POST['set_limit']) > 15 ){
      $set_limit = 15;
    }else{
      $set_limit = intval($_POST['set_limit']);
    }
  }

  //
  $controller->setResultsTable("<h4>Preparing file " . basename($_FILES["csv_file"]["name"]) . " ...</h4>");
  if( !is_dir($_ENV['UPLOADS_PATH']) ){
    mkdir( $_ENV['UPLOADS_PATH'], 0777, true);
  }
  $file = $_ENV['UPLOADS_PATH'] . basename($_FILES["csv_file"]["name"]);
  $file_type = pathinfo($file,PATHINFO_EXTENSION); //looking for csv
  if( $file_type == 'csv' ){
    if( move_uploaded_file($_FILES['csv_file']['tmp_name'], $file) ){
      $controller->setResultsTable("<h4>Processing file " . basename($_FILES["csv_file"]["name"]) . "...</h4>");
      header("Refresh:1; url=" . $controller->getClassFileName() . "_file.php?index=0&file=" . basename($_FILES["csv_file"]["name"]) . "&page_limit=" . $page_limit . "&display_limit=" . $display_limit . "&set_limit=" . $set_limit);
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
  if( isset($_GET['page_limit']) ) $page_limit = intval($_GET['page_limit']);
  if( isset($_GET['display_limit']) ) $display_limit = intval($_GET['display_limit']);
  if( isset($_GET['set_limit']) ){
    if( intval($_GET['set_limit']) > 15 ){
      $set_limit = 15;
    }else{
      $set_limit = intval($_GET['set_limit']);
    }
  }
  if( !isset($_GET['cnt']) ){
    $_GET['cnt'] = 0;
  }
  $controller->setData($page_limit, $display_limit, $set_limit);
  $controller->queueRecords($_ENV['UPLOADS_PATH'] . $_GET['file'], $_GET['index'], $_GET['cnt']);

}

?>
