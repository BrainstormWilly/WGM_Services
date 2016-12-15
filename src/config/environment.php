<?php

if( $_SERVER['SERVER_NAME']=='wgm.dev' ){
  $_ENV['APP_ENV'] = 'dev';
}else if($_SERVER['SERVER_NAME']=='bsc.sytes.net' ){
  $_ENV['APP_ENV'] = 'syn';
}else{
  $_ENV['APP_ENV'] = 'prd';
}

if( $_ENV['APP_ENV'] === 'dev' ){
  $_ENV['SERVICES_ROOT'] = dirname(dirname(__DIR__));

  $_ENV['APP_ROOT'] = $_ENV['SERVICES_ROOT'] . "/src/app";
  $_ENV['CONFIG_ROOT'] = $_ENV['SERVICES_ROOT'] . "/src/config";
  $_ENV['APP_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/includes";
  $_ENV['TYPES_ROOT'] = $_ENV['SERVICES_ROOT'] . "/types";
  $_ENV['UPLOADS_PATH'] = "./uploads/";
  $_ENV['APP_HOST'] = "/services";

  $_ENV['DB_HOST'] = 'localhost';
  $_ENV['DB_USERNAME'] = 'root';
  $_ENV['DB_PASSWORD'] = 'ok4root';
  $_ENV['DB_NAME'] = 'wgm';

  $_ENV['BLOYAL_HOST'] = "/services/bloyal";
  $_ENV['BLOYAL_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/bloyal/includes";

  $_ENV['NEX_HOST'] = "/services/nexternal";
  $_ENV['NEX_INCLUDES'] =  $_ENV['SERVICES_ROOT'] . "/nexternal/includes";

  $_ENV['V65_HOST'] = "/services/vin65";
  $_ENV['V65_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/vin65/includes";
}else if($_ENV['APP_ENV'] === 'syn'){
  $_ENV['SERVICES_ROOT'] = dirname(dirname(__DIR__));

  $_ENV['DB_HOST'] = 'localhost';
  $_ENV['DB_USERNAME'] = 'root';
  $_ENV['DB_PASSWORD'] = '';
  $_ENV['DB_NAME'] = 'wgm';

  $_ENV['APP_ROOT'] = $_ENV['SERVICES_ROOT'] . "/src/app";
  $_ENV['CONFIG_ROOT'] = $_ENV['SERVICES_ROOT'] . "/src/config";
  $_ENV['APP_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/includes";
  $_ENV['TYPES_ROOT'] = $_ENV['SERVICES_ROOT'] . "/types";
  $_ENV['UPLOADS_PATH'] = "./uploads/";
  $_ENV['APP_HOST'] = "/wgm";

  $_ENV['BLOYAL_HOST'] = $_ENV['APP_HOST'] . "/bloyal";
  $_ENV['BLOYAL_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/bloyal/includes";

  $_ENV['NEX_HOST'] = $_ENV['APP_HOST'] . "/nexternal";
  $_ENV['NEX_INCLUDES'] =  $_ENV['SERVICES_ROOT'] . "/nexternal/includes";

  $_ENV['V65_HOST'] = $_ENV['APP_HOST'] . "/vin65";
  $_ENV['V65_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/vin65/includes";
}else{
  $_ENV['SERVICES_ROOT'] = dirname(dirname(__DIR__));

  $_ENV['DB_HOST'] = 'exw.zhc.mybluehost.me';
  $_ENV['DB_USERNAME'] = 'exwzhcmy_wgm';
  $_ENV['DB_PASSWORD'] = '0&QP*Cmy9SGp';
  $_ENV['DB_NAME'] = 'exwzhcmy_wgm';

  $_ENV['APP_ROOT'] = $_ENV['SERVICES_ROOT'] . "/src/app";
  $_ENV['CONFIG_ROOT'] = $_ENV['SERVICES_ROOT'] . "/src/config";
  $_ENV['APP_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/includes";
  $_ENV['TYPES_ROOT'] = $_ENV['SERVICES_ROOT'] . "/types";
  $_ENV['UPLOADS_PATH'] = "./uploads/";
  $_ENV['APP_HOST'] = "/";

  $_ENV['BLOYAL_HOST'] = "/bloyal";
  $_ENV['BLOYAL_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/bloyal/includes";

  $_ENV['NEX_HOST'] = "/nexternal";
  $_ENV['NEX_INCLUDES'] =  $_ENV['SERVICES_ROOT'] . "/nexternal/includes";

  $_ENV['V65_HOST'] = "/vin65";
  $_ENV['V65_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/vin65/includes";

}


?>
