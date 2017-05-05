<?php namespace wgm\vin65\validators\tests\db;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  use \wgm\vin65\validators\tests\AbstractValidatorTest as AbstractValidatorTest;

  class ValidEmail extends AbstractValidatorTest {

    function __construct($db){
      parent::__construct();
      $this->_message = "";
      $this->_description = "Makes sure emails are well-formed";
      $this->_db = $db;
    }

    public function runTest($params = []){
      $this->_process = "Valid Emails";
      if( !isset($params['table_name']) || !isset($params['column']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $this->_process .= " -> " . $params['table_name'] . ":" . $params['column'];
      $emails = [];
      $nulls = 0;

      $sql = "SELECT " . $params['column'] . " FROM " . $params['table_name'];
      $q = $this->_db->query($sql);

      while($row = $q->fetch_array()){
        $rec = $row[$params['column']];
        if( $rec === NULL ){
          $nulls += 1;
          continue;
        }
        if( !filter_var($rec, FILTER_VALIDATE_EMAIL) ){
          array_push($emails, $rec);
        }
      }

      if( empty($emails)){
        $this->_message = $q->num_rows . " " . $this->pluralize($q->num_rows,"record") . " scanned with " . $nulls . " NULL " . $this->pluralize($q->num_rows,"value") . ".";
        $this->_result = $nulls > 0 ? self::WARNING : self::SUCCESS;
      }else{
        $this->_message = "The following " . $this->pluralize(count($emails),"email") . ": " . implode(", ", $emails) . " " . $this->pluralize(count($emails),"is") . " invalid.";
        $this->_result = self::ERROR;
      }
    }

  }


?>
