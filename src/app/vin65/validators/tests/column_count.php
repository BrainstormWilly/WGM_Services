<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class ColumnCount extends AbstractValidatorTest {

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure required number of columns are present.";
      // $this->runTest();
    }

    public function runTest($params = []){
      $this->_process = "Column Count";
      if( $params['table'] != $params['file'] ){
        $this->_message = 'Table requires ' . $params['table'] . ' columns, while file has ' . $params['file'];
        $this->_result = AbstractValidatorTest::WARNING;
      }else{
        $this->_message = 'Table has ' . $params['table'] . ' total columns.';
        $this->_result = AbstractValidatorTest::SUCCESS;
      }
    }

  }


?>
