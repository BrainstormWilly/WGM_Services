<?php namespace wgm\vin65\validators\tests\db;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/abstract_validator_test.php';

  use \wgm\vin65\validators\tests\AbstractValidatorTest as AbstractValidatorTest;

  class ValidBirthdate extends AbstractValidatorTest {


    function __construct($db){
      parent::__construct();
      $this->_db = $db;
      $this->_message = "";
      $this->_description = "Makes sure birthdate is valid.";

    }

    public function runTest($params = []){
      $this->_process = "Valid Birthdate";
      if( !isset($params['column']) || !isset($params['table_name']) ){
        $this->_message = 'Incorrect parameters';
        $this->_result = self::ERROR;
        return;
      }
      $this->_process .= " -> " . $params['table_name'] . ":" . $params['column'];

      $t = $params['table_name'];
      $i = 1;
      $nulls = 0;

      $today = [date("m"), date("j"), date("Y")];
      $min_year = 1920;

      $bads = [];
      $olds = [];
      $news = [];

      $sql = "SELECT * FROM " . $params['table_name'];
      $q = $this->_db->query($sql);
      $nulls = 0;

      while($row = $q->fetch_array()){
        $rec = $row[$params['column']];
        if( $rec === NULL ){
          $nulls += 1;
          continue;
        }
        $bd = explode("-", $rec);
        // $this->out($rec);
        // $this->out($bd,true);
        if( count($bd) < 3 ){
          array_push($bads, $rec);
          continue;
        }
        $bd = explode("-", $rec);
        if( $bd[0] < $min_year ){
          array_push($olds, $rec);
          continue;
        }
        // $this->out( $today[2] );
        if( $today[2] - $bd[0] <= 21 ){
          $max_date = strtotime("-21 years");
          $this_date = strtotime($rec);
          if( $max_date - $this_date < 0 ){
            array_push($news, $rec);
          }
        }
      }
      // foreach ($f as $value) {
      //   if( $value[$i] === NULL ){
      //     $nulls += 1;
      //     continue;
      //   }
      //   $bd = explode("/", $value[$i]);
      //   if( count($bd) < 3 ){
      //     array_push($bads, $value[$i]);
      //     continue;
      //   }
      //   if( $bd[2] < $min_year ){
      //     array_push($olds, $value[$i]);
      //     continue;
      //   }
      //   // $this->out( $today[2] );
      //   if( $today[2] - $bd[2] <= 21 ){
      //     $max_date = strtotime("-21 years");
      //     $this_date = strtotime($value[$i]);
      //     if( $max_date - $this_date < 0 ){
      //       array_push($news, $value[$i]);
      //     }
      //   }
      // }
      if( empty($bads) && empty($news) && empty($olds) ){
        $this->_message = $this->pluralize($q->num_rows,"This") . " " . $q->num_rows . " " . $this->pluralize($q->num_rows,"record") . " " . $this->pluralize($q->num_rows,"has a") . " valid " . $this->pluralize($q->num_rows,"birthdate") . " with " . $nulls . " NULL " . $this->pluralize($q->num_rows,"value") . ".";
        $this->_result = $nulls==0 ? self::SUCCESS : self::WARNING;
      }else{
        $this->_message = '';
        if( !empty($bads) ){
          $this->_message .= "The following " . $this->pluralize($q->num_rows,"value") . " " . $this->pluralize($q->num_rows,"is not a") . " " . $this->pluralize($q->num_rows,"date") . ": " . implode(", ", $bads) . ".";
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
