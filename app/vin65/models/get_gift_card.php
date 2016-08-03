<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class GetGiftCard extends AbstractSoapModel{

    const FILE_NAME = 'update_gift_card';

    function __construct($session, $version=3){
      $this->_value_map = [
        "WebsiteID" => '',
        "GiftCardID" => '',
        "CardNumber" => 0,
        "Code" => ''
      ];

      parent::__construct($session, $version);
    }

    public function getValuesID(){
      if( isset($this->_values["CardNumber"]) ){
        return $this->_values["CardNumber"];
      }elseif( isset($this->_values['Code']) ){
        return $this->_values['Code'];
      }elseif( isset($this->_values['GiftCardID']) ){
        return $this->_values['GiftCardID'];
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
