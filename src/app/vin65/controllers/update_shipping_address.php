<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_shipping_address.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/update_shipping_address.php";
  require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';

  use wgm\vin65\models\DateConverter as DateConverter;
  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\GetShippingAddress as GetShippingAddressModel;
  use wgm\vin65\models\UpdateShippingAddress as UpdateShippingAddressModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class UpdateShippingAddress extends AbstractSoapController{

    protected $_completed_addresses = [];
    protected $_total_addresses = [];

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
          $key = $update_model->getValueForKey($rec["changekey"]);
          $this->_total_addresses = $res->shippingAddresses;
          foreach ($res->shippingAddresses as $value) {
            // if( property_exists($value, $key) && $value->$key == $rec['changevalue'] ){
            //   $addr = $value;
            //   break;
            // }
            if( !in_array($value->ShippingAddressID, $this->_completed_addresses) ){
              if( $value->Birthdate=="" ){
                print_r("Birthdate is null");
                print_r("<br/>");
                // $value->Birthdate = DateConverter::toYMD($rec['changevalue']);
                $rec['birthdate'] = "1970-01-01T12:00:00";
                $addr = $value;
                break;
              }else{
                array_push($this->_completed_addresses, $value->ShippingAddressID);
              }
            }
          }

          if( isset($addr) ){
            $rec["shippingaddressid"] = $addr->ShippingAddressID;
            if( count($res->shippingAddresses)>1 ){
              array_push($this->_completed_addresses, $addr->ShippingAddressID);
            }
            $this->_queue->processNextService($rec);

          }else{
            $this->_queue->recordModelError($model, "Unable to find record with criteria: " . $rec["changekey"] . " = " . $rec["changevalue"]);
            $this->_queue->processNextRecord();
          }

          // if( isset($addr) ){
          //   $rec["shippingaddressid"] = $addr->ShippingAddressID;
          //   $this->_queue->processNextService($rec);
          // }else{
          //   $this->_queue->recordModelError($model, "Unable to find record with criteria: " . $rec["changekey"] . " = " . $rec["changevalue"]);
          //   $this->_queue->processNextRecord();
          // }
        }elseif( $model->getClassName()=="UpdateShippingAddress" ){
          print_r("Record updated");
          print_r("<br/><br/>");
          if( count($this->_total_addresses) > count($this->_completed_addresses) ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $this->_queue->processWithService($model, $rec);
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
