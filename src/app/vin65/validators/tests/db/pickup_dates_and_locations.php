<?php namespace wgm\vin65\validators\tests\db;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  use \wgm\vin65\validators\tests\AbstractValidatorTest as AbstractValidatorTest;

  class PickupDatesAndLocations extends AbstractValidatorTest {

    function __construct($db){
      parent::__construct();
      $this->_message = "";
      $this->_description = "Makes sure 'isPickup' with 'Yes' values also has location and date";
      $this->_db = $db;
    }

    public function runTest($params = []){
      $this->_process = "Yes Pickup Dates/Locations";
      if( !isset($params['table_name']) || !isset($params['ispickup']) || !isset($params['pickuplocation']) || !isset($params['pickupdate']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }

      $this->_process .= " -> " . $params['table_name'];
      $bad_yes = 0;
      $bad_no = 0;
      $locations = [];

      $sql = "SELECT " . $params['ispickup'] . "," . $params['pickuplocation'] . "," . $params['pickupdate'] . " FROM " . $params['table_name'];
      $q = $this->_db->query($sql);

      while($row = $q->fetch_array()){
        if( $row[$params["ispickup"]] == "Yes" ){
          if( $row[$params["pickuplocation"]] === NULL || $row[$params["pickupdate"]] === NULL ){
            $bad_yes += 1;
          }elseif( !in_array($row[$params["pickuplocation"]], $locations) ){
            array_push($locations, $row[$params["pickuplocation"]]);
          }
        }else{
          if( $row[$params["pickuplocation"]] !== NULL || $row[$params["pickupdate"]] !== NULL ){
            $bad_no += 1;
          }
        }
      }

      if( $bad_yes==0 && $bad_no==0){
        $this->_message = $q->num_rows . " " . $this->pluralize($q->num_rows,"record") . " scanned with all 'Yes' values verified.";
        $this->_message .= "<br/>Found the following pickup " . $this->pluralize(count($locations),"location") . ": " . implode(", ", $locations) . ".";
        $this->_result = self::SUCCESS;
      }else{
        $this->_message = $q->num_rows . " " .
          $this->pluralize($q->num_rows,"record") . " scanned with " .
          $bad_yes . " 'Yes' " . $this->pluralize($bad_yes,"value") .
          " missing either a PickupLocationCode or a PickupDate and " .
          $bad_no . " 'No' " . $this->pluralize($bad_no,"value") . " having either set.";

        $this->_result = self::ERROR;
      }
    }

  }


?>
