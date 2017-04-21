<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class ValidClubName extends AbstractValidatorTest {

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure club names are not null.";

    }

    public function runTest($params = []){
      $this->_process = "Valid Club Names";
      if( !isset($params['column']) || !isset($params['file']) || !isset($params['index']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $this->_process .= " -> " . $params['column'];
      $i = $params['index'];
      $f = $params['file'];
      $clubs = [];
      $nulls = 0;
      foreach ($f as $value) {
        if( $value[$i] === NULL ){
          $nulls += 1;
          continue;
        }
        array_push($clubs, $value[$i]);
      }
      if( $nulls === 0 ){
        $this->_message = "All " . count($f) . " records have club names with the following values: " . implode(", ", array_unique($clubs)) . ".";
        $this->_result = self::SUCCESS;
      }else{
        $this->_message .= "There are " . $nulls . " NULL values.";
        $this->_result = self::ERROR;
      }
    }

  }


?>
