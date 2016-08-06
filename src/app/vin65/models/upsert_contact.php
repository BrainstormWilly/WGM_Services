<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class UpsertContact extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/v201/contactService.cfc?wsdl";
    const SERVICE_NAME = "ContactService";
    const METHOD_NAME = "UpsertContact";

    function __construct($session, $version=2){
      $this->_value_map = [
        "contactid" => 'ContactID',
        "lastname" => 'Lastname',
        "firstname" => 'Firstname',
        "title" => 'Title',
        "birthdate" => 'Birthdate',
        "company" => 'Company',
        "address" => 'Address',
        "address2" => 'Address2',
        "city" => 'City',
        "zipcode" => 'ZipCode',
        "countrycode" => 'CountryCode',
        "statecode" => 'StateCode',
        "mainphone" => 'MainPhone',
        "cellphone" => 'CellPhone',
        "fax" => 'Fax',
        "email" => 'Email',
        "issingleoptin" => 'IsSingleOptIn',
        "username" => 'Username',
        "password" => 'Password',
        "pricelevel" => 'PriceLevel',
        "isnontaxable" => 'IsNonTaxable',
        "isdirecttotrade" => 'IsDirectToTrade',
        "wholesalenumber" => 'WholesaleNumber',
        "paymentterms" => 'PaymentTerms',
        "facebookprofileid" => 'FacebookProfileID'
      ];

      parent::__construct($session, 2);

      $this->_values['contacts'] = [];

    }

    public function getResultID(){
      if( isset($this->_result->internalKeyCode) ){
        return $this->_result->internalKeyCode;
      }
      return parent::getResultID();
    }

    public function getValuesID(){
      if( count($this->_values["contacts"]) > 0) {
        if( isset($this->_values["contacts"][0]["Email"]) ){
          return $this->_values["contacts"][0]["Email"];
        }elseif( isset($this->_values["contacts"][0]["ContactID"]) ){
          return $this->_values["contacts"][0]["ContactID"];
        }
        return parent::getValuesID();
      }
      return parent::getValuesID();
    }
    public function setValues($values){
      $contact = [];
      foreach ($values as $key => $value) {
        if(!empty($value)){
          if( array_key_exists(strtolower($key), $this->_value_map) ){
              $contact[$this->_value_map[strtolower($key)]] = $value;
          }
        }
      }
      array_push($this->_values["contacts"], $contact);
    }

    // public function callService($values=NULL){
    //   parent::callService($values);
    //   try{
    //     $client = new \SoapClient($_ENV['V65_V2_CONTACT_SERVICE']);
    //     $result = $client->upsertContact($this->_values);
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
