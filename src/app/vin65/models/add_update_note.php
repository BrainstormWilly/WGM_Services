<?php namespace wgm\vin65\models;

require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class AddUpdateNote extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/NoteService.cfc?wsdl";
    const SERVICE_NAME = "NoteService";
    const METHOD_NAME = "AddUpdateNote";

    function __construct($session, $version=3){
      $this->_value_map = [
        "Security" => [
          "Username" => '',
          "Password" => ''
        ],
        "Note" => [
          "WebsiteID" => "",
          "NoteID" => "",
          "Type" => "",
          "Subject" => "",
          "Note" => "",
          "NoteDate" => "",
          "RelatedTo" => "",
          "KeyCodeID" => ""
        ],
        "Mode" => "Strict"
      ];

      parent::__construct($session, $version);

      $this->_values["Note"] = [];
      $this->_values["Mode"] = "Strict";
    }

    public function setValues($values){
      foreach ($values as $key => $value) {
        if( array_key_exists($key, $this->_value_map["Note"]) ){
          $this->_values["Note"][$key] = $value;
        }
      }
    }

    public function setResult($result){
      if( $result->isSuccessful ){
        if( count($result->contacts) > 0 ){
          $this->_result = $result;
        }else{
          $this->_error = "No contacts found";
        }
      }else{
        $err = "";
        foreach($result->Errors as $value){
          $err .= $value["ErrorCode"] . ": " . $value["ErrorMessage"] . "; ";
        }
        $this->_error = $err;
      }
    }


  }



?>
