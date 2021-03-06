<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/service_input.php";
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/update_order_status.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use wgm\models\ServiceInput as ServiceInputModel;
  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\controllers\AbstractSoapController as AbstractSoapController;
  use wgm\vin65\models\UpdateOrderStatus as UpdateOrderStatusModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class UpdateOrderStatus extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\UpdateOrderStatus" );
      $this->_input_form = new ServiceInputForm( new UpdateOrderStatusModel($session) );
    }

    public function inputRecord($record){
      // create consumable service model for queue
      $input = new ServiceInputModel();
      $input->addRecord($record);

      $this->_queue->setData($input);
      $this->_queue->init($_ENV['UPLOADS_PATH'] . '/update_order_status.csv');
    }



    // CALLBACKS

    // public function onSoapServiceQueueStatus($status){
    //   if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
    //     $model = $this->_queue->getCurrentServiceModel();
    //     if( $model->getClassName()==GetContactModel::METHOD_NAME ){
    //       if( $model->success() ){
    //         $rec = $this->_queue->getCurrentCsvRecord();
    //         $rec["ContactID"] = $model->getResultID();
    //         $this->_queue->processNextService($rec);
    //       }else{
    //         $this->_queue->processNextRecord();
    //       }
    //     }elseif( $model->getClassName()==UpdateOrderStatusModel::METHOD_NAME ){
    //       $this->_queue->processNextRecord();
    //     }
    //   }elseif( $status==SoapServiceQueue::QUEUE_COMPLETE ){
    //     $this->setResultsTable($this->_queue->getLog());
    //     $this->_queue->processNextPage( $this->getClassFileName() );
    //   }elseif( $status==SoapServiceQueue::FAIL ){
    //     $this->setResultsTable($this->_queue->getLog());
    //   }
    // }



  }


?>
