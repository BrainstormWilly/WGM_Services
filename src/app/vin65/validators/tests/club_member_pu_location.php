<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class ClubMemberPULocation extends AbstractValidatorTest {

    private $_locations = [];

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure if club member is p/u there is a location";
      // $this->runTest();
    }

    public function runTest($params = []){
      $this->_process = "Valid Pickup Location";
      if( !isset($params['pu_at_winery_index']) || !isset($params['file']) || !isset($params['pu_location_index']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }

      $wi = $params['pu_at_winery_index'];
      $li = $params['pu_location_index'];
      $f = $params['file'];
      $nulls = 0;
      foreach ($f as $value) {
        if( $value[$wi] === "Yes" ){
          if( $value[$li] === NULL ){
            $nulls += 1;
            continue;
          }
          array_push($this->_locations, $value[$li]);
        }
      }
      if( $nulls === 0 ){
        $this->_message = count($f) . " records scanned. All pickups have the following location(s): " . implode(",", $this->_locations);
        $this->_result = self::SUCCESS;
      }else{
        $this->_message = "There were " . $nulls . " pickups missing a location.";
        $this->_result = self::ERROR;
      }
    }

  }


?>
