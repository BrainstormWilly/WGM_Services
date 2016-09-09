<?php namespace wgm\vin65\models;

require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

use wgm\models\ServiceInputForm as ServiceInputForm;

class PostOrderShippingStatus extends AbstractSoapModel{

  const SERVICE_WSDL = "https://webservices.vin65.com/v202/orderService.cfc?wsdl";
  const SERVICE_NAME = "OrderService";
  const METHOD_NAME = "PostOrderShippingStatus";

  function __construct($session, $version=2){
    $vf = ServiceInputForm::FieldValues();
    $vf['id'] = 'ordernumber';
    $vf['name'] = "Order Number";
    $vf['type'] = 'tel';
    array_push($this->_value_fields, $vf);

    $vf = ServiceInputForm::FieldValues();
    $vf['id'] = 'shippingstatusdate';
    $vf['name'] = "Shipping Status Date";
    $vf['type'] = 'date';
    array_push($this->_value_fields, $vf);

    $vf = ServiceInputForm::FieldValues();
    $vf['id'] = 'shippingstatus';
    $vf['name'] = "Shipping Status";
    $vf['type'] = 'radio';
    $vf['choices'] = [
      0 => ['id'=>'shipped', 'value'=>'Shipped', 'name'=>'Shipped'],
      1 => ['id'=>'pickedup', 'value'=>'PickedUp', 'name'=>'PickedUp'],
      2 => ['id'=>'external', 'value'=>'HandledExternally', 'name'=>'Handled Externally'],
      3 => ['id'=>'fulfillment', 'value'=>'SentToFulfillment', 'name'=>'Sent To Fulfillment'],
      4 => ['id'=>'noshipping', 'value'=>'NoShippingRequired', 'name'=>'No Shipping Required']
    ];
    array_push($this->_value_fields, $vf);

    $this->_value_map = [
      "ordernumber" => 'OrderNumber', // required
      "shippingstatus" => 'ShippingStatus', // required, PickedUp, Shipped, HandledExternally, SentToFulfillment, NoShippingRequired
      "shippingstatusdate" => 'ShippingStatusDate', // not required, defaults to today
    ];

    parent::__construct($session, 2);
    $this->_values['ordershippingstatus'] = [];

  }

  public function setValues($values){
    $status = [];
    foreach ($values as $key => $value) {
      $lkey = strtolower($key);
      if( $value != '' ){
        if( array_key_exists($lkey, $this->_value_map) ){
          if( $lkey=='shippingstatusdate' ){
            $value = DateConverter::toYMD($value);
          }
          $status[$this->_value_map[$lkey]] = $value;
        }
      }
    }
    array_push($this->_values["ordershippingstatus"], $status);
  }

  public function getResultID(){
    if( isset($this->_result->internalKeyCode) ){
      return $this->_result->internalKeyCode;
    }
    return parent::getResultID();
  }

  public function getValuesID(){
    if( isset($this->_values['ordershippingstatus'][0]['OrderNumber']) ){
      return $this->_values['ordershippingstatus'][0]['OrderNumber'];
    }
    return parent::getValuesID();

  }

  public function getValueCnt(){
    return 1;
  }

}

?>
