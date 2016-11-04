<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

  use \ReflectionClass as ReflectionClass;

  abstract class AbstractSoapModel{

    protected $_value_fields = [];
    protected $_value_map = [];
    protected $_values = [];
    protected $_result = [];
    protected $_error = '';
    protected $_services_version = 3;

    /*
    ** Pass credentials via $_SESSION to each model
    ** Vin65 has 2 service versions (2,3)
    ** Each handles credentials differently
    ** so pass $version from child models
    */
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

    /*
    ** Form field markup for single input Form
    ** Create in child model __construct
    */
    public function getValueFields(){
      return $this->_value_fields;
    }

    /*
    ** Log requires some sort of ID for each send
    ** record. Override in child model.
    */
    public function getValuesID(){
      return "Unknown";
    }

    /*
    ** Log requires some sort of ID for each return
    ** record. Override in child model.
    */
    public function getResultID(){
      return "Unknown";
    }

    /*
    ** Expose values. Rarely used.
    */
    public function getValues(){
      return $this->_values;
    }

    /*
    ** Set values by mapping keys to key maps set
    ** in __construct. Often overridden.
    */
    public function setValues($values){
      foreach ($values as $key => $value) {
        $lkey = strtolower($key);
        if( $this->_isRealValue($value) ){
          if( array_key_exists($lkey, $this->_value_map) ){
            $this->_values[$this->_value_map[$lkey]] = $value;
          }
        }
      }
    }


    public function getValueCnt(){
      // always override this
      return 0;
    }

    public function getKeys(){
      return array_keys($this->_value_map);
    }

    public function valueKeyExists($key){
      $lkey = strtolower($key);
      if( array_key_exists($lkey, $this->_value_map) ){
        return array_key_exists($key, $this->_values);
      }
      return false;
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
            $e.= $value->ErrorCode . ": " . $value->ErrorMessage . "; ";
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

    protected function _isRealValue($value, $key=NULL){
      if( $key===NULL ){
        return ( isset($value) && trim($value) !== '' && $value !== NULL );
      }
      return ( isset($value[$key]) && trim($value[$key]) !== '' && $value[$key] !== NULL );
    }


  }

?>
