<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class NoNulls extends AbstractValidatorTest {

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure column has no nulls or empty's";
      // $this->runTest();
    }

    public function runTest($params = []){
      $this->_process = "No Nulls";
      if( !isset($params['index']) || !isset($params['file']) || !isset($params['column']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $c = $params['column'];
      $this->_process .= " -> " . $c;
      $i = $params['index'];
      $f = $params['file'];
      $cnt = 0;
      foreach ($f as $value) {
        if( $value[$i] === NULL ){
          $cnt += 1;
        }
      }
      if( $cnt==0 ){
        $this->_message = count($f) . " records scanned with 0 nulls.";
        $this->_result = self::SUCCESS;
      }else{
        $this->_message = count($f) . " records scanned with " . $cnt . " nulls.";
        $this->_result = self::ERROR;
      }
    }

  }


?>
