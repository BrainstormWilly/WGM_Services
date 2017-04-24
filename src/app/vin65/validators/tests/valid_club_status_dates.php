<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class ValidClubStatusDates extends AbstractValidatorTest {

    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure record has a valid dates for club status";
      // $this->runTest();
    }

    public function runTest($params = []){
      $this->_process = "Club Status Dates";
      if( !isset($params['file']) ||
          !isset($params['signup_date_index']) ||
          !isset($params['on_hold_start_date_index']) ||
          !isset($params['on_hold_until_date_index']) ||
          !isset($params['cancel_date_index'])
        ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }

      $sudi = $params['signup_date_index'];
      $ohsdi = $params['on_hold_start_date_index'];
      $ohudi = $params['on_hold_until_date_index'];
      $cdi = $params['cancel_date_index'];
      $rec = 2;
      $f = $params['file'];
      $actives = 0;
      $scheduled_holds = 0;
      $unscheduled_holds = 0;
      $cancels = 0;
      $nulls = 0;
      foreach ($f as $v) {
        if( $v[$sudi] === NULL ){
          $nulls += 1;
          continue;
        }
        if( $v[$ohsdi] === NULL ){
          if( $v[$cdi] === NULL ){
            $actives += 1;
          }else{
            $cancels += 1;
          }
          continue;
        }
        if( $v[$ohudi] === NULL ){
          if( $v[$cdi] === NULL ){
            $unscheduled_holds += 1;
          }else{
            $cancels += 1;
          }
          continue;
        }
        $on_hold_until_date = date_create($v[$ohudi]);
        if( date_diff(date_create("today"), $on_hold_until_date)->invert == 0 ){
          $scheduled_holds += 1;
        }else{
          $actives += 1;
        }
      }
      if( $nulls === 0 ){
        $this->_message = count($f) . " records scanned. All have signup dates.";
        if( $cancels > 0 ){
          $this->_message .= "<br/>" . $cancels . " records have cancel dates and are inactive.";
        }
        if( $scheduled_holds > 0 ){
          $this->_message .= "<br/>" . $scheduled_holds . " records are currently on scheduled hold.";
        }
        if( $unscheduled_holds > 0 ){
          $this->_message .= "<br/>" . $unscheduled_holds . " records are currently on unscheduled hold.";
        }
        if( $actives > 0 ){
          $this->_message .= "<br/>" . $actives . " records are currently active.";
        }
        $this->_result = $unscheduled_holds > 0 ? self::WARNING : self::SUCCESS;
      }else{

        $this->_message = "There were " . $nulls . " record(s) with no signup date";
        $this->_result = self::ERROR;
      }
    }

  }


?>
