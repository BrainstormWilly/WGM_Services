<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/csv.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/service_logger.php";

  use \ReflectionClass as ReflectionClass;
  use wgm\models\CSV as CSVModel;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\ServiceLogger as ServiceLogger;

  abstract class AbstractSoapController{

    protected $_csv_model;
    protected $_session;
    protected $_logger;
    protected $_results = 'processing...';
    //protected $_proxys = [];

    function __construct($session){
      $this->_session = $session;
      $this->_logger = new ServiceLogger();
      $this->_csv_model = new CSVModel();
    }

    public function queueRecords($file, $index=0){
      $this->_csv_model->resetRecordIndex($index);
      // override
    }

    public function getInputForm(){
      return "<strong>No Form Available</strong>";
    }

    public function getCsvForm(){
      return '<div class="form-group">
        <label for="csv_file">Upload CSV file</label>
        <input type="file" id="csv_file" name="csv_file">
        <input type="hidden" id="input_type" name="input_type" value="file">
      </div>
      <button type="submit" class="btn btn-primary">Load File</button>';
    }

    public function setResultsTable($text){
      $this->_results = $text;
    }
    public function getResultsTable(){
      return $this->_results;
    }

    public function getClassName(){
      $class_ns = get_class($this);
      $class_bits = explode("\\", $class_ns);
      return array_pop($class_bits);
    }

    public function getClassFileName(){
      // override
      $class = new ReflectionClass($this);
      $path = $class->getFileName();
      $path_bits = explode("/", $path);
      $file = array_pop($path_bits);
      $file_bits = explode(".", $file);
      return $file_bits[0];

    }


  }

?>
