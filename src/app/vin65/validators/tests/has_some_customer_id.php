<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class HasSomeCustomerID extends AbstractValidatorTest {

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure record has a CustomerNumber or Email";
      // $this->runTest();
    }

    public function runTest($params = []){
      $this->_process = "Valid Customer ID";
      if( !isset($params['customer_number_index']) || !isset($params['file']) || !isset($params['email_index']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }

      $ci = $params['customer_number_index'];
      $ei = $params['email_index'];
      $f = $params['file'];
      $nulls = 0;
      foreach ($f as $value) {
        if( $value[$ei] === NULL && $value[$ci] === NULL){
          $nulls += 1;
        }
      }
      if( $nulls === 0 ){
        $this->_message = count($f) . " records scanned. All have either a CustomerNumber or Email.";
        $this->_result = self::SUCCESS;
      }else{
        $this->_message = "There were " . $nulls . " record(s) missing either a CustomerNumber or Email";
        $this->_result = self::ERROR;
      }
    }

  }


?>
