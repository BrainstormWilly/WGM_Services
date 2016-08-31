<?php

  require_once '../../../vendor/autoload.php';
  require_once "../../../src/config/bootstrap.php";
  require_once $_ENV['V65_INCLUDES'] . "/session_policy.php";
  require_once $_ENV['APP_ROOT'] . "/models/csv.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/add_update_note.php";

  use wgm\vin65\controllers\AddUpdateNote as AddUpdateNoteController;
  use wgm\models\CSV as CSV;

  $controller = new AddUpdateNoteController( $_SESSION );
  $result

  if( isset($_GET['file']) ){
    $log_file = $_ENV['UPLOADS_PATH'] . $_GET['file'] . "_log.csv";
    if( file_exists($log_file) ){
      if( isset($_GET['download']) ){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($log_file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($log_file));
        readfile($log_file);
      }else{
        $csv = new CSV();
        $csv->readFile( $log_file );
        $hdrs = $csv->getHeaders();
        $dta = $csv->getRecords();
        $index = 0;
        $r =  '<table class="table table-condensed">';
        $r .=   '<tr>';
        foreach($hdrs as $hdr){
          $r .= "<th>{$hdr}</th>";
        }
        $r .=   '</tr>';
        foreach($dta as $d){
          if( ++$index > $csv->getMaxDisplay() ) break;
          if( $d['Status']=='FAIL' ){
            $r .= '<tr class="danger">';
          }else{
            $r .= '<tr>';
          }
          foreach($d as $key=>$value){
            $r .= "<td>{$value}</td>";
          }
          $r .= '</tr>'
        }
        $r .= '</table>';
        $controller->setResultsTable($r);
      }
    }
  }else{
    header("Location: " . $_ENV['V65_HOST'] );
  }

?>

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

    <div class='panel panel-default'>
      <div class="panel-heading" id='input-heading'>
        <h4 class="panel-title">
          <a role='button' data-toggle="collapse" data-parent='#choices-group' href="#input-content">
            Service Results
          </a>
        </h4>
      </div>
      <?php echo $controller->getResultsTable(); ?>
      <div class="panel-footer">
        <?php echo "<a class=\"btn btn-primary pull-right\" href=\"service_log.php?file={$_GET['file']}&download=1\">Download Log</a>" ?>
      </div>
    </div>


    </div>

  </div>

</body>

</html>
