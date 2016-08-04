<?php namespace wgm\bloyal\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/csv.php";
  require_once $_ENV['APP_ROOT'] . "/bloyal/models/sales_transaction.php";

  use \ReflectionClass as ReflectionClass;
  use wgm\models\CSV as CSVModel;
  use wgm\bloyal\models\SalesTransaction as SalesTransactionModel;

  class SalesTransaction{

    private $_results = '';
    private $_model;

    function __construct(){
      $this->_csv_model = new CSVModel();
      $this->_model = new SalesTransactionModel();
    }

    private function _processRecord(){
      $csv_record = $this->_csv_model->getNextRecord();
      if( !$csv_record ){
        $t = "<h4>Service Complete: " . $this->_csv_model->getRecordCnt() . " records processed.</h4>";
        //print_r($this->_model->getValuesToArray());
        $this->_model->processXml();
        $file = $_ENV['UPLOADS_PATH'] . str_replace('.csv','.xml',$this->_csv_model->getFileName());
        if ( ($handle = fopen($file, "w")) !== FALSE) {
          fwrite($handle, $this->_model->getValuesToXml());
          fclose($handle);
          $t .= '<a class="btn btn-primary" href="sales_transaction_file.php?download=' . $file  . '">Download File</a>';
        }

        $this->setResultsTable($t);

      }else{
        $this->_model->addTransactionElement($csv_record);
        $this->_processRecord();
      }
    }

    public function queueRecords($file, $index=0){
      if( $this->_csv_model->readFile($file) ){
        $this->_processRecord();
      }else{
        $this->setResultsTable("<h4 style='color:red'>CSV Reader Failure: unable to read file " . $file . "</h4>");
      }
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
