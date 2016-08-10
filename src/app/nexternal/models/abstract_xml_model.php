<?php namespace wgm\nexternal\models;

  require_once $_ENV['APP_ROOT'] . '/models/csv.php';

  use wgm\models\CSV as CSV;

  abstract class AbstractXmlModel{

    protected $_input = "";
    protected $_output = "";
    protected $_v65_map = [];
    protected $_url;
    protected $_csv;

    function __construct($session, $page){
      $this->_csv = new CSV();
      // set url, input, v65 map
    }

    protected function _dateSplitter($date){
      $bits = explode("/", $date);
      $ary = [
        'mo' => $bits[0],
        'yr' => $bits[1]
      ];
      return $ary;
    }

    public function processService(){
      $ch = curl_init($this->_url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_input);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
      $data = curl_exec($ch);
      curl_close($ch);
      //convert the XML result into array
      $this->_output = simplexml_load_string($data);

    }

    public function writeOutputToCsv($file, $data, $page=1){
      foreach ($data as $value) {
        $this->_csv->addRecord($value);
      }
      return $this->_csv->writeFile($file, $page==1);
    }

    public function getOutputToXml(){
      return $this->_output;
    }

    public function getOutputToJson(){
      return json_encode( $this->_output );
    }

    public function getOutputToArray(){
      return json_decode( $this->getOutputToJson(), true );
    }

    public function getOutputToV65Array(){
      // override
      return getOutputToArray();
    }

    public function convertOutputToCsv($data){
      $keys = array_keys($data[0]);
      $csv;
      $csvs = [];
      array_push($csvs, $keys);
      foreach($data as $rec){
        $csv = [];
        foreach($rec as $k => $v){
          $i = array_search($k, $keys);
          $csv[$i] = $v;
        }
        array_push($csvs, $csv);

      }
      return $csvs;
    }

    public function hasNextPage(){
      return array_key_exists("NextPage", $this->_output);
    }

  }

  ?>
