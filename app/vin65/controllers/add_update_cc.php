<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/add_update_cc.php";

  use wgm\models\CSV as CSVModel;
  use wgm\vin65\controllers\AbstractSoapController as AbstractSoapController;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\AddUpdateCC as AddUpdateCCModel;
  use wgm\vin65\models\ServiceLogger as ServiceLogger;
  use React\EventLoop\Factory as EventLoopFactory;
  use Clue\React\Soap\Factory as SoapFactory;
  use Clue\React\Soap\Proxy;
  use Clue\React\Soap\Client;


  class AddUpdateCC extends AbstractSoapController{

    private $_contact_proxy;
    private $_cc_proxy;

    private function _processRecord(){
      $csv_record = $this->_csv_model->getNextRecord();
      $contact = new GetContactModel($this->_session);
      $contact->setValues($csv_record);
      $this->_contact_proxy->GetContact($contact->getValues())->then(
        function($gc_result) use ($contact, $csv_record){
          $contact->setResult($gc_result);
          //$this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex(), $contact->getCustomerNumber() , 'Service: ContactServices->GetContact', 'ContactID: ' . $contact->getContact()->ContactID));
          $csv_record['ContactID'] = $contact->GetContact()->ContactID;
          $cc = new AddUpdateCCModel($this->_session);
          $cc->setValues($csv_record);
          $this->_cc_proxy->AddUpdateCreditCard($cc->getValues())->then(
            function($cc_result) use ($cc, $contact){
              if($cc_result->IsSuccessful){
                $this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex(), $contact->getCustomerNumber() , 'Service: CreditCardServices->AddUpdateNote', 'CreditCardID: ' . $cc_result->CreditCardID));
              }else{
                $err = '';
                foreach ($cc_result->Errors as $value) {
                  $err .= " Code: " . $value->ErrorCode;
                  $err .= ", Message: " . $value->ErrorMessage . ';';
                }
                $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex(), $contact->getCustomerNumber() , 'Service: CreditCardServices->AddUpdateNote', 'Error: ' . $err));
              }
              if( $this->_csv_model->hasNextRecord() ){
                $this->_processRecord();
              }

            },
            function($excp) use ($contact){
              $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex(), $contact->getCustomerNumber() , 'Service: CreditCardServices->AddUpdateNote', 'Error: ' . $excp->getMessage()));
            }
          );
        },
        function($excp) use ($contact){
          $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerNumber() , 'Service: ContactServices->GetContact', 'Error: ' . $excp->getMessage()));
        }
      );
    }


    public function queueRecords($file, $index=0){
      parent::queueRecords($file, $index);

      if( $this->_csv_model->readFile($file) ){
        $this->_logger->openLog($this->_csv_model->getFile(), $index);

        $loop = EventLoopFactory::create();
        $soap = new SoapFactory($loop);

        $soap->createClient($_ENV['V65_CONTACT_SERVICE'])->then(
          function($contact_client) use ($soap){
            $this->_contact_proxy = new Proxy($contact_client);
            $soap->createClient($_ENV['V65_CC_SERVICE'])->then(
              function($cc_client) use ($soap){
                $this->_cc_proxy = new Proxy($cc_client);
                $this->_processRecord();
              },
              function($excp){
                $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerNumber() , 'Service: CreditCardServices', 'Error: ' . $excp->getMessage()));
              }

            );
          },
          function($excp){
            $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerNumber() , 'Service: CreditCardServices', 'Error: ' . $excp->getMessage()));
          }
        );

        $loop->run();
      }else{
        $this->_logger->writeToLog( ServiceLogger::createFailItem(1, '0000' , 'CSV Reader', 'Unable to read file.'));
      }

      // if( $this->_csv_model->readFile($file) ){
      //   $this->_logger->openLog($this->_csv_model->getFile(), $index);
      //   $contactModel = new GetContactModel($this->_session);
      //   $rec = $this->_csv_model->getCurrentRecord();
      //   //print_r($rec);
      //   $contactModel->callService($rec);
      //   if( $contactModel->success() ){
      //     $rec['ContactID'] = $contactModel->getContact()->ContactID;
      //     $this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex()+1, $contactModel->getCustomerNumber() , 'Service: GetContact', 'ContactID: ' . $contactModel->getContact()->ContactID));
      //     $cc = new AddUpdateCCModel($this->_session);
      //     $cc->callService($rec);
      //     if( $cc->success() ){
      //       $this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex()+1, $contactModel->getCustomerNumber() , 'Service: AddUpdateCC', 'CreditCardID: ' . $cc->getResult()));
      //     }else{
      //       $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contactModel->getCustomerNumber() , 'Service: AddUpdateCC', 'Error: ' . $cc->getError()));
      //     }
      //   }else{
      //     $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contactModel->getCustomerNumber() , 'Service: GetContact', 'Error: ' . $contactModel->getError()));
      //   }
      //   $this->_logger->closeLog();
      //   if( $this->_csv_model->hasNextRecord() ){
      //     $file_bits = explode('/', $this->_csv_model->getFile() );
      //     header("Refresh:1; url=add_update_cc_file.php?file=" . array_pop($file_bits) . "&index=" . strval($this->_csv_model->getRecordIndex()+1) );
      //   }
      // }else{
      //   array_push($this->_errors, "<strong>Unable to read CSV file.</strong>");
      // }
    }

  }

?>
