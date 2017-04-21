<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class ValidCCType extends AbstractValidatorTest {

    private $_types = [
        "AmericanExpress",
        "MasterCard",
        "Visa",
        "Discover",
        "JCB"
      ];

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure credit card types are valid in Vin65.";

    }

    public function runTest($params = []){
      $this->_process = "Valid CreditCard Types";
      if( !isset($params['column']) || !isset($params['file']) || !isset($params['index']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $this->_process .= " -> " . $params['column'];
      $i = $params['index'];
      $f = $params['file'];
      $bads = [];
      $nulls = 0;
      foreach ($f as $value) {
        if( $value[$i] === NULL ){
          $nulls += 1;
          continue;
        }
        if( in_array($value[$i], $this->_types) ){
          continue;
        }
        array_push($bads, $value[$i]);
      }
      if( empty($bads) && empty($nulls) ){
        $this->_message = "All " . count($f) . " records have valid credit card types with " . $nulls . " NULL values.";
        $this->_result = self::SUCCESS;
      }else{
        $this->message = '';
        if( count($bads) > 0 ){
          $this->_message .= "The following credit card types: " . implode(", ", $bads) . " are invalid. <br/>";
        }
        if( $nulls > 0 ){
          $this->_message .= "There are also " . $nulls . " NULL values.";
        }
        $this->_result = self::ERROR;
      }
    }

  }


?>
