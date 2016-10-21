<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class ColumnCase extends AbstractValidatorTest {

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure columns are spelled/capitalized correctly";
      // $this->runTest();
    }

    public function runTest($params = []){
      $this->_process = "Column Case";
      if( !isset($params['table']) || !isset($params['file']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $tc = $params['table'];
      $fc = $params['file'];
      $this->_message = '';
      foreach ($tc as $value) {
        if( !in_array($value, $fc) ){
          $this->_message .= "File column " . $value . " is incorrectly spelled or doesn't exist.</br>";
        }
      }
      if( empty($this->_message) ){
        $this->_message = "All " . count($tc) . " columns are spelled correctly.";
        $this->_result = self::SUCCESS;
      }else{
        $this->_result = self::WARNING;
      }
    }

  }


?>
