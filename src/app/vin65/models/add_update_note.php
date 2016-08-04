<?php namespace wgm\vin65\models;

require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class AddUpdateNote extends AbstractSoapModel{

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

    public function getNoteSubject(){
      return $this->_values['Note']['Subject'];
    }

    public function getNoteBody(){
      return $this->_values['Note']['Note'];
    }

    public function getKeyCodeID(){
      return $this->_values['Note']['KeyCodeID'];
    }

    public function getNoteID(){
      return $this->_values['Note']['NoteID'];
    }

    // public function callService($values=NULL){
    //   parent::callService($values);
    //   $client_params = [
    //     'trace' => 1,
    //     'exceptions' => 1,
    //     'connection_timeout' => 30
    //   ];
    //   try{
    //     $client = new \SoapClient($_ENV['V65_NOTE_SERVICE'], $client_params);
    //     $result = $client->AddUpdateNote($this->_values);
    //     if( is_soap_fault($result) ){
    //       $this->_error = "SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})";
    //       return false;
    //     }else{
    //       $this->_result = $result;
    //       return true;
    //     }
    //   }catch(Exception $e){
    //     return false;
    //   }
    //
    // }


  }



?>
