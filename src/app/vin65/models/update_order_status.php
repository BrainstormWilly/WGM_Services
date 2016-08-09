<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;


  class UpdateOrderStatus extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/v201/orderService.cfc?wsdl";
    const SERVICE_NAME = "OrderService";
    const METHOD_NAME = "UpsertOrder";

    function __construct($session, $version=2){

      $this->_value_fields = [
        ["OrderNumber", "*Order Number", "text", "Required"],
        ["BillingEmail", "Billing Email", "text", NULL],
        ['OrderStatus', "*Order Status", "text", "Pending, Submitted, Completed, Cancelled, Quarantined"]
      ];


      $this->_value_map = [
        "billingemail" => 'BillingEmail', // required
        "orderstatus" => 'OrderStatus', // Pending, Submitted, Completed, Cancelled, Quarantined
        "ordernumber" => 'OrderNumber' // required
      ];

      parent::__construct($session, 2);
      $this->_values['orders'] = [];
    }



    public function getValuesID(){
      $ids = [];
      foreach ($this->_values['orders'] as $value) {
        if( isset($value["OrderNumber"]) && !empty($values["OrderNumber"]) ){
          array_push($ids, $value["OrderNumber"]);
        }
      }

      if( count($ids) > 0 ) return implode($ids, ",");

      return parent::getValuesID();

    }
    public function setValues($values){
      $order = [];
      foreach ($values as $key => $value) {
        if(!empty($value)){
          if( array_key_exists(strtolower($key), $this->_value_map) ){
              $order[$this->_value_map[strtolower($key)]] = $value;
          }
        }
      }
      array_push($this->_values["orders"], $order);

      var_dump($this->_values);
      exit;
    }

    public function getResultID(){
      if( isset($this->_result->internalKeyCode) ){
        return $this->_result->internalKeyCode;
      }
      return parent::getResultID();
    }


  }

?>
