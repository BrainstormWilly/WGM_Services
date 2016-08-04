<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/upsert_contact.php";

  use wgm\models\CSV as CSVModel;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\controllers\AbstractSoapController as AbstractSoapController;
  use wgm\vin65\models\UpsertContact as UpsertContactModel;
  use wgm\vin65\models\ServiceLogger as ServiceLogger;
  use React\EventLoop\Factory as EventLoopFactory;
  use Clue\React\Soap\Factory as SoapFactory;
  use Clue\React\Soap\Proxy;
  use Clue\React\Soap\Client;

  class UpsertContact extends AbstractSoapController{

    private $_contact_proxy;

    private function _processRecord(){
      $csv_record = $this->_csv_model->getNextRecord();
      if( !$csv_record ){
        $this->_logger->closeLog();
        $log = $this->_logger->getLog();
        if( $this->_csv_model->hasNextPage() ){
          $t = "<h4>Service In-Process: " . $this->_csv_model->getRecordIndex() . " of " . $this->_csv_model->getRecordCnt() . " records processed.</h4>";
        }else{
          $t = "<h4>Service Complete: " . $this->_csv_model->getRecordCnt() . " records processed.</h4>";
        }

        foreach($log as $rec){
          $t .= $rec->toHtml();
        }

        $this->setResultsTable($t);

        if( $this->_csv_model->hasNextPage() ){
          header("Refresh:1; url=upsert_contact_file.php?file=" . $this->_csv_model->getFileName() . "&index=" . strval($this->_csv_model->getRecordIndex()));
        }

      }else{
        $get_contact = new GetContactModel($this->_session);
        $get_contact->setValues($csv_record);

        $this->_contact_proxy->GetContact($get_contact->getValues())->then(
          function($gc_result) use ($get_contact, $csv_record){

            if( $gc_result->isSuccessful ){
              $get_contact->setResult($gc_result);
              //$this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex(), $get_contact->getCustomerNumber() , 'Service: ContactServices->GetContact', 'ContactID: ' . $get_contact->getContact()->ContactID));

              $csv_record['ContactID'] = $gc_result->contacts[0]->ContactID;

              $upsert_contact = new UpsertContactModel($this->_session);
              $upsert_contact_record = $upsert_contact->addContactValues($csv_record);
              $upsert_contact->addContact($upsert_contact_record);
              $this->_contact_proxy->UpsertContact($upsert_contact->getValues())->then(
                function($model_result) use ($upsert_contact){
                  $this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex(), $upsert_contact->getValuesID() , 'ContactV2Services->UpsertContact', $upsert_contact->getResultID()));
                  $this->_processRecord();
                },
                function($excp) use ($upsert_contact){
                  $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex(), $upsert_contact->getValuesID() , 'ContactV2Services->UpsertContact', $excp->getMessage()));
                  $this->_processRecord();
                }
              );
            }else{
              $err = "";
              foreach($result->Errors as $value){
                $err .= $value->ErrorCode . ": " . $value->ErrorMessage . "; ";
              }
              $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $get_contact->getValuesID() , 'ContactV2Services->GetContact', $err));
              $this->_processRecord();
            }

          },
          function($excp) use ($get_contact){
            $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $get_contact->getValuesID() , 'ContactV2Services->GetContact', $excp->getMessage()));
            $this->_processRecord();
          }
        );
      }


    }

    public function queueRecords($file, $index=0){
      parent::queueRecords($file, $index);

      if( $this->_csv_model->readFile($file) ){
        $this->_logger->openLog($this->_csv_model->getFile(), $index);

        $loop = EventLoopFactory::create();
        $soap = new SoapFactory($loop);

        $soap->createClient($_ENV['V65_V2_CONTACT_SERVICE'])->then(
          function($contact_client) use ($soap){
            $this->_contact_proxy = new Proxy($contact_client);
            $this->_processRecord();
            // $soap->createClient($_ENV['V65_V2_CONTACT_SERVICE'])->then(
            //   function($model_client){
            //     $this->_model_proxy = new Proxy($model_client);
            //     $this->_processRecord();
            //   },
            //   function($excp){
            //     $this->_logger->closeLog();
            //     $this->setResultsTable('<h4 style="color:red">ContactV2Services Error: ' . $excp->getMessage() . '</h4>');
            //   }
            //
            // );
          },
          function($excp){
            $this->_logger->closeLog();
            $this->setResultsTable('<h4 style="color:red">ContactV3Services Error: ' . $excp->getMessage() . '</h4>');
          }
        );

        $loop->run();
      }else{
        $this->setResultsTable('<h4 style="color:red">CSV Reader Error: Unable to read file.</h4>');
      }
    }


  }


?>
