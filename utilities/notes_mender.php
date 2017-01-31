<?php

if( !file_exists("notes_in.csv") ){
    exit("No notes file!");
}

if( file_exists("notes_in.csv") && file_exists("notes_out.csv") ){
  unlink("notes_out.csv");
}

// $in_file = file_get_contents("notes_in.txt", true);
// $out_file = "";
// $idx = 0;
// $quoted = FALSE;
// while( $idx < strlen($in_file) ){
//   if($in_file[$idx]=='"'){
//     $quoted = !$quoted;
//   }
//   if( $quoted ){
//     if($in_file[$idx]==="\r"){
//       $out_file .= ";";
//     }elseif($in_file[$idx]==="\n"){
//       $out_file .= " ";
//     }else{
//       $out_file .= $in_file[$idx];
//     }
//   }else{
//     $out_file .= $in_file[$idx];
//   }
//   ++$idx;
// }
//
// $out_handle = fopen("notes_out.csv", "w");
// if( fwrite( $out_handle , $out_file ) !== FALSE ){
//     echo "Parse complete.";
// }
// fclose($out_handle);

if (($handle = fopen("notes_in.csv", "r")) !== FALSE) {
  $cnt = 0;
  $out = [];
  while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
    // foreach ($data as $value) {
    //   echo $value . "<br/>";
    //   // if( $value[0]=='"' ){
    //   //   echo "Found a quote." . PHP_EOL;
    //   // }
    // }
    $cnt = count($data); // column count
    if( isset($pending) ){ // pending record (broken)
      $pending[ count($pending)-1 ] .= "; " . $data[0]; // merge first column of record to last column of pending
      if( isset($data[1]) ){ // 2nd column exists in record (s/b date column)
        array_push($pending, $data[1]); // add date column to pending
        array_push($out, $pending); // add completed pending record
        unset($pending);
      }
    }else if( $cnt < 5 ){
      $pending = $data;
    }else{
      array_push($out, $data);
    }
    // print_r(count($data));
    // print_r(": ");
    // print_r($data[0]);
    // print_r("<br/><br/>");
  }
  fclose($handle);

  $out_handle = fopen("notes_out.csv", "w");
  foreach($out as $row){
    fputcsv( $out_handle, $row );
  }
  echo "<h3>Parsed into " . count($out) . " rows.</h3";
  fclose($out_handle);

}

?>
