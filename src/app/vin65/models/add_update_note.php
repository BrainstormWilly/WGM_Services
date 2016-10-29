<?php namespace wgm\vin65\models;

require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

use wgm\models\ServiceInputForm as ServiceInputForm;

// use \DateTime as DateTime;
// use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class AddUpdateNote extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/NoteService.cfc?wsdl";
    const SERVICE_NAME = "NoteService";
    const METHOD_NAME = "AddUpdateNote";

    function __construct($session, $version=3){

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'noteid';
      $vf['name'] = "Note ID";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'customernumber';
      $vf['name'] = "CustomerNumber";
      $vf['type'] = "integer";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'email';
      $vf['name'] = "Email";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'ordernumber';
      $vf['name'] = "OrderNumber <small>(has to include order or customer number)</small>";
      $vf['type'] = "integer";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'notedate';
      $vf['name'] = "Note Date";
      $vf['type'] = "date";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'subject';
      $vf['name'] = "Subject";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'note';
      $vf['name'] = "Note (body)";
      $vf['type'] = 'textarea';
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

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'type';
      $vf['name'] = "Note Type:";
      $vf['type'] = 'radio';
      $vf['choices'] = [
        0 => ['id'=>'note', 'value'=>'Note', 'name'=>'Note'],
        1 => ['id'=>'flag', 'value'=>'Flag', 'name'=>'Flag']
      ];
      array_push($this->_value_fields, $vf);

      $this->_value_map = [
        "websiteid" => "WebsiteID",
        "noteid" => "NoteID",
        "type" => "Type",
        "subject" => "Subject",
        "note" => "Note",
        "notedate" => "NoteDate",
        "relatedto" => "RelatedTo",
        "keycodeid" => "KeyCodeID"
      ];

      parent::__construct($session, $version);

      $this->_values["Note"] = [];
      $this->_values["Mode"] = "Strict";
    }

    public function setValues($values){
      foreach ($values as $key => $value) {
        $lkey = strtolower($key);
        if( array_key_exists($lkey, $this->_value_map) ){
          if( $lkey=='notedate' ){
            $value = DateConverter::toYMD($value);
          }
          if( $lkey=='note' || $lkey=='subject' ){
            // $value = preg_replace("/\\[nr]/", " ", $value);
            $value = mb_convert_encoding($value, "UTF-8");
          }
          $this->_values["Note"][$this->_value_map[$lkey]] = $value;
        }
      }
    }

    public function getValuesID(){
      if( isset($this->_values["Note"]["Subject"]) ){
        return $this->_values["Note"]["Subject"];
      }
      return parent::getValuesID();
    }

    public function getResultID(){
      if( isset($this->_result->NoteID) ){
        return $this->_result->NoteID;
      }
      return parent::getResultID();
    }




  }



?>
