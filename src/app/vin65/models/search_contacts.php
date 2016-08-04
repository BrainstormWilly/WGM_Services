<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';

  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;


  class SearchContacts extends AbstractSoapModel{

    function __construct($session){
      $this->_values = [
        "Security" => [
          "Username" => $session['username'],
          "Password" => $session['password']
        ],
        "CustomerNumber" => '',
        "FirstName" => '',
        "LastName" => '',
        "Company" => '',
        "City" => '',
        "StateCode" => '',
        "Phone" => '',
        "Email" => '',
        "EmailStatus" => '',
        "ContactType" => '',
        "DateModifiedFrom" => '1972-01-01T00:00:00',
        "DateModifiedTo" => date("Y-m-d\Th:i:s"),
        "SortBy" => 'LastName',
        "MaxRows" => 100,
        "Page" => 1
      ];
    }

    public function callService($values=NULL){
      parent::callService($values);
      try{
        $client = new \SoapClient($_ENV['V65_CONTACT_SERVICE']);
        $result = $client->SearchContacts($this->_values);
        if( is_soap_fault($result) ){
          $this->_error = "SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})";
        }elseif($result->IsSuccessful){
          $this->_result = $result ;
        }else{
          $this->_error = "Unknown Error.";
        }
      }catch(Exception $e){
        $this->_error = "SOAP Exception: " . $e->message;
      }
    }


  }

 ?>
