<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';

  class UpdateClubMembership extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/v201/contactService.cfc?wsdl";
    const SERVICE_NAME = "ContactService";
    const METHOD_NAME = "UpsertClubMembership";

    function __construct($session, $version=2){

      // $vf = ServiceInputForm::FieldValues();
      // $vf['id'] = 'contactid';
      // $vf['name'] = "Contact ID";
      // array_push($this->_value_fields, $vf);
      //
      // $vf = ServiceInputForm::FieldValues();
      // $vf['id'] = 'clubname';
      // $vf['name'] = "Club Name";
      // array_push($this->_value_fields, $vf);
      //
      // $vf = ServiceInputForm::FieldValues();
      // $vf['id'] = 'clubname';
      // $vf['name'] = "Club Name";
      // array_push($this->_value_fields, $vf);

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
        "totalnumberofshipments" => "TotalNumberOfShipments",
        "customernumber" => "CustomerNumber",
        "email" => "Email",
        "updatekey" => "UpdateKey",
        "updatevalue" => "UpdateValue"
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
        $id = $this->_values["clubMemberships"][0]["UpdateBy"];
        if( isset($id) ){
          return $this->_values["clubMemberships"][0][$id];
        }elseif( isset($this->_values["clubMemberships"][0]["Email"]) ){
          return $this->_values["clubMemberships"][0]["Email"];
        }elseif( isset($this->_values["clubMemberships"][0]["ContactID"]) ){
          return $this->_values["clubMemberships"][0]["ContactID"];
        }
      }
      return parent::getValuesID();
    }
    public function setValues($values){
      $cm = [];
      foreach ($values as $key => $value) {
        if(!empty($value)){
          $lkey = strtolower($key);
          if( array_key_exists($lkey, $this->_value_map) ){
              if( $lkey=="updatekey" ){
                $value = $this->_value_map[$this->_value_map[strtolower($value)]]
              }
              $cm[$this->_value_map[$lkey]] = $value;
          }
        }
      }
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
