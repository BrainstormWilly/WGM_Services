<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/add_update_cc.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use wgm\vin65\controllers\AbstractSoapController as AbstractSoapController;
  use wgm\vin65\models\AddUpdateCC as AddUpdateCCModel;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class AddUpdateCC extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetContact" );
      $this->_queue->appendService( "wgm\\vin65\\models\\AddUpdateCC" );
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
    //       $cc = new AddUpdateCCModel($this->_session);
    //       $cc->setValues($csv_record);
    //       $this->_cc_proxy->AddUpdateCreditCard($cc->getValues())->then(
    //         function($cc_result) use ($cc, $contact){
    //           if($cc_result->IsSuccessful){
    //             $this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex(), $contact->getCustomerNumber() , 'Service: CreditCardServices->AddUpdateNote', 'CreditCardID: ' . $cc_result->CreditCardID));
    //           }else{
    //             $err = '';
    //             foreach ($cc_result->Errors as $value) {
    //               $err .= " Code: " . $value->ErrorCode;
    //               $err .= ", Message: " . $value->ErrorMessage . ';';
    //             }
    //             $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex(), $contact->getCustomerNumber() , 'Service: CreditCardServices->AddUpdateNote', 'Error: ' . $err));
    //           }
    //           if( $this->_csv_model->hasNextRecord() ){
    //             $this->_processRecord();
    //           }
    //
    //         },
    //         function($excp) use ($contact){
    //           $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex(), $contact->getCustomerNumber() , 'Service: CreditCardServices->AddUpdateNote', 'Error: ' . $excp->getMessage()));
    //         }
    //       );
    //     },
    //     function($excp) use ($contact){
    //       $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerNumber() , 'Service: ContactServices->GetContact', 'Error: ' . $excp->getMessage()));
    //     }
    //   );
    // }


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
    //         $soap->createClient($_ENV['V65_CC_SERVICE'])->then(
    //           function($cc_client) use ($soap){
    //             $this->_cc_proxy = new Proxy($cc_client);
    //             $this->_processRecord();
    //           },
    //           function($excp){
    //             $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerNumber() , 'Service: CreditCardServices', 'Error: ' . $excp->getMessage()));
    //           }
    //
    //         );
    //       },
    //       function($excp){
    //         $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerNumber() , 'Service: CreditCardServices', 'Error: ' . $excp->getMessage()));
    //       }
    //     );
    //
    //     $loop->run();
    //   }else{
    //     $this->_logger->writeToLog( ServiceLogger::createFailItem(1, '0000' , 'CSV Reader', 'Unable to read file.'));
    //   }
    //
    //
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
        }elseif( $model->getClassName()==AddUpdateCCModel::METHOD_NAME ){
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
