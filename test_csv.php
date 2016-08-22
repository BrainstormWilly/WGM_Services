<?php
  $time_beg = microtime(true);
  $cindex = 0;
  $sindex = 600000;
  $limit = 25;
  $results = [];
  if( $handle = fopen("lrg_orders_sample.csv", "r") ){
    while( !feof($handle) ){
      $data = fgetcsv($handle);

      if( $cindex==0 && count($results)==0 ){
        array_push( $results, $data[0] );
      }elseif( ++$cindex > $sindex ){
        array_push($results, $data[0] );
        if( --$limit == 0 ){
          break;
        }
      }
    }
  }else{
    array_push($results, "fopen failed.");
  }
  fclose($handle);
  $time_end = microtime(true);
  $time_dur = $time_end - $time_beg;
?>

<html>

<p>Duration: <?php echo $time_dur ?></p>
<p>
<?php foreach( $results as $result ){
  echo $result . '</br>';
}?>
</p>
