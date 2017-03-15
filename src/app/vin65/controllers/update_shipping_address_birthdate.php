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


  class UpdateShippingAddressBirthdate extends AbstractSoapController{

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
          $this->_completed_addresses = [];
          $this->_total_addresses = [];
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
          $update_model = new UpdateShippingAddressModel($this->_session);
          // $key = $update_model->getValueForKey($rec["changekey"]);
          $this->_remaining_addresses = [];
          $min_date = 1921;
          foreach ($res->shippingAddresses as $value) {

            $year = (int)explode("-", $value->Birthdate)[0];
            if( $value->Birthdate=='' || $year < $min_date || $year > 2000 ){
              array_push($this->_remaining_addresses, $value);
            }

          }

          if( count($this->_remaining_addresses) > 0 ){
            $rec["shippingaddressid"] = array_pop($this->_remaining_addresses)->ShippingAddressID;
            $this->_queue->processNextService($rec);

          }else{
            $this->_queue->recordModelError($model, "Unable to find record with criteria: " . $rec["changekey"] . " = " . $rec["changevalue"]);
            $this->_queue->processNextRecord();
          }

        }elseif( $model->getClassName()=="UpdateShippingAddress" ){
          if( count($this->_remaining_addresses) > 0 ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $rec["shippingaddressid"] = array_pop($this->_remaining_addresses)->ShippingAddressID;
            $this->_queue->processWithService(UpdateShippingAddressModel::METHOD_NAME, $rec);
          }else{
            $this->_queue->processNextRecord();
          }
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
