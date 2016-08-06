<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class CreateGiftCard extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/GiftCardService.cfc?wsdl";
    const SERVICE_NAME = "GiftCardService";
    const METHOD_NAME = "CreateGiftCard";

    function __construct($session, $version=3){
      $this->_value_map = [
        "websiteid" => 'WebsiteID',
        "title" => 'Title',
        "expirydate" => 'ExpiryDate',
        "notes" => 'Notes',
        "amount" => 'Amount'
      ];

      parent::__construct($session, $version);
    }

    public function getValuesID(){
      if( isset($this->_values["Title"]) ){
        return $this->_values["Title"];
      }

      return parent::getValuesID();
    }

    public function getResultID(){
      if( isset($this->_result->GiftCard) ){
        return $this->_result->GiftCard->Code;
      }

      return parent::getResultID();
    }

  }

  ?>
