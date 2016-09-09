<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/cc_encoder.php';
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;
  use wgm\vin65\models\CCEncoder as CCE;



  class AddUpdateCC extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/CreditCardServiceX.cfc?wsdl";
    const SERVICE_NAME = "CreditCardService";
    const METHOD_NAME = "AddUpdateCreditCard";

    function __construct($session){

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'customernumber';
      $vf['name'] = "Customer Number";
      $vf['type'] = 'integer';
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'creditcardtype';
      $vf['name'] = "Card Type";
      $vf['type'] = 'radio';
      $vf['choices'] = [
        0 => ['id'=>'visa', 'value'=>"Visa", 'name'=>'Visa'],
        1 => ['id'=>'mc', 'value'=>"MasterCard", 'name'=>'MasterCard'],
        2 => ['id'=>'ae', 'value'=>'AmericanExpress', 'name'=>'AmericanExpress'],
        3 => ['id'=>'d', 'value'=>'Discover', 'name'=>'Discover'],
        4 => ['id'=>'jcb', 'value'=>'JCB', 'name'=>'JCB']
      ];
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'cardnumber';
      $vf['name'] = "Card Number";
      $vf['type'] = "integer";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'cardexpirymonth';
      $vf['name'] = "Card Expiration Month (1-12)";
      $vf['type'] = "month";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'cardexpiryyear';
      $vf['name'] = "Card Expiration Year (YYYY)";
      $vf['type'] = "year";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'nameoncard';
      $vf['name'] = "Name on Card";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'isprimary';
      $vf['name'] = "Is Primary?";
      $vf['type'] = 'radio';
      $vf['choices'] = [
        0 => ['id'=>'yes', 'value'=>1, 'name'=>'Yes'],
        1 => ['id'=>'no', 'value'=>0, 'name'=>'No']
      ];
      array_push($this->_value_fields, $vf);


      $this->_value_map = [
        "websiteid" => "WebsiteID",
        "creditcardid" => "CreditCardID",
        "isprimary" => "IsPrimary",
        "creditcardtype" => "CreditCardType",
        "cardexpirymonth" => "CardExpiryMonth",
        "cardexpiryyear" => "CardExpiryYear",
        "encryptedcardnumber" => "EncryptedCardNumber",
        "nameoncard" => "NameOnCard",
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
