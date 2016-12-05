<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

  use wgm\models\ServiceInputForm as ServiceInputForm;

  class SearchShippingAddress extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/v300/ContactService.cfc?wsdl";
    const SERVICE_NAME = "ContactService";
    const METHOD_NAME = "SearchShippingAddress";

    function __construct($session, $version=3){

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'contactid';
      $vf['name'] = "Contact ID";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'shippingaddressid';
      $vf['name'] = "Shipping Address ID";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'email';
      $vf['name'] = "Email";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'customernumber';
      $vf['name'] = "Customer Number";
      $vf['required'] = FALSE;
      $vf['type'] = "integer";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'datemodifiedfrom';
      $vf['name'] = "Date From";
      $vf['required'] = FALSE;
      $vf['type'] = "date";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'datemodifiedto';
      $vf['name'] = "Date To";
      $vf['required'] = FALSE;
      $vf['type'] = "date";
      array_push($this->_value_fields, $vf);

      $this->_value_map = [
        "contactid" => 'ContactID',
        "shippingaddressid" => "ShippingAddressID",
        "isprimary" => "IsPrimary",
        "datemodifiedfrom" => "DateModifiedFrom",
        "datemodifiedto" => "DateModifiedTo",
        "email" => "Email",
        "customernumber" => "CustomerNumber"
      ];

      parent::__construct($session, 3);

    }

    public function getResultID(){
      if( isset($this->_result->ShippingAddresses) && count($this->_result->ShippingAddresses) > 0 ){
        $res = [];
        foreach ($this->_result->ShippingAddresses as $value) {
          array_push($res, $value->ShippingAddressID);
        }
        return explode($res, "; ");
      }
      return parent::getResultID();
    }

    public function getValuesID(){
      if( count($this->_values) > 0) {
        if( isset($this->_values["ShippingAddressID"]) ){
          return $this->_values["ShippingAddressID"];
        }elseif( isset($this->_values["ContactID"]) ){
          return $this->_values["ContactID"];
        }
        return parent::getValuesID();
      }
      return parent::getValuesID();
    }

    public function setValues($values){
      $addr = [];
      foreach ($values as $key => $value) {
        $lkey = strtolower($key);
        if( $this->_isRealValue($value) ){
          if( array_key_exists($lkey, $this->_value_map) ){
            if( $lkey=='datemodifiedfrom' || $lkey=='datemodifiedto' ){
              $addr[$this->_value_map[$lkey]] = DateConverter::toYMD($value);
            }else{
              $addr[$this->_value_map[$lkey]] = utf8_encode($value);
            }
          }
        }
      }
      array_push($this->_values, $addr);
    }


  }

?>
