<?php namespace wgm\vin65\models;


  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

  use wgm\models\ServiceInputForm as ServiceInputForm;

  class SearchContactTypes extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/ContactService.cfc?wsdl";
    const SERVICE_NAME = "ContactService";
    const METHOD_NAME = "SearchContactTypes";

    function __construct($session, $version=3){

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'contacttypeid';
      $vf['name'] = "Contact ID <small>(leave blank to search all types)</small>";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $this->_value_map = [
        "websiteids" => 'WebsiteIDs',
        "contacttypeid" => 'ContactTypeID',
        "contacttype" => 'ContactType',
        "datemodifiedfrom" => 'DateModifiedFrom',
        "datemodifiedto" => 'DateModifiedTo',
        "maxrows" => 'MaxRows',
        "page" => 'Page'
      ];

      parent::__construct($session, 3);
    }

    public function setValues($values){
      foreach ($values as $key => $value) {
        $lkey = strtolower($key);

        if( $value!=='' ){
          if( array_key_exists($lkey, $this->_value_map) ){

            if( $lkey=="datemodifiedfrom" || $lkey=="datemodifiedto" ){
              $value = DateConverter::toYMD($value);
            }
            $this->_values[$this->_value_map[$lkey]] = $value;
          }
        }
      }
    }

    public function getValuesID(){
      if( isset($this->_values["ContactTypeID"]) ){
        return $this->_values["ContactTypeID"];
      }elseif( isset($this->_values["ContactType"]) ){
        return $this->_values["ContactType"];
      }

      return parent::getValuesID();
    }

    public function getResultID(){
      $s = "";
      // print_r($this->_result["ContactTypes"]);
      // exit;
      foreach ($this->_result->ContactTypes as $value) {
        $s .= $value->ContactType . ", ";
      }
      return $s;
    }

  }

  ?>
