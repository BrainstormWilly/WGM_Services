<?php namespace wgm\vin65\validators\tests;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  class ValidBirthdate extends AbstractValidatorTest {


    function __construct(){
      // override construct
      $this->_message = "";
      $this->_description = "Makes sure birthdate is valid.";
    }

    public function runTest($params = []){
      $this->_process = "Valid Birthdate";
      if( !isset($params['column']) || !isset($params['file']) || !isset($params['index']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $this->_process .= " -> " . $params['column'];
      $i = $params['index'];
      $f = $params['file'];

      $nulls = 0;

      $today = [date("m"), date("j"), date("Y")];
      $min_year = 1920;

      $bads = [];
      $olds = [];
      $news = [];
      foreach ($f as $value) {
        if( $value[$i] === NULL ){
          $nulls += 1;
          continue;
        }
        $bd = explode("/", $value[$i]);
        if( count($bd) < 3 ){
          array_push($bads, $value[$i]);
          continue;
        }
        if( $bd[2] < $min_year ){
          array_push($olds, $value[$i]);
          continue;
        }
        // $this->out( $today[2] );
        if( $today[2] - $bd[2] <= 21 ){
          $max_date = strtotime("-21 years");
          $this_date = strtotime($value[$i]);
          if( $max_date - $this_date < 0 ){
            array_push($news, $value[$i]);
          }
        }
      }
      if( empty($bads) && empty($news) && empty($olds) ){
        $this->_message = "All " . count($f) . " records are valid birthdates with " . $nulls . " NULL values.";
        $this->_result = $nulls==0 ? self::SUCCESS : self::WARNING;
      }else{
        $this->_message = '';
        if( !empty($bads) ){
          $this->_message .= "The following values are not dates: " . implode(", ", $bads) . ".";
        }
        if( !empty($news) ){
          if( $this->_message !== '' ){
            $this->_message .= "<br/>";
          }
          $this->_message .= "The following dates are under 21 years of age: " . implode(", ", $news) . ".";
        }
        if( !empty($olds) ){
          if( $this->_message !== '' ){
            $this->_message .= "<br/>";
          }
          $this->_message .= "The following dates are older than 1920: " . implode(", ", $olds) . ".";
        }
        $this->_result = self::ERROR;
      }
    }

  }


?>
