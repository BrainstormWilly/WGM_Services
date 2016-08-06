<?php namespace wgm\vin65\models;

require_once $_ENV['APP_ROOT'] . "/models/csv.php";

use wgm\models\CSV as CSVModel;
use React\EventLoop\Factory as EventLoopFactory;
use Clue\React\Soap\Factory as SoapFactory;
use Clue\React\Soap\Proxy;
use Clue\React\Soap\Client;

class SoapServiceModel{

  private $_model_class;
  private $_proxy;
  private $_callback;

  function __construct($class, $callback){
    $this->_model_class = $class;
    $this->_callback = $callback;
  }

  public function getName(){
    $class = $this->_model_class;
    return $class::SERVICE_NAME . '->' . $class::METHOD_NAME;
  }

  public function process($session, $values, $callback){

    $model = new $this->_model_class($session);
    $model->setValues($values);
    $class = $this->_model_class;
    $method = $class::METHOD_NAME;

    $this->_proxy->$method($model->getValues())->then(
      function($result) use ($model, $callback){

        $model->setResult($result);
        call_user_func_array($callback, [$model]);
      },
      function($excp) use ($model, $callback){
        $model->setError($excp->getMessage());
        call_user_func_array($callback, [$model]);
      }
    );
  }

  public function setProxy($soap, $callback){
    $class = $this->_model_class;
    $soap->createClient($class::SERVICE_WSDL)->then(
      function($client) use ($callback){
        $this->_proxy = new Proxy($client);
        call_user_func_array($callback, [true]);
      },
      function($excp) use ($callback){
        call_user_func_array($callback, [false]);
      }
    );
  }
  public function getProxy(){
    return $this->_proxy;
  }


}

class SoapServiceQueue{

  const
    INIT = 0,
    INCOMPLETE = 1,
    FAIL = 2,
    PROCESS_COMPLETE = 3,
    QUEUE_COMPLETE = 4;

  private $_current_service_model;
  private $_services = [];
  private $_session;
  private $_process_service_index = 0;
  private $_logger;
  private $_status = 0;
  private $_status_callback;
  private $_csv;

  function __construct($session, $callback, $page_limit=25, $max_display=50){
    $this->_session = $session;
    $this->_logger = new ServiceLogger();
    $this->_csv = new CSVModel($page_limit, $max_display);
    $this->_status_callback = $callback;
  }

  public function init($file, $index=0){
    $this->_csv->resetRecordIndex($index);
    if( $this->_csv->readFile($file) ){
        $this->_logger->openLog($this->_csv->getFile(), $index);
        $this->setProxies();
    }else{
      $this->_logger->writeToLog( ServiceLogger::createFailItem(0, '0000' , 'CSV Reader', 'Unable to read file.'));
      $this->setStatus(self::FAIL);
    }
  }

  public function appendService($service){
    array_push( $this->_services, new SoapServiceModel($service, [$this, 'onProcessServiceComplete']) );
  }

  public function getCurrentService(){
    return $this->_services[$this->_process_service_index-1];
  }

  public function getCurrentServiceModel(){
    return $this->_current_service_model;
  }

  public function getCurrentCsvRecord(){
    return $this->_csv->getCurrentRecord();
  }

  public function getLog($type='html'){
    if( $this->_csv->hasNextPage() ){
      $s = "<h4>Service In-Process: " . $this->_csv->getRecordIndex() . " of " . $this->_csv->getRecordCnt() . " records processed.</h4>";
    }else{
      $s = "<h4>Service Complete: " . $this->_csv->getRecordCnt() . " records processed.</h4>";
    }
    $log = $this->_logger->getLog();
    foreach($log as $item){
      $s .= $item->toHtml();
    }
    return $s;
  }

  public function processNextPage($class_file){
    if( $this->_csv->hasNextPage() ){
      header("Refresh:1; url=" . $class_file . "_file.php?file=" . $this->_csv_model->getFileName() . "&index=" . strval($this->_csv->getRecordIndex()));
    }
  }

  public function processNextService($record=NULL){
    if($record===NULL){
      $record = $this->_csv->getNextRecord();
    }

    if( $this->_process_service_index < count($this->_services) ){
      $this->_services[$this->_process_service_index++]->process($this->_session, $record, [$this, "onProcessServiceComplete"]);
    }else{
      $this->processNextRecord();
    }
  }

  public function processNextRecord(){
    $rec = $this->_csv->getNextRecord();
    if( $rec ){
      $this->_process_service_index = 0;
      $this->_services[$this->_process_service_index++]->process($this->_session, $rec, [$this, "onProcessServiceComplete"]);
    }else{
      $this->_logger->closeLog();
      $this->setStatus(self::QUEUE_COMPLETE);
    }
  }

  public function setProxies(){
    $this->_status = self::INCOMPLETE;
    $loop = EventLoopFactory::create();
    $soap = new SoapFactory($loop);
    foreach($this->_services as $service){
      $service->setProxy($soap, [$this, "onProxyComplete"]);
    }
    $loop->run();
  }

  public function getStatus(){
    return $this->_status;
  }
  public function setStatus($value){
    $this->_status = $value;
    call_user_func_array($this->_status_callback, [$value]);
  }


  // CALLBACKS

  public function onProxyComplete($result){
    if( $this->_status==self::FAIL)
      return;

    if( $result ){
      foreach ($this->_services as $service) {
        if( $service->getProxy()==NULL ){
          return;
        }
      }
      $this->processNextService();
    }else{
      $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv->getRecordIndex(), "0", "SOAP Service Error", "Unable to Connect to service."));
      $this->_logger->closeLog();
      $this->setStatus(self::FAIL);
    }
  }

  public function onProcessServiceComplete($model){
    if( $model->success() ){
      $this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv->getRecordIndex(), $model->getValuesID(), $this->getCurrentService()->getName(), $model->getResultID()));
    }else{
      $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv->getRecordIndex(), $model->getValuesID(), $this->getCurrentService()->getName(), $model->getError()));
    }
    $this->_current_service_model = $model;
    $this->setStatus(self::PROCESS_COMPLETE);
    //$this->_processNextService($model->getResultsID());
  }


}

?>
