<?php namespace wgm\vin65\models;

require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

use wgm\models\ServiceInputForm as ServiceInputForm;

class PostOrderTracking extends AbstractSoapModel{

  const SERVICE_WSDL = "https://webservices.vin65.com/v202/orderService.cfc?wsdl";
  const SERVICE_NAME = "OrderService";
  const METHOD_NAME = "PostOrderTracking";

  function __construct($session, $version=2){

    $vf = ServiceInputForm::FieldValues();
    $vf['id'] = 'ordernumber';
    $vf['name'] = "Order Number";
    $vf['type'] = 'tel';
    array_push($this->_value_fields, $vf);

    $vf = ServiceInputForm::FieldValues();
    $vf['id'] = 'trackingnumber';
    $vf['name'] = "Tracking Code";
    array_push($this->_value_fields, $vf);

    $vf = ServiceInputForm::FieldValues();
    $vf['id'] = 'shipdate';
    $vf['name'] = "Ship Date";
    $vf['type'] = 'date';
    array_push($this->_value_fields, $vf);

    $vf = ServiceInputForm::FieldValues();
    $vf['id'] = 'sendtrackingemail';
    $vf['name'] = "Send Tracking Email?";
    $vf['type'] = 'radio';
    $vf['choices'] = [
      0 => ['id'=>'yes', 'value'=>1, 'name'=>'Yes'],
      1 => ['id'=>'no', 'value'=>0, 'name'=>'No']
    ];
    array_push($this->_value_fields, $vf);

    $this->_value_map = [
      "ordernumber" => 'OrderNumber', // required
      "trackingnumber" => 'TrackingNumber', // required
      "shipdate" => 'ShipDate', // defaults to today
      "sendtrackingemail" => 'SendTrackingEmail', // blank is false
    ];

    parent::__construct($session, 2);
    $this->_values['ordertracking'] = [];
  }

  public function setValues($values){
    $status = [];
    foreach ($values as $key => $value) {
      $lkey = strtolower($key);
      if($value != ''){
        if( array_key_exists($lkey, $this->_value_map) ){
          if( $lkey=='shipdate' ){
            $value = DateConverter::toYMD($value);
          }
          $status[$this->_value_map[$lkey]] = $value;
        }
      }
    }
    array_push($this->_values['ordertracking'], $status);
  }

  public function getResultID(){
    if( isset($this->_result->internalKeyCode) ){
      return $this->_result->internalKeyCode;
    }
    return parent::getResultID();
  }

  public function getValuesID(){
    if( count($this->_values['ordertracking']) > 0 ){
      return $this->_values['ordertracking'][0]['OrderNumber'] . ":" . $this->_values['ordertracking'][0]['TrackingNumber'];
    }
    return parent::getValuesID();

  }

  public function getValueCnt(){
    return 1;
  }

}

?>
