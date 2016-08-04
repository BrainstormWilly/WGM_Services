<?php namespace wgm\vin65\models;

  abstract class AbstractSoapModel{

    protected $_value_map = [];
    protected $_values = [];
    protected $_result = [];
    protected $_error = '';

    function __construct($session, $version=3){
      if($version==3){
        $this->_values = [
          "Security" => [
            "Username" => $session['username'],
            "Password" => $session['password']
          ]
        ];
      }else{
        $this->_values = [
          "username" => $session["username"],
          'password' => $session['password']
        ];
      }
    }

    public function getValuesID(){
      // override where possible
      return "Unknown";
    }

    public function getResultID(){
      // override where possible
      return "Unknown";
    }

    public function getValues(){
      return $this->_values;
    }
    public function setValues($values){
      foreach ($values as $key => $value) {
        if( array_key_exists($key, $this->_value_map) ){
          $this->_values[$key] = $value;
        }
      }
    }

    public function getKeys(){
      return array_keys($this->_value_map);
    }

    public function getError(){
      return $this->_error;
    }
    public function setError($err){
      $this->_error = $err;
    }

    public function getResult(){
      return $this->_result;
    }
    public function setResult($result){
      $this->_result = $result;
    }

    public function success(){
      return empty($this->_error);
    }

    public function callService($values=NULL){
      $this->_result = [];
      $this->_error = '';
      if( $values ){
        $this->setValues($values);
      }
      // extend from here
    }

  }

?>
