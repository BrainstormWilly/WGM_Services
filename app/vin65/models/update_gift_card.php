<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class UpdateGiftCard extends AbstractSoapModel{

    const FILE_NAME = 'update_gift_card';

    function __construct($session, $version=3){
      $this->_value_map = [
        "WebsiteID" => '',
        "GiftCardID" => '',
        "CardNumber" => 0,
        "Code" => '',
        "Title" => '',
        "ExpiryDate" => '',
        "Notes" => '',
        "IsActive" => 1
      ];

      parent::__construct($session, $version);
      $this->_values["GiftCard"] = [];
      $this->_values["GiftCard"]["IsActive"] = 1; // required field change or no
    }

    public function setValues($values){
      foreach ($values as $key => $value) {
        if( array_key_exists($key, $this->_value_map) ){
          $this->_values["GiftCard"][$key] = $value;
        }
      }
    }

    public function getValuesID(){
      if( isset($this->_values["GiftCard"]["CardNumber"]) ){
        return $this->_values["GiftCard"]["CardNumber"];
      }elseif( isset($this->_values["GiftCard"]["Title"]) ){
        return $this->_values["GiftCard"]["Title"];
      }elseif( isset($this->_values["GiftCard"]["GiftCardID"]) ){
        return $this->_values["GiftCard"]["GiftCardID"];
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
