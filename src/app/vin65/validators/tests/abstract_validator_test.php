<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/result_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/pluralize.php';

  use \wgm\vin65\validators\ResultModel as ResultModel;
  use \wgm\vin65\validators\Pluralize as Pluralize;


  abstract class AbstractValidatorTest{

    const
      SUCCESS = 1,
      ERROR = 0,
      WARNING = 2;

    protected $_process;
    protected $_message;
    protected $_description;
    protected $_result = 1;
    protected $_db;
    protected $_pluralizer;

    function __construct(){

      $this->_process = "Invalid Test";
      $this->_message = "Abstract test not overriden";
      $this->_description = "Invalid Test description";
      $this->_pluralizer = new Pluralize();
      // override construct
    }

    public function getClassName(){
      $class_ns = get_class($this);
      $class_bits = explode("\\", $class_ns);
      return array_pop($class_bits);
    }

    public function getDescription(){
      return $this->_description;
    }

    public function getProcess(){
      return $this->_process;
    }

    public function getMessage(){
      return $this->_message;
    }

    public function getPreamble(){
      return "<h5>" . $this->_process . "</h5><p>" . $this->_description . "</p>";
    }

    public function getResult(){
      if( $this->_result === self::WARNING ){
        return ResultModel::CreateWarning($this->_process, $this->_message);
      }elseif ( $this->_result === self::ERROR ) {
        return ResultModel::CreateError($this->_process, $this->_message);
      }
      return ResultModel::CreateSuccess($this->_process, $this->_message);
    }

    public function runTest($params = []){
      $this->_process = self::$PROCESS;
      // override;
    }

    public function pluralize($cnt, $word){
      return $this->_pluralizer->replace($cnt, $word);
    }

    public function success(){
      return $this->_result === self::SUCCESS;
    }

    public function out($output, $exit=FALSE){
      print_r("[[" . $this->getClassName() . "]] ");
      print_r($output);
      print_r("<br/>");
      if( $exit ){
        exit;
      }

    }

  }


?>
