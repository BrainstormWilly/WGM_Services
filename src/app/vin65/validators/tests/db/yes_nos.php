<?php namespace wgm\vin65\validators\tests\db;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  use \wgm\vin65\validators\tests\AbstractValidatorTest as AbstractValidatorTest;

  class YesNos extends AbstractValidatorTest {

    function __construct($db){
      parent::__construct();
      $this->_message = "";
      $this->_description = "Makes sure column has only yes/no values";
      $this->_db = $db;
    }

    public function runTest($params = []){
      $this->_process = "Yes/Nos";
      if( !isset($params['table_name']) || !isset($params['column']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }

      $this->_process .= " -> " . $params['table_name'] . ":" . $params['column'];
      $bad = 0;
      $yes = 0;
      $no = 0;

      $sql = "SELECT " . $params['column'] . " FROM " . $params['table_name'];
      $q = $this->_db->query($sql);

      while($row = $q->fetch_array()){
        $rec = $row[$params['column']];
        if( $rec === "Yes" ){
          $yes += 1;
        }elseif ($rec === "No") {
          $no +=1;
        }else{
          $bad +=1;
        }
      }

      if( $bad==0 ){
        $this->_message = $q->num_rows . " " . $this->pluralize($q->num_rows,"record") . " validated with " . $yes . " 'Yes' " . $this->pluralize($yes,"value") . " and " . $no . " 'No' " . $this->pluralize($no,"value") . ".";
        $this->_result = self::SUCCESS;
      }else{
        $this->_message = $q->num_rows . " " . $this->pluralize($q->num_rows,"record") . " scanned with " . $bad . " invalid " . $this->pluralize($bad,"value") . ".";
        $this->_result = self::ERROR;
      }
    }

  }


?>
