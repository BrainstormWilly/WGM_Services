<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/upsert_shipping_address.php";


  use wgm\vin65\controllers\AbstractSoapController as AbstractSoapController;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\UpsertShippingAddress as UpsertShippingAddressModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class UpsertShippingAddress extends AbstractSoapController{

    // private $_contact_proxy;
    // private $_addr_proxy;

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetContact" );
      $this->_queue->appendService( "wgm\\vin65\\models\\UpsertShippingAddress" );
    }

    // private function _processRecord(){
    //   $csv_record = $this->_csv_model->getNextRecord();
    //   $contact = new GetContactModel($this->_session);
    //   $contact->setValues($csv_record);
    //   $this->_contact_proxy->GetContact($contact->getValues())->then(
    //     function($gc_result) use ($contact, $csv_record){
    //       $contact->setResult($gc_result);
    //       //$this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex(), $contact->getCustomerNumber() , 'Service: ContactServices->GetContact', 'ContactID: ' . $contact->getContact()->ContactID));
    //       $csv_record['ContactID'] = $contact->GetContact()->ContactID;
    //
    //       $addr = new UpsertShippingAddressModel($this->_session);
    //       $addr_item = $addr->addAddressValues($csv_record);
    //       $addr->addAddress($addr_item);
    //       $this->_addr_proxy->UpsertShippingAddress($addr->getValues())->then(
    //         function($model_result) use ($addr, $contact){
    //           if(empty($model_result->results[0]->isSuccessful)){
    //             $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex(), $contact->getCustomerNumber() , 'Service: ContactServices->UpsertShippingAddress', 'Error: ' . $model_result->results[0]->message));
    //           }else{
    //             $this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex(), $contact->getCustomerNumber() , 'Service: NotesServices->UpsertShippingAddress', 'Result: ' . $model_result->results[0]->internalKeyCode));
    //           }
    //           if( $this->_csv_model->hasNextRecord() ){
    //             $this->_processRecord();
    //           }
    //         },
    //         function($excp) use ($contact){
    //           $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex(), $contact->getCustomerNumber() , 'Service: NotesServices->UpsertShippingAddress', 'Error: ' . $excp->getMessage()));
    //         }
    //       );
    //     },
    //     function($excp) use ($contact){
    //       $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerNumber() , 'Service: ContactServices->GetContact', 'Error: ' . $excp->getMessage()));
    //     }
    //   );
    // }
    //
    // public function queueRecords($file, $index=0){
    //   parent::queueRecords($file, $index);
    //
    //   if( $this->_csv_model->readFile($file) ){
    //     $this->_logger->openLog($this->_csv_model->getFile(), $index);
    //
    //     $loop = EventLoopFactory::create();
    //     $soap = new SoapFactory($loop);
    //
    //     $soap->createClient($_ENV['V65_CONTACT_SERVICE'])->then(
    //       function($contact_client) use ($soap){
    //         $this->_contact_proxy = new Proxy($contact_client);
    //         $soap->createClient($_ENV['V65_V2_CONTACT_SERVICE'])->then(
    //           function($addr_client) use ($soap){
    //             $this->_addr_proxy = new Proxy($addr_client);
    //             $this->_processRecord();
    //           },
    //           function($excp){
    //             $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerNumber() , 'Service: NotesServices', 'Error: ' . $excp->getMessage()));
    //           }
    //
    //         );
    //       },
    //       function($excp){
    //         $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerNumber() , 'Service: ContactServices', 'Error: ' . $excp->getMessage()));
    //       }
    //     );
    //
    //     $loop->run();
    //   }else{
    //     $this->_logger->writeToLog( ServiceLogger::createFailItem(1, '0000' , 'CSV Reader', 'Unable to read file.'));
    //   }
    // }

    // CALLBACKS

    public function onSoapServiceQueueStatus($status){
      if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
        $model = $this->_queue->getCurrentServiceModel();
        if( $model->getClassName()==GetContactModel::METHOD_NAME ){
          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $rec["ContactID"] = $model->getResultID();
            $this->_queue->processNextService($rec);
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==UpsertShippingAddressModel::METHOD_NAME ){
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
