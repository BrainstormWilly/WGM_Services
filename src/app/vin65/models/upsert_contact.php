<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class UpsertContact extends AbstractSoapModel{

    function __construct($session){
      $this->_value_map = [
        "ContactID" => '',
        "Lastname" => '',
        "Firstname" => '',
        "Title" => '',
        "Birthdate" => '',
        "Company" => '',
        "Address" => '',
        "Address2" => '',
        "City" => '',
        "ZipCode" => '',
        "CountryCode" => '',
        "StateCode" => '',
        "MainPhone" => '',
        "CellPhone" => '',
        "Fax" => '',
        "Email" => '',
        "IsSingleOptIn" => false,
        "Username" => '',
        "Password" => '',
        "PriceLevel" => '',
        "IsNonTaxable" => '',
        "IsDirectToTrade" => '',
        "WholesaleNumber" => '',
        "PaymentTerms" => '',
        "FacebookProfileID" => ''
      ];

      $this->_values = [
        "username" => $session["username"],
        'password' => $session['password'],
        'contacts' => []
      ];
    }

    public function addContactValues($props, $contact=NULL){
      if( $contact===NULL ){
        $contact = [];
      }
      foreach($props as $key => $value){
        if( array_key_exists($key, $this->_value_map) ){
          $contact[$key] = $value;
        }
      }
      return $contact;
    }

    public function getResultID(){
      if( isset($this->_result->Contact) ){
        return $this->_result->Contact->ContactID;
      }
      return "Unknown";
    }

    public function addContact($contact){
      array_push($this->_values['contacts'], $contact);
    }

    public function getValuesID(){
      if( count($this->_values["contacts"]) > 0) {
        if( isset($this->_values["contacts"][0]["Email"]) ){
          return $this->_values["contacts"][0]["Email"];
        }elseif( isset($this->_values["contacts"][0]["ContactID"]) ){
          return $this->_values["contacts"][0]["ContactID"];
        }
        return self::getValuesID();
      }
      return self::getValuesID();
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

    public function getEmail(){
      return $this->_values["contacts"][0]["Email"];
    }

  }

?>
