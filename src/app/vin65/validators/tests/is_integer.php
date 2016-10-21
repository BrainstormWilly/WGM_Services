<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class IsInteger extends AbstractValidatorTest {

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure all records in field are integers";
      // $this->runTest();
    }

    public function runTest($params = []){
      $this->_process = "Is Integer";
      if( !isset($params['index']) || !isset($params['file']) || !isset($params['column']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $c = $params['column'];
      $this->_process .= " -> " . $c;
      $i = $params['index'];
      $f = $params['file'];
      $nonints = [];
      $nulls = 0;
      foreach ($f as $value) {
        if( $value[$i] === NULL ){
          $nulls += 1;
          continue;
        }
        if( !filter_var($value[$i], FILTER_VALIDATE_INT) ){
          array_push($nonints, $value[$i]);
        }
      }
      if( empty($nonints)){
        $this->_message = count($f) . " records scanned with " . $nulls . " nulls.";
        $this->_result = $nulls > 0 ? self::WARNING : self::SUCCESS;
      }else{
        $this->_message = "The following values are not integers: " . implode(", ", $nonints) . " with " . $nulls . " nulls.";
        $this->_result = self::ERROR;
      }
    }

  }


?>
