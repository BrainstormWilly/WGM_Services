<?php

require_once __DIR__ . "./../../vendor/autoload.php";
require_once $_ENV['APP_INCLUDES'] . "/session_policy.php";

$csv_file = $_GET['file'];
$csv_parts = explode("." , $csv_file);
$file = $_ENV['UPLOADS_PATH'] . array_shift( $csv_parts ) . ".txt";
$page = isset($_GET['page']) ? $_GET['page'] : 25;
$log = [];

if (($handle = fopen($file, "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
    array_push( $log, $data );
    if(--$page == 0) break;
  }
  fclose($handle);
}

echo json_encode($log);

?>
