<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/cc_encoder.php';


  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;
  use wgm\vin65\models\CCEncoder as CCE;



  class AddUpdateCC extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/CreditCardServiceX.cfc?wsdl";
    const SERVICE_NAME = "CreditCardService";
    const METHOD_NAME = "AddUpdateCreditCard";

    function __construct($session){
      $this->_value_map = [
        "websiteid" => "WebsiteID",
        "creditcardid" => "CreditCardID",
        "isprimary" => "IsPrimary",
        "creditcardtype" => "CreditCardType",
        "cardexpirymonth" => "CardExpiryMonth",
        "cardexpiryyear" => "CardExpiryYear",
        "encryptedcardnumber" => "EncryptedCardNumber",
        "contactid" => "ContactID"
      ];

      parent::__construct($session, 3);
      $this->_values['CreditCard'] = [];
      $this->_values['Mode'] = 'Strict';
    }

    public function setValues($values){
      foreach ($values as $key => $value) {
        if( array_key_exists(strtolower($key), $this->_value_map) ){
          $this->_values["CreditCard"][$this->_value_map[strtolower($key)]] = $value;
        }elseif(strtolower($key)=="cardnumber"){
          $this->_values["CreditCard"]["EncryptedCardNumber"] = CCE::encode($value, $_SESSION['CC_KEY'], $_SESSION['CC_SALT']);
        }
      }
    }

    public function getValuesID(){
      if( isset($this->_values["CreditCard"]["CreditCardID"]) ){
        return $this->_values["CreditCard"]["CreditCardID"];
      }elseif ( isset($this->_values["CreditCard"]["EncryptedCardNumber"]) ) {
        return $this->_values["CreditCard"]["EncryptedCardNumber"] . " (encrypted)";
      }
      return parent::getValuesID();
    }

    public function getResultID(){
      if( isset($this->_result->CreditCardID) ){
        return $this->_result->CreditCardID;
      }
      return parent::getResultID();
    }

    // public function callService($values=NULL){
    //   parent::callService($values);
    //   try{
    //     $client = new \SoapClient($_ENV['V65_CC_SERVICE']);
    //     $result = $client->AddUpdateCreditCard($this->_values);
    //     if( is_soap_fault($result) ){
    //       $this->_error = "SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})";
    //     }elseif($result->IsSuccessful){
    //       $this->_result = $result->CreditCardID ;
    //     }else{
    //       $this->_error = "SERVICE ERROR: </br>";
    //       foreach ($result->Errors as $value) {
    //         $this->_error .= "&nbsp;&nbsp;Code: " . $value->ErrorCode . "</br>";
    //         $this->_error .= "&nbsp;&nbsp;Message: " . $value->ErrorMessage . "</br>";
    //       }
    //     }
    //   }catch(Exception $e){
    //     $this->_error = "SOAP Exception: " . $e->message;
    //   }
    // }

  }

?>
