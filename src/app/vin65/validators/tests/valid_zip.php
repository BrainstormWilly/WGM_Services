<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class ValidZip extends AbstractValidatorTest {

    private $_patterns = array(
      	"US"=>"^\d{5}([\-]?\d{4})?$",
      	"UK"=>"^(GIR|[A-Z]\d[A-Z\d]??|[A-Z]{2}\d[A-Z\d]??)[ ]??(\d[A-Z]{2})$",
      	"DE"=>"\b((?:0[1-46-9]\d{3})|(?:[1-357-9]\d{4})|(?:[4][0-24-9]\d{3})|(?:[6][013-9]\d{3}))\b",
      	"CA"=>"^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])\ {0,1}(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$",
      	"FR"=>"^(F-)?((2[A|B])|[0-9]{2})[0-9]{3}$",
      	"IT"=>"^(V-|I-)?[0-9]{5}$",
      	"AU"=>"^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$",
      	"NL"=>"^[1-9][0-9]{3}\s?([a-zA-Z]{2})?$",
      	"ES"=>"^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$",
      	"DK"=>"^([D-d][K-k])?( |-)?[1-9]{1}[0-9]{3}$",
      	"SE"=>"^(s-|S-){0,1}[0-9]{3}\s?[0-9]{2}$",
      	"BE"=>"^[1-9]{1}[0-9]{3}$");

    function __construct(){
      // override construct

      $this->_message = "";
      $this->_description = "Makes sure zipcodes are valid in US and Canada";
      // $this->runTest();
    }

    public function runTest($params = []){
      $this->_process = "Valid ZipCodes";
      if( !isset($params['index']) || !isset($params['file']) || !isset($params['column']) ){
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
        if( preg_match("/" . $this->_patterns["US"]."/i", $value[$i]) || preg_match("/" . $this->_patterns["CA"]."/i", $value[$i]) ){
          continue;
        }
        array_push($bads, $value[$i]);
      }
      if( empty($bads)){
        $this->_message = count($f) . " records scanned. " . (count($f) - $nulls) . " are valid zipcodes in the US or Canada with " . $nulls . " nulls.";
        $this->_result = $nulls > 0 ? self::WARNING : self::SUCCESS;
      }else{
        $this->_message = "The following zipcodes: " . implode(", ", $bads) . " are invalid in the US or Canada with " . $nulls . " nulls.";
        $this->_result = self::WARNING;
      }
    }

  }


?>
