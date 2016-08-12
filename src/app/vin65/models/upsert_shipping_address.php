<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class UpsertShippingAddress extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/v201/contactService.cfc?wsdl";
    const SERVICE_NAME = "ContactService";
    const METHOD_NAME = "UpsertShippingAddress";

    function __construct($session, $version=2){
      $this->_value_map = [
        "contactid" => 'ContactID',
        "lastname" => 'Lastname',
        "firstname" => 'Firstname',
        "nickname" => 'Nickname',
        "birthdate" => 'Birthdate',
        "company" => 'Company',
        "address" => 'Address',
        "address2" => 'Address2',
        "city" => 'City',
        "statecode" => 'StateCode',
        "zipcode" => 'ZipCode',
        "countrycode" => 'CountryCode',
        "mainphone" => 'MainPhone',
        "email" => 'Email',
        "isprimary" => 'IsPrimary'
      ];

      parent::__construct($session, 2);
      $this->_values['shippingAddresses'] = [];

    }

    public function getResultID(){
      if( isset($this->_result->internalKeyCode) ){
        return $this->_result->internalKeyCode;
      }
      return parent::getResultID();
    }

    public function getValuesID(){
      if( count($this->_values["shippingAddresses"]) > 0) {
        if( isset($this->_values["shippingAddresses"][0]["Email"]) ){
          return $this->_values["shippingAddresses"][0]["Email"];
        }elseif( isset($this->_values["shippingAddresses"][0]["ContactID"]) ){
          return $this->_values["shippingAddresses"][0]["ContactID"];
        }
        return parent::getValuesID();
      }
      return parent::getValuesID();
    }

    public function setValues($values){
      $addr = [];
      foreach ($values as $key => $value) {
        if(!empty($value)){
          if( array_key_exists(strtolower($key), $this->_value_map) ){
              $addr[$this->_value_map[strtolower($key)]] = $value;
          }
        }
      }
      array_push($this->_values["shippingAddresses"], $addr);
    }

    // public function callService($values=NULL){
    //   parent::callService();
    //   try{
    //     $client = new \SoapClient($_ENV['V65_V2_CONTACT_SERVICE']);
    //     $result = $client->upsertShippingAddress($this->_values);
    //     // print_r($this->_values);
    //     if( is_soap_fault($result) ){
    //       $this->_error = "SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})";
    //     }elseif(empty($result->results[0]->isSuccessful)){
    //       $this->_error = $result->results[0]->message;
    //     }else{
    //       $this->_result = $result->results[0]->internalKeyCode ;
    //     }
    //   }catch(Exception $e){
    //     $this->_error = $e->message;
    //   }
    // }

  }

?>