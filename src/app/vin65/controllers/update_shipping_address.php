<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_shipping_address.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/update_shipping_address.php";

  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\GetShippingAddress as GetShippingAddressModel;
  use wgm\vin65\models\UpdateShippingAddress as UpdateShippingAddressModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class UpdateShippingAddress extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetContact" );
      $this->_queue->appendService( "wgm\\vin65\\models\\GetShippingAddress" );
      $this->_queue->appendService( "wgm\\vin65\\models\\UpdateShippingAddress" );
      // $this->_input_form = new ServiceInputForm( new UpdateShippingAddressModel($session) );
    }


    // CALLBACKS

    public function onSoapServiceQueueStatus($status){
      if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
        $model = $this->_queue->getCurrentServiceModel();
        if( $model->getClassName()==GetContactModel::METHOD_NAME ){
          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $rec["contactid"] = $model->getResultID();
            $this->_queue->processNextService($rec);
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==GetShippingAddressModel::METHOD_NAME ){
          $rec = $this->_queue->getCurrentCsvRecord();
          $res = $model->getResult();
          $update_model = new UpdateShippingAddressModel($this->_session)
          $key = $update_model->getValueForKey($rec["changekey"]);

          foreach ($res->shippingAddresses as $value) {
            if( property_exists($value, $key) && $value->$key == $rec['changevalue'] ){
              $addr = $value;
              break;
            }
          }

          if( isset($addr) ){
            $rec["shippingaddressid"] = $addr->ShippingAddressID;
            // print_r($rec);
            // exit;
            $this->_queue->processNextService($rec);
          }else{
            $this->_queue->recordModelError($model, "Unable to find record with criteria: " . $rec["changekey"] . " = " . $rec["changevalue"]);
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()=="UpdateShippingAddress" ){
          $this->_queue->processNextRecord();
        }
      }elseif( $status==SoapServiceQueue::QUEUE_COMPLETE ){
        $this->setResultsTable($this->_queue->getLog());
        $this->_queue->processNextPage( $this->getClassFileName() );
      }elseif( $status==SoapServiceQueue::FAIL ){
        $this->setResultsTable($this->_queue->getLog());
      }
    }


  }


?>
