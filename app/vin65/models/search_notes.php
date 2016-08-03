<?php namespace wgm\vin65\models;

  class SearchNotes{

    private $_values = [
      "Security" => [
        "Username" => '',
        "Password" => ''
      ],
      "WebsitedIDs" => '',
      "NoteID" => '',
      "RelatedTo" => 'Contact',
      "KeyCodeID" => '',
      "DateModifiedFrom" => '1972-01-01T00:00:00',
      "DateModifiedTo" => '',
      "MaxRows" => 100,
      "Page" => 1
    ];

    //d9bb4fcb-ba22-45e6-81af-614c9365c1d2

    private $_results = [];

    function __construct($customer){
      $this->_values['DateModifiedTo'] = date("Y-m-d\Th:i:s");
      $this->_values['Security']['Username'] = $customer['username'];
      $this->_values['Security']['Password'] = $customer['password'];
    }

    public function setValues($values){
      foreach ($values as $key => $value) {
        if( array_key_exists($key, $this->_values) ){
          $this->values[$key] = $value;
        }
      }
    }

    public function callService($values=NULL){
      if( isset($values) ){
        $this->setValues($values);
      }
      $client = new \SoapClient($_ENV['V65_NOTE_SERVICE']);
      $this->_results = $client->SearchNotes($this->_values);

    }

    public function getResults(){
      return $this->_results;
    }

    public function getValues(){
      return $this->_values;
    }



  }

?>
