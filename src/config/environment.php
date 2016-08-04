<?php



$_ENV['APP_ENV'] = 'dev';

if( strpos( $_SERVER['HTTP_USER_AGENT'], "Windows") ){
  // $_ENV['TYPES_ROOT'] = $_SERVER['DOCUMENT_ROOT'] . "\\wgm\\services\\types";
  $_ENV['APP_ROOT'] = $_SERVER['DOCUMENT_ROOT'] . "\wgm\services\src\app";
  $_ENV['APP_INCLUDES'] = $_SERVER['DOCUMENT_ROOT'] . "\wgm\services\includes";
}else{
  $_ENV['APP_ROOT'] = $_SERVER['DOCUMENT_ROOT'] . "/wgm/services/src/app";
  $_ENV['APP_INCLUDES'] = $_SERVER['DOCUMENT_ROOT'] . "/wgm/services/includes";
}
$_ENV['TYPES_ROOT'] = $_SERVER['DOCUMENT_ROOT'] . "/wgm/services/types";
$_ENV['UPLOADS_PATH'] = "./uploads/";
$_ENV['APP_HOST'] = "http://localhost/wgm/services";

$_ENV['BLOYAL_HOST'] = "http://localhost/wgm/bloyal";
$_ENV['BLOYAL_INCLUDES'] = $_SERVER['DOCUMENT_ROOT'] . "/wgm/services/bloyal/includes";

$_ENV['V65_HOST'] = "http://localhost/wgm/services/vin65";
$_ENV['V65_INCLUDES'] = $_SERVER['DOCUMENT_ROOT'] . "/wgm/services/vin65/includes";
$_ENV['V65_V2_CONTACT_SERVICE'] = "https://webservices.vin65.com/v201/contactService.cfc?wsdl";
$_ENV['V65_V2_ORDER_SERVICES'] = "https://webservices.vin65.com/v201/orderService.cfc?wsdl";
$_ENV['V65_CONTACT_SERVICE'] = "https://webservices.vin65.com/V300/ContactService.cfc?wsdl";
$_ENV['V65_NOTE_SERVICE'] = "https://webservices.vin65.com/V300/NoteService.cfc?wsdl";
$_ENV['V65_CC_SERVICE'] = "https://webservices.vin65.com/V300/CreditCardServiceX.cfc?wsdl";
$_ENV['V65_GIFTCARD_SERVICE'] = "https://webservices.vin65.com/V300/GiftCardService.cfc?wsdl";

?>
