<?php namespace wgm\vin65\models;

require_once $_ENV['APP_ROOT'] . "/models/csv.php";
require_once $_ENV['APP_ROOT'] . "/models/i_service_data.php";

use wgm\models\CSV as CSVModel;
use wgm\models\IServiceData as IServiceData;
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

  public function getMethod(){
    $class = $this->_model_class;
    return $class::METHOD_NAME;
  }

  public function process($session, $values, $callback){
    $model = new $this->_model_class($session);
    $model->setValues($values);
    $class = $this->_model_class;
    $method = $class::METHOD_NAME;

    // print_r($class::METHOD_NAME);
    // print_r("</br>");
    // print_r($values);
    // print_r("</br></br>");
    // exit;

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

  public function out($value){
    print_r("SoapServiceModel->");
    print_r($this->_model_class);
    print_r(": ");
    print_r($value);
    print_r("<br/><br/>");
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
  private $_current_csv_record;
  private $_services = [];
  private $_session;
  private $_process_service_index = 0;
  private $_logger;
  private $_status = 0;
  private $_status_callback;
  private $_data;

  function __construct($session, $callback, $page_limit=25, $display_limit=50, $set_limit=1){
    $this->_session = $session;
    $this->_logger = new ServiceLogger();
    $this->_data = new CSVModel($page_limit, $display_limit, $set_limit); // DEFAULT TO CSV, OVERRIDE W/setData
    $this->_status_callback = $callback;
  }

  public function setData(IServiceData $data_model){
    $this->_data = $data_model;
  }

  public function init($file, $index=0, $cnt=0){
    $this->_data->resetRecordIndex($index, $cnt);
    if( $this->_data->readData($file) ){
        $this->_logger->openLog($this->_data->getFile(), $index);
        $this->setProxies();
    }else{

      $this->_logger->writeToLog( ServiceLogger::createFailItem(0, '0000' , 'CSV Reader', 'Unable to read file.'));
      $this->setStatus(self::FAIL);
    }
    // print_r("Getting Data</br>");
    // print_r($this->_data->getNextRecord());
    // print_r("</br>");
    // print_r($this->_data->getNextRecord());
    // print_r("</br>");
    // print_r('end at SoapServiceQueue');
    // exit;
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
    return $this->_current_csv_record;
  }

  public function getLog($type='html'){
    if( $this->_data->hasNextPage() ){
      $s = "<h4>Service In-Process: " . $this->_data->getCurrentRecordIndex() . " of " . $this->_data->getRecordCnt() . " records processed. " . count($this->_data->getRecords()) . " actual records sent to service in page.</h4>";
    }else{
      $s = "<h4>Service Complete: " . $this->_data->getRecordCnt() . " records processed.</h4>";
      $l = $this->_data->getFileName() . "_log.csv";
      $s .= "<p><a type='button' class='btn btn-default' href='?download={$l}'>Download Full Log</a></p>";
    }
    $log = $this->_logger->getLog();
    foreach($log as $item){
      $s .= $item->toHtml();
    }

    return $s;
  }

  public function getPrimaryService(){
    return $this->_services[0];
  }

  public function processNextPage($class_file){
    if( $this->_data->hasNextPage() ){
      $url = $class_file . "_file.php?file=" . $this->_data->getFileName() . ".csv&index=" . strval($this->_data->getCurrentRecordIndex()) . "&cnt=" . $this->_data->getRecordCnt() . "&page_limit=" . $this->_data->getPageLimit() . "&display_limit=" . $this->_data->getDisplayLimit() . "&set_limit=" . $this->_data->getSetLimit();
      header("Refresh:1; url=" . $url );
    }
  }

  public function processNextService($record=NULL){
    // print_r($record);exit;
    if($record===NULL){
      $this->_current_csv_record = $this->_data->getNextRecord();
    }else{
      $this->_current_csv_record = $record;
    }
    if( $this->_process_service_index < count($this->_services) ){
      $this->_services[$this->_process_service_index++]->process($this->_session, $this->_current_csv_record, [$this, "onProcessServiceComplete"]);
    }else{
      $this->processNextRecord();
    }
  }

  public function processWithService($service, $record=NULL){
    if($record===NULL){
      $this->_current_csv_record = $this->_data->getNextRecord();
    }else{
      $this->_current_csv_record = $record;
    }
    if($record){
      foreach ($this->_services as $value) {
        if( $value->getMethod()==$service ){
          $value->process($this->_session, $this->_current_csv_record , [$this, "onProcessServiceComplete"]);
          break;
        }
      }
    }else{
      $this->_logger->closeLog();
      $this->setStatus(self::QUEUE_COMPLETE);
    }
  }

  public function processNextRecord(){

    $this->_current_csv_record = $this->_data->getNextRecord();
    if( $this->_current_csv_record  ){
      // print_r("next record: <br/>");
      $this->_process_service_index = 0;
      $this->_services[$this->_process_service_index++]->process($this->_session, $this->_current_csv_record , [$this, "onProcessServiceComplete"]);
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

  public function recordModelError($model, $error){
    $svc= $model->getClassName();
    $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_data->getCurrentRecordIndex(), $model->getValuesID(), $svc, $error));
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
      $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_data->getCurrentRecordIndex(), "0", "SOAP Service Error", "Unable to Connect to service."));
      $this->_logger->closeLog();
      $this->setStatus(self::FAIL);
    }
  }

  public function onProcessServiceComplete($model){
    $svc= $model->getClassName();
    if( $model->success() ){
      $this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_data->getCurrentRecordIndex(), $model->getValuesID(), $svc, $model->getResultID()));
    }else{
      $this->recordModelError($model, $model->getError());
    }
    $this->_current_service_model = $model;
    $this->setStatus(self::PROCESS_COMPLETE);
    //$this->_processNextService($model->getResultsID());
  }

  public function out($value){
    print_r("SoapServiceQueue");
    print_r(": ");
    print_r($value);
    print_r("<br/><br/>");
  }
}

?>
