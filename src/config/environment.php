<?php

if( $_SERVER['SERVER_NAME']=='wgm.dev' ){
  $_ENV['APP_ENV'] = 'dev';
}else{
  $_ENV['APP_ENV'] = 'prod';
}

if( $_ENV['APP_ENV'] === 'dev' ){
  $_ENV['SERVICES_ROOT'] = dirname(dirname(__DIR__));

  $_ENV['APP_ROOT'] = $_ENV['SERVICES_ROOT'] . "/src/app";
  $_ENV['APP_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/includes";
  $_ENV['TYPES_ROOT'] = $_ENV['SERVICES_ROOT'] . "/types";
  $_ENV['UPLOADS_PATH'] = "./uploads/";
  $_ENV['APP_HOST'] = "/services";

  $_ENV['BLOYAL_HOST'] = "/services/bloyal";
  $_ENV['BLOYAL_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/bloyal/includes";

  $_ENV['NEX_HOST'] = "/services/nexternal";
  $_ENV['NEX_INCLUDES'] =  $_ENV['SERVICES_ROOT'] . "/nexternal/includes";

  $_ENV['V65_HOST'] = "/services/vin65";
  $_ENV['V65_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/vin65/includes";
}else{
  $_ENV['SERVICES_ROOT'] = dirname(dirname(__DIR__));

  $_ENV['APP_ROOT'] = $_ENV['SERVICES_ROOT'] . "/src/app";
  $_ENV['APP_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/includes";
  $_ENV['TYPES_ROOT'] = $_ENV['SERVICES_ROOT'] . "/types";
  $_ENV['UPLOADS_PATH'] = "./uploads/";
  $_ENV['APP_HOST'] = "/services";

  $_ENV['BLOYAL_HOST'] = "/services/bloyal";
  $_ENV['BLOYAL_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/bloyal/includes";

  $_ENV['NEX_HOST'] = "/services/nexternal";
  $_ENV['NEX_INCLUDES'] =  $_ENV['SERVICES_ROOT'] . "/nexternal/includes";

  $_ENV['V65_HOST'] = "/services/vin65";
  $_ENV['V65_INCLUDES'] = $_ENV['SERVICES_ROOT'] . "/vin65/includes";

  echo $_ENV['SERVICES_ROOT'];
}

?>
