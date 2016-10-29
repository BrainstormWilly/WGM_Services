<?php namespace wgm\vin65\models;


  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

  use wgm\models\ServiceInputForm as ServiceInputForm;

  class SearchNotes extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/NoteService.cfc?wsdl";
    const SERVICE_NAME = "NotesService";
    const METHOD_NAME = "SearchNotes";

    function __construct($session, $version=3){

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'contactid';
      $vf['name'] = "Contact ID";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'customernumber';
      $vf['name'] = "Customer Number";
      $vf['type'] = "integer";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'email';
      $vf['name'] = "Email";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'relatedto';
      $vf['name'] = "Note is Related To:";
      $vf['type'] = 'radio';
      $vf['choices'] = [
        0 => ['id'=>'contact', 'value'=>'Contact', 'name'=>'Contact'],
        1 => ['id'=>'order', 'value'=>'Order', 'name'=>'Order']
      ];
      array_push($this->_value_fields, $vf);


      $this->_value_map = [
        "websiteid" => "WebsiteID",
        "noteid" => "NoteID",
        "datemodifiedfrom" => "DateModifiedFrom",
        "datemodifiedto" => "DateModifiedTo",
        "keycodeid" => "KeyCodeID"
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
      if( isset($this->_values["CustomerNumber"]) ){
        return $this->_values["CustomerNumber"];
      }elseif( isset($this->_values["Email"]) ){
        return $this->_values["Email"];
      }elseif( isset($this->_values["ContactID"]) ){
        return $this->_values["ContactID"];
      }

      return parent::getValuesID();
    }

    public function getResultID(){
      $s = "";
      // print_r($this->_result["ContactTypes"]);
      // exit;
      foreach ($this->_result->Notes as $value) {
        $s .= $value->NoteID . ", ";
      }
      return $s;
    }

  }

  ?>
