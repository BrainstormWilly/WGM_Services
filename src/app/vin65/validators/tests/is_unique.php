<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class IsUnique extends AbstractValidatorTest {


    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure column has no dupes";
      // $this->runTest();
    }

    public function runTest($params = []){
      $this->_process = "Is Unique";
      if( !isset($params['index']) || !isset($params['file']) || !isset($params['column'])){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $this->_process .= " -> " . $params['column'];
      $i = $params['index'];
      $f = $params['file'];
      $vals = [];
      $dups = [];
      $nulls = 0;
      foreach ($f as $value) {
        if( $value[$i] === NULL ){
          $nulls += 1;
          continue;
        }
        if( in_array($value[$i], $vals) && !in_array($value[$i], $dups) ){
          array_push($dups, $value[$i] );
        }else{
          array_push($vals, $value[$i]);
        }
      }
      if( empty($dups) ){
        $this->_message = count($f) . " unique records scanned with " . $nulls . " nulls.";
        $this->_result = $nulls > 0 ? self::WARNING : self::SUCCESS;
      }else{
        $this->_message = "The following values: " . implode(", ", $dups) . "are not unique for field " . $c . ".";
        $this->_result = self::ERROR;
      }
    }

  }


?>
