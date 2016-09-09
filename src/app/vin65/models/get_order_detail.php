<?php namespace wgm\vin65\models;


  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';

  use wgm\models\ServiceInputForm as ServiceInputForm;


  class GetOrderDetail extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V301/OrderService.cfc?wsdl";
    const SERVICE_NAME = "OrderService";
    const METHOD_NAME = "GetOrderDetail";

    function __construct($session, $version=3){

      $vf = ServiceInputForm::FieldValues();
      $vf['required'] = FALSE;
      $vf['id'] = 'ordernumber';
      $vf['name'] = "Order Number";
      $vf['type'] = 'tel';
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['required'] = FALSE;
      $vf['id'] = 'orderid';
      $vf['name'] = "Order ID";
      $vf['type'] = 'text';
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['required'] = FALSE;
      $vf['id'] = 'showkitasindividualskus';
      $vf['name'] = "Show Kit as Individual SKUs";
      $vf['type'] = 'radio';
      $vf['choices'] = [
        0 => ['id'=>'no', 'name'=>'No', 'value'=>0],
        1 => ['id'=>'yes', 'name'=>'Yes', 'value'=>1]
      ];
      array_push($this->_value_fields, $vf);


      $this->_value_map = [
        "orderid" => 'OrderID',
        "ordernumber" => 'OrderNumber',
        "showkitasindividualskus" => 'ShowKitAsIndividualSKUs' // default = False
      ];

      parent::__construct($session, 3);
    }


    public function getValuesID(){
      if( isset($this->_values['OrderID']) ){
        return $this->_values['OrderID'];
      }elseif( isset($this->_values['OrderNumber']) ) {
        return $this->_values['OrderNumber'];
      }

      return parent::getValuesID();

    }


    public function getResultID(){
      if( isset($this->_result->Order->OrderNumber) ){
        return $this->_result->Order->OrderNumber;
      }
      return parent::getResultID();
    }


  }

?>
