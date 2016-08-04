<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/cc_encoder.php';


  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;
  use wgm\vin65\models\CCEncoder as CCE;

  class AddUpdateCC extends AbstractSoapModel{

    function __construct($session){
      $this->_value_map = [
        "Security" => [
          "Username" => '',
          "Password" => ''
        ],
        "CreditCard" => [
          "WebsiteID" => "",
          "CreditCardID" => "",
          "IsPrimary" => "",
          "CreditCardType" => "",
          "CardExpiryMonth" => "",
          "CardExpiryYear" => "",
          "EncryptedCardNumber" => "",
          "ContactID" => ""
        ],
        "Mode" => "Strict"
      ];

      parent::__construct($session, 3);
      $this->_values['CreditCard'] = [];
      $this->_values['Mode'] = 'Strict';
    }

    public function setValues($values){
      foreach ($values as $key => $value) {
        if( array_key_exists($key, $this->_value_map["CreditCard"]) ){
          $this->_values["CreditCard"][$key] = $value;
        }elseif($key=="CardNumber"){
          $this->_values["CreditCard"]["EncryptedCardNumber"] = CCE::encode($value, $_SESSION['CC_KEY'], $_SESSION['CC_SALT']);
        }
      }
    }

    public function callService($values=NULL){
      parent::callService($values);
      try{
        $client = new \SoapClient($_ENV['V65_CC_SERVICE']);
        $result = $client->AddUpdateCreditCard($this->_values);
        if( is_soap_fault($result) ){
          $this->_error = "SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})";
        }elseif($result->IsSuccessful){
          $this->_result = $result->CreditCardID ;
        }else{
          $this->_error = "SERVICE ERROR: </br>";
          foreach ($result->Errors as $value) {
            $this->_error .= "&nbsp;&nbsp;Code: " . $value->ErrorCode . "</br>";
            $this->_error .= "&nbsp;&nbsp;Message: " . $value->ErrorMessage . "</br>";
          }
        }
      }catch(Exception $e){
        $this->_error = "SOAP Exception: " . $e->message;
      }
    }

  }

?>
