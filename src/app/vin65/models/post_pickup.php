<?php namespace wgm\vin65\models;

require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

use wgm\models\ServiceInputForm as ServiceInputForm;

class PostPickup extends AbstractSoapModel{

  const SERVICE_WSDL = "https://webservices.vin65.com/v202/orderService.cfc?wsdl";
  const SERVICE_NAME = "OrderService";
  const METHOD_NAME = "PostPickup";

  function __construct($session, $version=2){

    $vf = ServiceInputForm::FieldValues();
    $vf['id'] = 'ordernumber';
    $vf['name'] = "Order Number";
    $vf['type'] = 'tel';
    array_push($this->_value_fields, $vf);

    $vf = ServiceInputForm::FieldValues();
    $vf['id'] = 'pickupdate';
    $vf['name'] = "Pickup Date";
    $vf['type'] = 'date';
    array_push($this->_value_fields, $vf);

    $vf = ServiceInputForm::FieldValues();
    $vf['id'] = 'ispickedup';
    $vf['name'] = "Is Picked Up?";
    $vf['type'] = 'radio';
    $vf['choices'] = [
      0 => ['id'=>'yes', 'value'=>1, 'name'=>'Yes'],
      1 => ['id'=>'no', 'value'=>0, 'name'=>'No']
    ];
    array_push($this->_value_fields, $vf);


    $this->_value_map = [
      "ordernumber" => 'OrderNumber', // required
      "pickupdate" => 'PickupDate', // if left blank will default to today
      "ispickedup" => 'isPickedUp', // blank is false
    ];

    parent::__construct($session, 2);
    $this->_values['pickups'] = [];
  }

  public function setValues($values){
    $status = [];
    foreach ($values as $key => $value) {
      $lkey = strtolower($key);
      if($value != ''){
        if( array_key_exists($lkey, $this->_value_map) ){
          if( $lkey=='pickupdate' ){
            $value = DateConverter::toYMD($value);
          }
          $status[$this->_value_map[$lkey]] = $value;
        }
      }
    }
    array_push($this->_values['pickups'], $status);
  }

  public function getResultID(){
    if( isset($this->_result->internalKeyCode) ){
      return $this->_result->internalKeyCode;
    }
    return parent::getResultID();
  }

  public function getValuesID(){
    if( isset($this->_values['OrderNumber']) ){
      return $this->_values['OrderNumber'];
    }
    return parent::getValuesID();

  }

  public function getValueCnt(){
    return 1;
  }

}

?>
