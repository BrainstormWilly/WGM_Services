<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';

  use wgm\models\ServiceInputForm as ServiceInputForm;

  class GetGiftCard extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/GiftCardService.cfc?wsdl";
    const SERVICE_NAME = "GiftCardService";
    const METHOD_NAME = "GetGiftCard";

    function __construct($session, $version=3){

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'cardnumber';
      $vf['name'] = "Gift Card Number";
      $vf['type'] = "integer";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'code';
      $vf['name'] = "Gift Code";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'giftcardid';
      $vf['name'] = "Gift Card ID";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $this->_value_map = [
        "websiteid" => 'WebsiteID',
        "giftcardid" => 'GiftCardID',
        "cardnumber" => 'CardNumber',
        "code" => 'Code'
      ];

      parent::__construct($session, $version);
    }

    public function setValues($values){
      if( array_key_exists("giftcardid", $values) ){
        $this->_values["GiftCardID"] = $values['giftcardid'];
      }elseif (array_key_exists("cardnumber", $values)) {
        $this->_values["CardNumber"] = $values['cardnumber'];
      }else{
        $this->_values["Code"] = $values['code'];
      }

    }

    public function getValuesID(){
      if( isset($this->_values['GiftCardID']) ){
        return $this->_values['GiftCardID'];
      }elseif( isset($this->_values["CardNumber"]) ){
        return $this->_values["CardNumber"];
      }else{
        return $this->_values['Code'];
      }

      return parent::getValuesID();
    }

    public function getResultID(){
      if( isset($this->_result->GiftCard) ){
        return $this->_result->GiftCard->GiftCardID;
      }

      return parent::getResultID();
    }

  }

  ?>
