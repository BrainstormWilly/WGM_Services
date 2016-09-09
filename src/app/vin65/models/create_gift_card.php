<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

  use wgm\models\ServiceInputForm as ServiceInputForm;

  class CreateGiftCard extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/GiftCardService.cfc?wsdl";
    const SERVICE_NAME = "GiftCardService";
    const METHOD_NAME = "CreateGiftCard";

    function __construct($session, $version=3){

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'title';
      $vf['name'] = "Title";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'notes';
      $vf['name'] = "Notes";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'amount';
      $vf['name'] = "Amount";
      $vf['type'] = "currency";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'expirydate';
      $vf['name'] = "Expiration Date <small>leave blank if none</small>";
      $vf['type'] = "date";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $this->_value_map = [
        "websiteid" => 'WebsiteID',
        "title" => 'Title',
        "expirydate" => 'ExpiryDate',
        "notes" => 'Notes',
        "amount" => 'Amount'
      ];

      parent::__construct($session, $version);
    }

    public function setValues($values){
      foreach ($values as $key => $value) {
        $lkey = strtolower($key);
        if(!empty($value)){
          if( array_key_exists($lkey, $this->_value_map) ){
            if( $lkey=="expirydate" ){
              $value = DateConverter::toYMD($value);
            }
            $this->_values[$this->_value_map[$lkey]] = $value;
          }
        }
      }
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
