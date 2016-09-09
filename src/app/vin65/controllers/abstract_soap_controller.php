<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/csv.php";
  require_once $_ENV['APP_ROOT'] . "/models/service_input.php";
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/service_logger.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use \ReflectionClass as ReflectionClass;
  use wgm\models\CSV as CSVModel;
  use wgm\models\ServiceInput as ServiceInputModel;
  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\ServiceLogger as ServiceLogger;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;
  use wgm\vin65\models\SoapServiceModel as SoapServiceModel;



  abstract class AbstractSoapController{

    const
      CONTACT_SERVICE_V201 = "https://webservices.vin65.com/v201/contactService.cfc?wsdl",
      CONTACT_SERVICE_V300 = "https://webservices.vin65.com/V300/ContactService.cfc?wsdl",
      NOTE_SERVICE_V300 = "https://webservices.vin65.com/V300/NoteService.cfc?wsdl";

    protected $_input_form;
    protected $_csv_model;
    protected $_session;
    protected $_logger;
    protected $_results = '';
    protected $_queue;
    //protected $_proxys = [];

    function __construct($session){
      $this->_session = $session;

      $this->_queue = new SoapServiceQueue($session, [$this, "onSoapServiceQueueStatus"]);
      // $this->_logger = new ServiceLogger();
      // $this->_csv_model = new CSVModel();
    }

    public function setData($page_limit=25, $display_limit=50, $set_limit=1){
      // override for specific data model
    }

    public function queueRecords($file, $index=0, $cnt=0){
      $this->_queue->init($file, $index, $cnt);
    }

    public function inputRecord($record){
      // create consumable service model for queue

      $input = new ServiceInputModel();
      $input->addRecord($record);

      $this->_queue->setData($input);
      $this->_queue->init($_ENV['UPLOADS_PATH'] . $this->getClassFileName() . "_input.csv");
    }

    public function getInputForm(){
      if( isset($this->_input_form) ){
        return $this->_input_form->getFormHtml();
      }
      return "";
    }

    public function getCsvForm($has_sets=false){
      $t = '<div class="form-group">
                <label for="csv_file">Upload CSV file</label>
                <input type="file" id="csv_file" name="csv_file">
              </div>
              <div class="row">
                <div class="col-md-2 form-group">
                  <label for="page_limit">Page Limit</label>
                  <input class="form-control" type="number" id="page_limit" name="page_limit" value="25">
                </div>';
      if( $has_sets ){
        $t .= '<div class="col-md-2 form-group">
                <label for="set_limit">Set Limit (15 Max.)</label>
                <input class="form-control" type="number" id="set_limit" name="set_limit" value="1">
              </div>';
      }
      $t .=   '</div>
              <button type="submit" class="btn btn-primary">Load File</button>';
      return $t;
    }

    public function setResultsTable($text){
      $this->_results = $text;
    }
    public function getResultsTable(){
      return $this->_results;
    }
    public function getFullResultsTable(){
      // override for displaying data vs. just results
      $model = $this->_queue->getCurrentServiceModel();

      if( !empty($model) ){
        if( $model->success() ){
          return print_r($this->_results);
        }else{
          return "<div><h4 style='color:red'>" . $model->getError() . "</h4><div>";
        }
      }
    }

    public function getClassName(){
      $class_ns = get_class($this);
      $class_bits = explode("\\", $class_ns);
      return array_pop($class_bits);
    }

    public function getClassFileName(){
      $class = new ReflectionClass($this);
      $path = $class->getFileName();
      $path_bits = explode("/", $path);
      $file = array_pop($path_bits);
      $file_bits = explode(".", $file);
      return $file_bits[0];
    }

    public function hasInputForm(){
      return isset( $this->_input_form );
    }


    // CALLBACKS

    public function onSoapServiceQueueStatus($status){
      if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
        $this->_queue->processNextRecord();
      }elseif( $status==SoapServiceQueue::QUEUE_COMPLETE ){
        $this->setResultsTable($this->_queue->getLog());
        $this->_queue->processNextPage( $this->getClassFileName() );
      }elseif( $status==SoapServiceQueue::FAIL ){
        $this->setResultsTable($this->_queue->getLog());
      }
    }


  }

?>
