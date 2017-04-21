<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class ValidCCNumber extends AbstractValidatorTest {

    private $_patterns = [
        "AmericanExpress"=>"^([34|37]{2})([0-9]{13})$",
        "MasterCard"=>"^([51|52|53|54|55]{2})([0-9]{14})$",
        "Visa"=>"^([4]{1})([0-9]{12,15})$",
        "Discover"=>"^([6011]{4})([0-9]{12})$",
        "JCB"=>"(35[2-8][89]\d\d\d{10})"
      ];

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure credit card numbers are valid.";

    }

    public function runTest($params = []){
      $this->_process = "Valid CreditCard Numbers";
      if( !isset($params['column']) || !isset($params['file']) || !isset($params['cc_type_index']) || !isset($params['cc_number_index']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $this->_process .= " -> " . $params['column'];
      $ti = $params['cc_type_index'];
      $ni = $params['cc_number_index'];
      $f = $params['file'];
      $bads = [];
      $fakes = 0;
      $nulls = 0;
      foreach ($f as $value) {
        if( $value[$ni] === NULL ){
          $nulls += 1;
          continue;
        }
        if( array_key_exists($value[$ti], $this->_patterns) && preg_match("/" . $this->_patterns[$value[$ti]]."/i", $value[$ni]) ){
          continue;
        }
        if( $value[$ti]=="MasterCard" && ($value[$ni]=="'5454545454545454" || $value[$ni]=="5454545454545454") ){
          $fakes += 1;
          continue;
        }
        array_push($bads, $value[$ni]);
      }
      if( empty($bads) && empty($nulls) ){
        $this->_message = "All " . count($f) . " records have valid credit card numbers with " . $fakes . " fakes and " . $nulls . "NULL values.";
        $this->_result = self::SUCCESS;
      }else{
        $this->message = '';
        if( count($bads) > 0 ){
          $this->_message .= "The following credit card numbers are invalid: " . implode(", ", $bads) . '.<br/>';
        }
        if( $nulls > 0 ){
          $this->_message .= "There are also " . $nulls . " NULL values.";
        }
        $this->_result = self::ERROR;
      }
    }

  }


?>
