<?php

if( !file_exists("names_in.csv") ){
    exit("No names_in file!");
}

$last_name_prefixes = ["st.", "st", "van", "von", "de", "da"];
$last_name_suffixes = ["jr", "jr.", "sr", "sr.", "i", "ii", "iii", "i i", "i i i", "md", "m.d.", "cmp", "cpm", "ms", "m.s.", "dds", "d.d.s."];

if( file_exists("names_out.csv") ){
  unlink("names_out.csv");
}

if (($handle = fopen("names_in.csv", "r")) !== FALSE) {
  $splits = "";
    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
        $parts = explode( " ", $data[0] );
        $cnt = count($parts);
        print_r($cnt);
        print_r("<br/>");
        if( $cnt==1 ){
          if( empty(trim($parts[0])) ){
            $parts[0] = "NO";
          }
          $splits .= trim($parts[0]) . ", NAME" . PHP_EOL;
        }elseif( $cnt==2 ) {
          $splits .= trim($parts[0]). "," . trim($parts[1]) . PHP_EOL;
        }else{
          $lastchr = count($parts) - 1;
          if( in_array(trim(strtolower($parts[$lastchr])), $last_name_suffixes) || in_array(trim(strtolower($parts[$lastchr-1])), $last_name_prefixes) ){
            $first = array_slice($parts, 0, $lastchr-1);
            $last = array_slice($parts, -2);
          }else{
            $first = array_slice($parts, 0, $lastchr);
            $last = array_slice($parts, -1);
          }
          $splits .= trim(implode(" ", $first)) . "," . trim(implode(" ", $last)) . PHP_EOL;
        }
    }
    fclose($handle);

    $out_handle = fopen("names_out.csv", "w");
    fwrite( $out_handle , $splits );
    fclose($out_handle);

}

?>
