<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class YesNos extends AbstractValidatorTest {

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure column has only yes/no values";
      // $this->runTest();
    }

    public function runTest($params = []){
      $this->_process = "Yes/Nos";
      if( !isset($params['index']) || !isset($params['file']) || !isset($params['column']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $c = $params['column'];
      $this->_process .= " -> " . $c;
      $i = $params['index'];
      $f = $params['file'];
      $cnt = 0;
      $yes = 0;
      $no = 0;
      foreach ($f as $value) {
        if( $value[$i] === "Yes" ){
          $yes += 1;
        }elseif ($value[$i] === "No") {
          $no +=1;
        }else{
          $cnt +=1;
        }
      }
      if( $cnt==0 ){
        $this->_message = count($f) . " records validated with " . $yes . " 'Yes' values and " . $no . " 'No' values.";
        $this->_result = self::SUCCESS;
      }else{
        $this->_message = count($f) . " records scanned with " . $cnt . " invalid values.";
        $this->_result = self::ERROR;
      }
    }

  }


?>
