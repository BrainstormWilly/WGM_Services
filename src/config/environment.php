<?php

function make_path(...$dirs){
  return join(DIRECTORY_SEPARATOR, $dirs);
}

if( $_SERVER['SERVER_NAME']=='wgm.dev' ){
  $_ENV['APP_ENV'] = 'dev';
}else if($_SERVER['SERVER_NAME']=='bsc.sytes.net' ){
  $_ENV['APP_ENV'] = 'syn';
}else{
  $_ENV['APP_ENV'] = 'prd';
}

$_ENV['SERVICES_ROOT'] = dirname(dirname(__DIR__));
$_ENV['APP_ROOT'] = make_path( $_ENV['SERVICES_ROOT'], "src", "app" );
$_ENV['CONFIG_ROOT'] = make_path( $_ENV['SERVICES_ROOT'], "src", "config" );
$_ENV['APP_INCLUDES'] = make_path( $_ENV['SERVICES_ROOT'], "includes" );
$_ENV['TYPES_ROOT'] = make_path( $_ENV['SERVICES_ROOT'], "types" );
$_ENV['UPLOADS_PATH'] = "uploads" . DIRECTORY_SEPARATOR;
$_ENV['BLOYAL_HOST'] = DIRECTORY_SEPARATOR . "bloyal";
$_ENV['BLOYAL_INCLUDES'] = make_path( $_ENV['SERVICES_ROOT'], "bloyal", "includes" );
$_ENV['NEX_HOST'] = DIRECTORY_SEPARATOR . "nexternal";
$_ENV['NEX_INCLUDES'] =  make_path( $_ENV['SERVICES_ROOT'], "nexternal", "includes" );
$_ENV['V65_HOST'] = DIRECTORY_SEPARATOR . "vin65";
$_ENV['V65_INCLUDES'] = make_path( $_ENV['SERVICES_ROOT'], "vin65", "includes" );

if( $_ENV['APP_ENV'] === 'dev' ){

  $_ENV['APP_HOST'] = DIRECTORY_SEPARATOR;

  $_ENV['DB_HOST'] = 'localhost';
  $_ENV['DB_USERNAME'] = 'root';
  $_ENV['DB_PASSWORD'] = 'ok4root';
  $_ENV['DB_NAME'] = 'wgm';

}else if($_ENV['APP_ENV'] === 'syn'){

  $_ENV['APP_HOST'] = DIRECTORY_SEPARATOR . "wgm";

  $_ENV['DB_HOST'] = 'localhost';
  $_ENV['DB_USERNAME'] = 'root';
  $_ENV['DB_PASSWORD'] = '';
  $_ENV['DB_NAME'] = 'wgm';

}else{

  $_ENV['APP_HOST'] = DIRECTORY_SEPARATOR;

  $_ENV['DB_HOST'] = 'localhost';
  $_ENV['DB_USERNAME'] = 'exwzhcmy_wgm';
  $_ENV['DB_PASSWORD'] = '0&QP*Cmy9SGp';
  $_ENV['DB_NAME'] = 'exwzhcmy_wgm';

}


?>
