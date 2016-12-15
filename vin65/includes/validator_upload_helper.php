<?php

require_once $_ENV['APP_ROOT'] . "/vin65/validators/abstract_validator.php";
use \wgm\vin65\validators\AbstractValidator as AbstractValidator;

if( count($_FILES) > 0 ){
  $validator->uploadFile( $_FILES['data_file'] );
  if( $validator->getState()==AbstractValidator::STATE_UPLOAD_COMPLETE ){
    $validator->runTests();
  }
}

?>
