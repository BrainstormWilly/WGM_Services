<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class ValidCCExpiryMonth extends AbstractValidatorTest {

    private $_months = [];

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure credit card expiry months are valid.";
      $this->_months = range(1,12);
    }

    public function runTest($params = []){
      $this->_process = "Valid CreditCard Expiry Months";
      if( !isset($params['column']) || !isset($params['file']) || !isset($params['index']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $this->_process .= " -> " . $params['column'];
      $i = $params['index'];
      $f = $params['file'];
      $bads = [];
      foreach ($f as $value) {
        if( !in_array($value[$i], $this->_months) ){
          array_push($bads, $value[$ni]);
        }
      }
      if( empty($bads) ){
        $this->_message = "All " . count($f) . " records have valid credit card expiry months.";
        $this->_result = self::SUCCESS;
      }else{
        $this->_message = "The following values are not valid credit card expiry months: " . implode(", ", $bads) . '.';
        $this->_result = self::ERROR;
      }
    }

  }


?>
