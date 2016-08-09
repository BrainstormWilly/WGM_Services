<?php namespace wgm\vin65\models;

  use \ReflectionClass as ReflectionClass;

  abstract class AbstractSoapModel{

    protected $_value_fields = [];
    protected $_value_map = [];
    protected $_values = [];
    protected $_result = [];
    protected $_error = '';
    protected $_services_version = 3;


    function __construct($session, $version=3){
      $this->_services_version = $version;
      if($this->_services_version==3){
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

    public function getValueFields(){
      return $this->_value_fields;
    }

    public function getValuesID(){
      return "Unknown";
    }

    public function getResultID(){
      return "Unknown";
    }

    public function getValues(){
      return $this->_values;
    }
    public function setValues($values){
      foreach ($values as $key => $value) {
        if(!empty($value)){
          if( array_key_exists(strtolower($key), $this->_value_map) ){
            $this->_values[$this->_value_map[strtolower($key)]] = $value;
          }
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
      if( $this->_services_version==3 ){
        if($result->IsSuccessful){
          $this->_result = $result;
        }else{
          $e = "";
          foreach($result->Errors as $value){
            $e.= $value["ErrorCode"] . ": " . $value["ErrorMessage"] . "; ";
          }
          $this->setError($e);
        }
        $this->_result = $result;
      }else{
        if( $result==NULL ){
          $this->_error = "Unknown error.";
        }elseif( isset($result->results) && count($result->results) > 0 ){
          if( $result->results[0]->isSuccessful ){
            $this->_result = $result->results[0];
          }else{
            $this->_error = $result->results[0]->message;
          }
        }elseif( isset($result->isSuccessful) && $result->isSuccessful){
          $this->_result = $result;
        }else{

          $this->_error = "Unknown error.";
        }
      }
    }

    public function success(){
      return empty($this->_error);
    }

    public function getClassName(){
      $class_ns = get_class($this);
      $class_bits = explode("\\", $class_ns);
      return array_pop($class_bits);
    }

    public function getClassFileName(){
      $class = new ReflectionClass($this);
      $path = $class->getFileName();
      $path_bits = explode("/", $path);
      $file = array_pop($path_bits);
      $file_bits = explode(".", $file);
      return $file_bits[0];
    }

    // public function callService($values=NULL){
    //   $this->_result = [];
    //   $this->_error = '';
    //   if( $values ){
    //     $this->setValues($values);
    //   }
    //   // extend from here
    // }

  }

?>
