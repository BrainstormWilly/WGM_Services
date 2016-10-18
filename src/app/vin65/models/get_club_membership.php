<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';

  use wgm\models\ServiceInputForm as ServiceInputForm;

  class GetClubMembership extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/v201/contactService.cfc?wsdl";
    const SERVICE_NAME = "ContactService";
    const METHOD_NAME = "GetClubMembership";

    function __construct($session, $version=2){

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'contactid';
      $vf['name'] = "Contact ID";
      $vf['required'] = false;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'customernumber';
      $vf['name'] = "Customer Number";
      $vf['type'] = 'integer';
      $vf['required'] = false;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'email';
      $vf['name'] = "Customer Email";
      $vf['required'] = false;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'clubmembershipid';
      $vf['name'] = "Club Membership ID";
      $vf['required'] = false;
      array_push($this->_value_fields, $vf);

      $this->_value_map = [
        "clubmembershipid" => 'ClubMembershipID',
        'contactid' => 'contactID',
        'customernumber' => 'CustomerNumber',
        'email' => "Email"
      ];

      parent::__construct($session, 2);
    }

    // public function getContact(){
    //   if( empty($this->_result) || !$this->_result->isSuccessful || empty($this->_result->contacts) ){
    //     return NULL;
    //   }
    //   return $this->_result->contacts[0];
    // }

    public function getValuesID(){
      if( isset($this->values['CustomerNumber']) && $this->_values["CustomerNumber"] != '' ){
        return $this->_values["CustomerNumber"];
      }elseif( isset($this->_values["Email"]) && $this->_values["Email"] != '' ){
        return $this->_values["Email"];
      }elseif( isset($this->_values["ContactID"]) && $this->_values["ContactID"] != '' ){
        return $this->_values["ContactID"];
      }elseif( isset($this->_values["ClubMembershipID"]) && $this->_values["ClubMembershipID"] != '' ){
        return $this->_values["ClubMembershipID"];
      }
      return parent::getValuesID();
    }
    // public function setValues($values){
    //   foreach ($values as $key => $value) {
    //     if( $value !== '' ){
    //       if( array_key_exists(strtolower($key), $this->_value_map) ){
    //         $this->_values[$this->_value_map[strtolower($key)]] = $value;
    //       }
    //     }
    //   }
    //   if( isset($this->_values["customerNumber"]) && isset($this->_values['eMail']) ){
    //     unset($this->_values['eMail']);
    //   }
    // }

    public function getResultID(){
      if( count($this->_result->clubMemberships) > 0 ){
        $r = '';
        foreach ($this->_result->clubMemberships as $value) {
          $r .= $value->ClubMembershipID . ", ";
        }
      }
      return parent::getResultID();
    }

    // public function setResult($result){
    //
    //   parent::setResult($result);
    //
    //   if( $result->recordCount == 0 ){
    //     $this->_result = NULL;
    //     $this->_error = "No contacts found";
    //   }
    //
    // }



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
