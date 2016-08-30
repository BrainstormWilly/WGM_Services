<?php

require_once "vendor/autoload.php";
require_once "src/config/bootstrap.php";
  require_once "src/app/models/csv.php";

  use wgm\models\CSV as CSV;

  $time_beg = microtime(true);
  $index = 0;
  $cnt = 0;
  $result = "<p>";
  $record;
  $csv = new CSV();

  if( isset($_GET["index"]) ) $index = $_GET['index'];
  if( isset($_GET['total']) ) $cnt = $_GET['total'];

  $csv->resetRecordIndex($index, $cnt);
  $csv->readData("lrg_orders_sample.csv");

  while( $csv->hasNextRecord() ){
    $record = $csv->getNextRecord();
    $result .= "Record: {$csv->getCurrentRecordIndex()} -> {$record['OrderNumber']}</br>";
  }

  $time_end = microtime(true);
  $time_dur = $time_end - $time_beg;

  $title = "<h4>Processed {$csv->getCurrentRecordIndex()} of {$csv->getRecordCnt()}</h4>";
  $title .= "<p><strong>{$time_dur} time elapsed</strong></p>";
  $result = $title . $result . "</p>";

  if( $csv->hasNextPage() ){
    header("Refresh:0; url=test_csv.php?file=lrg_orders_sample.csv&index=" . strval($csv->getCurrentRecordIndex()) . "&total=" . strval($csv->getRecordCnt()));
  }

  // $cindex = 0;
  // $sindex = 600000;
  // $limit = 25;
  // $results = [];
  // if( $handle = fopen("lrg_orders_sample.csv", "r") ){
  //   while( !feof($handle) ){
  //     $data = fgetcsv($handle);
  //
  //     if( $cindex==0 && count($results)==0 ){
  //       array_push( $results, $data[0] );
  //     }elseif( ++$cindex > $sindex ){
  //       array_push($results, $data[0] );
  //       if( --$limit == 0 ){
  //         break;
  //       }
  //     }
  //   }
  // }else{
  //   array_push($results, "fopen failed.");
  // }
  // fclose($handle);

?>

<html>

<?php echo $result ?>

</html>
