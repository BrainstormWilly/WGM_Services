<?php namespace wgm\vin65\models;

require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class AddUpdateNote extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/NoteService.cfc?wsdl";
    const SERVICE_NAME = "NoteService";
    const METHOD_NAME = "AddUpdateNote";

    function __construct($session, $version=3){
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
        if( array_key_exists(strtolower($key), $this->_value_map) ){
          $this->_values["Note"][$this->_value_map[strtolower($key)]] = $value;
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
