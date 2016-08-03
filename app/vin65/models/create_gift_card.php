<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class CreateGiftCard extends AbstractSoapModel{

    const FILE_NAME = 'create_gift_card';

    function __construct($session, $version=3){
      $this->_value_map = [
        "WebsiteID" => '',
        "Title" => '',
        "ExpiryDate" => '',
        "Notes" => '',
        "Amount" => 0
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
        return $this->_result->GiftCard->CardNumber;
      }

      return parent::getResultID();
    }

  }

  ?>
