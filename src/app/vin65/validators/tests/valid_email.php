<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class ValidEmail extends AbstractValidatorTest {

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure emails are well-formed";
      // $this->runTest();
    }

    public function runTest($params = []){
      $this->_process = "Valid Emails";
      if( !isset($params['index']) || !isset($params['file']) || !isset($params['column']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $this->_process .= " -> " . $params['column'];
      $i = $params['index'];
      $f = $params['file'];
      $emails = [];
      $nulls = 0;
      foreach ($f as $value) {
        if( $value[$i] === NULL ){
          $nulls += 1;
          continue;
        }
        if( !filter_var($value[$i], FILTER_VALIDATE_EMAIL) ){
          array_push($emails, $value[$i]);
        }
      }
      if( empty($emails)){
        $this->_message = count($f) . " records scanned. All are valid emails.";
        $this->_result = $nulls > 0 ? self::WARNING : self::SUCCESS;
      }else{
        $this->_message = "The following emails: " . implode(", ", $emails) . " are invalid.";
        $this->_result = self::ERROR;
      }
    }

  }


?>
