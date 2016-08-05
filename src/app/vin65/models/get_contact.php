<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class GetContact extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/v201/contactService.cfc?wsdl";
    const SERVICE_NAME = "ContactService";
    const METHOD_NAME = "GetContact";

    function __construct($session, $version=2){
      // $this->_value_map = [
      //   "Security" => [
      //     "Username" => '',
      //     "Password" => ''
      //   ],
      //   "WebsiteID" => '',
      //   "ContactID" => '',
      //   "AltContactID" => '',
      //   "CustomerNumber" => 0
      // ];
      $this->_value_map = [
        "altContactID" => '',
        "city" => '',
        "company" => '',
        "contactID" => '',
        "contactType" => '',
        "customerNumber" => '',
        "eMail" => '',
        "firstName" => '',
        "fromDate" => '',
        "lastName" => '',
        "maxRecordCount" => 1,
        "page" => 1,
        "phone" => '',
        "toDate" => ''
      ];
      parent::__construct($session, $version);
    }

    public function getContact(){
      if( empty($this->_result) || !$this->_result->isSuccessful || empty($this->_result->contacts) ){
        return NULL;
      }
      return $this->_result->contacts[0];
    }

    public function getCustomerNumber(){
      return $this->_values["customerNumber"];
    }

    public function getValuesID(){
      if( isset($this->_values["customerNumber"]) && !empty($this->_values["customerNumber"]) ){
        return $this->_values["customerNumber"];
      }elseif( isset($this->_values["eMail"]) && !empty($this->_values["eMail"]) ){
        return $this->_values["eMail"];
      }
      return parent::getValuesID();
    }

    public function getResultID(){
      if( isset($this->_result->contacts) ){
        return $this->_result->contacts[0]->ContactID;
      }
      return parent::getResultID();
    }



    // public function callService($values=NULL){
    //   parent::callService($values);
    //   try{
    //     $client = new \SoapClient($_ENV['V65_CONTACT_SERVICE']);
    //     $result = $client->GetContact($this->_values);
    //     if( is_soap_fault($result) ){
    //       $this->_error = "SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})";
    //     }elseif ($result->IsSuccessful) {
    //       $this->setResult($result);
    //     }else{
    //       $this->_error = "SERVICE ERROR: </br>";
    //       foreach ($result->Errors as $value) {
    //         $this->_error .= "&nbsp;&nbsp;Code: " . $value->ErrorCode . "</br>";
    //         $this->_error .= "&nbsp;&nbsp;Message: " . $value->ErrorMessage . "</br>";
    //       }
    //     }
    //   }catch(Exception $e){
    //     $this->_error = "SOAP Exception: " . $e.message;
    //   }
    // }
  }

?>
