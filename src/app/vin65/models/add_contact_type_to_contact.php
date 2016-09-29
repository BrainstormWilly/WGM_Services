<?php namespace wgm\vin65\models;


  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

  use wgm\models\ServiceInputForm as ServiceInputForm;

  class AddContactTypeToContact extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/ContactService.cfc?wsdl";
    const SERVICE_NAME = "ContactService";
    const METHOD_NAME = "AddContactTypeToContact";

    function __construct($session, $version=3){

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'contactid';
      $vf['name'] = "Contact ID";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'customernumber';
      $vf['name'] = "Customer Number";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'email';
      $vf['name'] = "Email";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'contacttypeid';
      $vf['name'] = "Contact Type ID";
      array_push($this->_value_fields, $vf);

      $this->_value_map = [
        "contactid" => 'ContactID',
        "contacttypeid" => 'ContactTypeID',
        "customernumber" => "CustomerNumber",
        "email" => "Email",
      ];

      parent::__construct($session, $version);
    }

    // public function setValues($values){
    //   foreach ($values as $key => $value) {
    //     $lkey = strtolower($key);
    //
    //     if( $value!=='' ){
    //       if( array_key_exists($lkey, $this->_value_map) ){
    //
    //         if( $lkey=="expirydate" ){
    //           $value = DateConverter::toYMD($value);
    //         }
    //         $this->_values["GiftCard"][$this->_value_map[$lkey]] = $value;
    //       }
    //     }
    //   }
    // }

    public function getValuesID(){
      if( isset($this->_values["ContactTypeID"]) ){
        return $this->_values["ContactTypeID"];
      }

      return parent::getValuesID();
    }

    public function getResultID(){
      // there is no return code for this service
      return $this->_values["ContactTypeID"];

      // return parent::getResultID();
    }

  }

  ?>
