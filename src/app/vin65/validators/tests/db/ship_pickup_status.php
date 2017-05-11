<?php namespace wgm\vin65\validators\tests\db;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  use \wgm\vin65\validators\tests\AbstractValidatorTest as AbstractValidatorTest;

  class ShipPickupStatus extends AbstractValidatorTest {

    function __construct($db){
      parent::__construct();
      $this->_message = "";
      $this->_description = "Makes sure ShippingStatus is set to 'Shipped' or 'PickedUp'";
      $this->_db = $db;
    }

    public function runTest($params = []){
      $this->_process = "Shipped/PickedUp";
      if( !isset($params['table_name']) || !isset($params['column']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }

      $this->_process .= " -> " . $params['table_name'] . ":" . $params['column'];
      $bad = 0;
      $ship = 0;
      $pu = 0;

      $sql = "SELECT " . $params['column'] . " FROM " . $params['table_name'];
      $q = $this->_db->query($sql);

      while($row = $q->fetch_array()){
        if( $row[$params["column"]] == "Shipped" ){
          $ship += 1;
        }elseif ($row[$params["column"]] == "PickedUp") {
          $pu += 1;
        }else{
          $bad += 1;
        }
      }

      if( $bad==0 ){
        $this->_message = $q->num_rows . " " .
          $this->pluralize($q->num_rows,"record") . " scanned with " .
          $ship . " 'Shipped' " . $this->pluralize($ship,"value") . " and " .
          $pu . " 'PickedUp' " . $this->pluralize($pu,"value");
        $this->_result = self::SUCCESS;
      }else{
        $this->_message = $bad . " " .
          $this->pluralize($q->num_rows,"record") . " " .
          $this->pluralize($q->num_rows,"is not") . " set to 'Shipped' or 'PickedUp'.";
        $this->_result = self::ERROR;
      }
    }

  }


?>
