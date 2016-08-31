<?php namespace wgm\vin65\models;


  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';

  use wgm\models\ServiceInputForm as ServiceInputForm;


  class UpdateOrderStatus extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/v201/orderService.cfc?wsdl";
    const SERVICE_NAME = "OrderService";
    const METHOD_NAME = "UpsertOrder";

    function __construct($session, $version=2){

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'ordernumber';
      $vf['name'] = "Order Number";
      $vf['type'] = 'text';
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'billingemail';
      $vf['name'] = "Billing Email";
      $vf['type'] = 'text';
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'orderstatus';
      $vf['name'] = "Order Status";
      $vf['type'] = 'text';
      $vf['prompt'] = 'Pending, Submitted, Completed, Cancelled, Quarantined';
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'orderdate';
      $vf['name'] = "Order Date";
      $vf['type'] = 'date';
      // $vf['prompt'] = 'yyyy-mm-dd';
      array_push($this->_value_fields, $vf);


      $this->_value_map = [
        "billingemail" => 'BillingEmail', // required
        "orderstatus" => 'OrderStatus', // Pending, Submitted, Completed, Cancelled, Quarantined
        "ordernumber" => 'OrderNumber', // required
        "orderdate" => 'OrderDate' // required
      ];

      parent::__construct($session, 2);
      $this->_values['orders'] = [];
    }


    public function getValuesID(){
      $ids = [];
      foreach ($this->_values['orders'] as $value) {
        array_push($ids, $value["OrderNumber"]);
      }

      if( count($ids) > 0 ) return implode($ids, ",");

      return parent::getValuesID();

    }

    public function setValues($values){
      $order = [];
      foreach ($values as $key => $value) {
        if($value != ''){
          $lkey = strtolower($key);
          if( array_key_exists($lkey, $this->_value_map) ){
            if( $lkey=='orderdate' ){
              $value = DateConverter::toMDY($value);
            }
            $order[$this->_value_map[$lkey]] = $value;
          }
        }
      }
      array_push($this->_values["orders"], $order);
    }

    public function getResultID(){
      if( isset($this->_result->internalKeyCode) ){
        return $this->_result->internalKeyCode;
      }
      return parent::getResultID();
    }


  }

?>
