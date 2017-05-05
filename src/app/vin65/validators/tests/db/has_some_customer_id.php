<?php namespace wgm\vin65\validators\tests\db;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  use \wgm\vin65\validators\tests\AbstractValidatorTest as AbstractValidatorTest;


  class HasSomeCustomerID extends AbstractValidatorTest {

    function __construct($db){

      $this->_db = $db;
      $this->_message = "";
      $this->_description = "Makes sure record has a CustomerNumber or Email";
      // $this->runTest();
    }

    public function runTest($params = []){
      $this->_process = "Valid Customer ID";
      if( !isset($params['customer_number']) || !isset($params['customer_email']) || !isset($params['table_name']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }

      $sql = "SELECT * FROM " . $params['table_name'] . " WHERE " . $params['customer_number'] . " IS NULL AND " . $params['customer_email'] . " IS NULL";
      $q = $this->_db->query($sql);
      $nulls = 0;

      if( !$q ){
        $this->_message = "DB Error: " . $this->_db->error;
        $this->_result = self::ERROR;
      }elseif( $q->num_rows==0 ){
        $this->_message = "All records in table " . $params['table_name'] . " have either a " . $params['customer_number'] . " or a " . $params['customer_email'] . ".";
        $this->_result = self::SUCCESS;
      }else{
        if( $q->num_rows==1 ){
          $this->_message = $q->num_rows . " record in table '" . $params['table_name'] . "' has no customer id.";
        }else{
          $this->_message = $q->num_rows . " records in table '" . $params['table_name'] . "' have no customer id.";
        }
        $this->_result = self::ERROR;
      }

      // $ci = $params['customer_number_index'];
      // $ei = $params['email_index'];
      // $f = $params['file'];
      // $nulls = 0;
      // foreach ($f as $value) {
      //   if( $value[$ei] === NULL && $value[$ci] === NULL){
      //     $nulls += 1;
      //   }
      // }
      // if( $nulls === 0 ){
      //   $this->_message = count($f) . " records scanned. All have either a CustomerNumber or Email.";
      //   $this->_result = self::SUCCESS;
      // }else{
      //   $this->_message = "There were " . $nulls . " record(s) missing either a CustomerNumber or Email";
      //   $this->_result = self::ERROR;
      // }
    }

  }


?>
