<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class UpsertShippingAddress extends AbstractSoapModel{

    function __construct($session){
      $this->_value_map = [
        "ContactID" => '',
        "Lastname" => '',
        "Firstname" => '',
        "Nickname" => '',
        "Birthdate" => '',
        "Company" => '',
        "Address" => '',
        "Address2" => '',
        "City" => '',
        "StateCode" => '',
        "ZipCode" => '',
        "CountryCode" => '',
        "MainPhone" => '',
        "Email" => '',
        "IsPrimary" => 0
      ];

      parent::__construct($session, 2);
      $this->_values['shippingAddresses'] = [];

    }

    public function addAddressValues($props, $address=NULL){
      if( $address===NULL ){
        $address = [];
      }
      foreach($props as $key => $value){
        if( array_key_exists($key, $this->_value_map) ){
          $address[$key] = $value;
        }
      }
      return $address;
    }

    public function addAddress($address){
      array_push($this->_values['shippingAddresses'], $address);
    }

    public function callService($values=NULL){
      parent::callService();
      try{
        $client = new \SoapClient($_ENV['V65_V2_CONTACT_SERVICE']);
        $result = $client->upsertShippingAddress($this->_values);
        // print_r($this->_values);
        if( is_soap_fault($result) ){
          $this->_error = "SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})";
        }elseif(empty($result->results[0]->isSuccessful)){
          $this->_error = $result->results[0]->message;
        }else{
          $this->_result = $result->results[0]->internalKeyCode ;
        }
      }catch(Exception $e){
        $this->_error = $e->message;
      }
    }

  }

?>
