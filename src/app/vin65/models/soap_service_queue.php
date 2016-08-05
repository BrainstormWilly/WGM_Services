<?php namespace wgm\vin65\models;

use React\EventLoop\Factory as EventLoopFactory;
use Clue\React\Soap\Factory as SoapFactory;
use Clue\React\Soap\Proxy;
use Clue\React\Soap\Client;

class SoapServiceModel{

  private $_model_class;
  private $_proxy;
  private $_logger;
  private $_callback;

  function __construct($class, $logger, $callback){
    $this->_logger = $logger;
    $this->_model_class = $class;
    $this->_callback = $callback;
  }

  public function getName(){
    return $this->_model_class::SERVICE_NAME . '->' .   $this->_model_class::SERVICE_METHOD;
  }

  public function process($session, $values, $callback){
    $model = new $this->_model_class($session);
    $model->setValues($values);
    $method = $this->_model_class::METHOD_NAME;
    $this->_proxy->$method($model->getValues())->then(
      function($result) use ($model, $callback){
        $model->setResult($result);
        $callback($model);
      },
      function($excp) use ($model, $callback){
        $model->setError($excp->getMessage());
        $callback($model);
      }
    );
  }

  public function setProxy($soap, $callback){
    $soap->createClient($this->_model_class::SERVICE_WSDL)->then(
      function($client) use ($callback){
        $this->_proxy = new Proxy($client);
        $callback(true);
      },
      function($excp) use ($callback){
        $this->_logger->writeToLog( ServiceLogger::createFailItem(0, "0000" , $this->getName(), $excp->getMessage()));
        $callback(false);
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
    COMPLETE = 3;

  private $_services = [];
  private $_session;
  private $_process_index = 0;
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
    array_push($this->_services, $service);
  }

  public function processNextService($values){
    if( $this->_process_index+1 < count($this->_services) ){
      $this->_services[$this->_process_index++]->process($this->_session, [$this, "onProcessServiceComplete"]);
    }else{

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
    $this->_status_callback($value);
  }


  // CALLBACKS

  public function onProxyComplete($result){
    if( $this->_status==self::FAIL)
      return;

    if( $result ){
      foreach ($this->_services as $service) {
        if( !isset($service->getProxy()) ){
          return;
        }
      }
      $this->processNextService();
    }else{
      $this->setStatus(self::FAIL);
    }
  }

  public function onProcessServiceComplete($model){
    if( $model->success() ){
      
    }
  }


}

?>
