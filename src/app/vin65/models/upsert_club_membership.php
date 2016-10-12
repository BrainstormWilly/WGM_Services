<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';

  use wgm\models\ServiceInputForm as ServiceInputForm;

  class UpsertClubMembership extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/v201/contactService.cfc?wsdl";
    const SERVICE_NAME = "ContactService";
    const METHOD_NAME = "UpsertClubMembership";

    function __construct($session, $version=2){

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'customernumber';
      $vf['name'] = "Customer Number";
      $vf['type'] = 'integer';
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'email';
      $vf['name'] = "Email";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'contactid';
      $vf['name'] = "Contact ID";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      // $vf = ServiceInputForm::FieldValues();
      // $vf['id'] = 'clubmembershipid';
      // $vf['name'] = "Club Membership ID (required for update only)";
      // $vf['required'] = FALSE;
      // array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'clubname';
      $vf['name'] = "Club Name";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'signupdate';
      $vf['name'] = "Sign Up Date";
      $vf['type'] = 'date';
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'canceldate';
      $vf['name'] = "Cancel Date";
      $vf['type'] = 'date';
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'onholdstartdate';
      $vf['name'] = "On Hold Start Date";
      $vf['type'] = 'date';
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'onholduntildate';
      $vf['name'] = "On Hold Until Date";
      $vf['type'] = 'date';
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'notes';
      $vf['name'] = "Club Notes";
      $vf['type'] = 'textarea';
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'isgift';
      $vf['name'] = "Is Gift";
      $vf['type'] = "radio";
      $vf['choices'] = [
        0 => ['id'=>'no', 'value'=>'0', 'name'=>'No'],
        1 => ['id'=>'yes', 'value'=>'1', 'name'=>'Yes']
      ];
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'giftmessage';
      $vf['name'] = "Gift Message";
      $vf['type'] = 'textarea';
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'ispickup';
      $vf['name'] = "Is Pick Up";
      $vf['type'] = "radio";
      $vf['choices'] = [
        0 => ['id'=>'no', 'value'=>'0', 'name'=>'No'],
        1 => ['id'=>'yes', 'value'=>'1', 'name'=>'Yes']
      ];
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'pickuplocationcode';
      $vf['name'] = "Pick Up Location Code (required if 'Is Pick Up')";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'shipto';
      $vf['name'] = "Ship To";
      $vf['type'] = "radio";
      $vf['choices'] = [
        0 => ['id'=>'billing', 'value'=>'BillingAddress', 'name'=>'BillingAddress'],
        1 => ['id'=>'shipping', 'value'=>'ShippingAddress', 'name'=>'ShippingAddress']
      ];
      array_push($this->_value_fields, $vf);

      $this->_value_map = [
        "altclubid" => "AltClubID",
        "altclubmembershipid" => "AltClubMembershipID",
        "altcontactid" => "AltContactID",
        "altshippingaddressid" => "AltShippingAddressID",
        "canceldate" => "CancelDate",
        "cancellationreason" => "CancellationReason",
        "clubmembershipid" => "ClubMembershipID",
        "clubname" => "ClubName",
        "contactid" => "ContactID",
        "creditcardid" => "CreditCardID",
        "giftmessage" => "GiftMessage",
        "isgift" => "IsGift",
        "ispickup" => "IsPickup",
        "isprepay" => "IsPrePay",
        "notes" => "Notes",
        "onholdstartdate" => "OnHoldStartDate",
        "onholduntildate" => "OnHoldUntilDate",
        "pickuphandlingfee" => "PickupHandlingFee",
        "pickuplocationcode" => "PickupLocationCode",
        "prepayordernumber" => "PrePayOrderNumber",
        "retainclubprivileges" => "RetainClubPrivileges",
        "shipto" => "ShipTo",
        "shippingaddressid" => "ShippingAddressID",
        "signupdate" => "SignupDate",
        "sourcecode" => "SourceCode",
        "totalnumberofshipments" => "TotalNumberOfShipments"
      //   "customernumber" => "CustomerNumber", // used in GetContact
      //   "email" => "Email", // used in GetContact
      ];

      parent::__construct($session, 2);

      $this->_values['clubMemberships'] = [];

    }

    public function getResultID(){
      if( isset($this->_result->internalKeyCode) ){
        return $this->_result->internalKeyCode;
      }
      return parent::getResultID();
    }

    public function getValuesID(){
      if( count($this->_values["clubMemberships"]) > 0) {
        if( isset($this->_values["clubMemberships"][0]["CustomerNumber"]) ){
          return $this->_values["clubMemberships"][0]["CustomerNumber"];
        }elseif( isset($this->_values["clubMemberships"][0]["Email"]) ){
          return $this->_values["clubMemberships"][0]["Email"];
        }elseif( isset($this->_values["clubMemberships"][0]["ContactID"]) ){
          return $this->_values["clubMemberships"][0]["ContactID"];
        }
        return parent::getValuesID();
      }
      return parent::getValuesID();
    }
    public function setValues($values){
      $cm = [];

      foreach ($values as $key => $value) {
        if($value !== ''){
          $lkey = strtolower($key);
          if( array_key_exists($lkey, $this->_value_map) ){
              if( $lkey=='signupdate' ||
                  $lkey=='onholdstartdate' ||
                  $lkey=='onholduntildate' ||
                  $lkey=='canceldate'){
                    $value = DateConverter::toYMD($value);
                  }
              $cm[$this->_value_map[$lkey]] = $value;
          }
        }
      }
      // $cm['ClubMembershipID'] = '1234';
      // print_r($cm);exit;
      array_push($this->_values["clubMemberships"], $cm);
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
