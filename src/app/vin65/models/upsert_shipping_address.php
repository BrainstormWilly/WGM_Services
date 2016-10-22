<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';

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
        if( $value !== '' ){
          if( array_key_exists(strtolower($key), $this->_value_map) ){
              $addr[$this->_value_map[strtolower($key)]] = utf8_encode($value);
          }
        }
      }
      array_push($this->_values["shippingAddresses"], $addr);
    }


  }

?>
