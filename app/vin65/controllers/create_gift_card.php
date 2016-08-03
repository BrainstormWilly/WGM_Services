<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/create_gift_card.php";

  use wgm\models\CSV as CSVModel;
  use wgm\vin65\controllers\AbstractSoapController as AbstractSoapController;
  use wgm\vin65\models\CreateGiftCard as CreateGiftCardModel;
  use wgm\vin65\models\ServiceLogger as ServiceLogger;
  use React\EventLoop\Factory as EventLoopFactory;
  use Clue\React\Soap\Factory as SoapFactory;
  use Clue\React\Soap\Proxy;
  use Clue\React\Soap\Client;

  class CreateGiftCard extends AbstractSoapController{

    private $_proxy;

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
          header("Refresh:1; url=create_gift_card_file.php?file=" . $this->_csv_model->getFileName() . "&index=" . strval($this->_csv_model->getRecordIndex()));
        }

      }else{

        $model = new CreateGiftCardModel($this->_session);
        $model->setValues($csv_record);
        $this->_proxy->CreateGiftCard($model->getValues())->then(
          function($result) use ($model, $csv_record){
            if( $result->IsSuccessful ){
              $model->setResult($result);
              $this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex(), $model->getValuesID() , 'GiftCardService->CreateGiftCard', $model->getResultID()));
              $this->_processRecord();
            }else{
              $err = "";
              foreach($result->Errors as $value){
                $err .= $value["ErrorCode"] . ": " . $value["ErrorMessage"] . "; ";
              }
              $model->setError($err);
              $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $model->getValuesID() , 'GiftCardService->CreateGiftCard', $model->getError()));
              $this->_processRecord();
            }
          },
          function($excp) use ($model){
            $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $model->getValuesID() , 'GiftCardService->CreateGiftCard', $excp->getMessage()));
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

        $soap->createClient($_ENV['V65_GIFTCARD_SERVICE'])->then(
          function($card_client) use ($soap){
            $this->_proxy = new Proxy($card_client);
            $this->_processRecord();
          },
          function($excp){
            $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerNumber() , 'ContactServices', $excp->getMessage()));
            $this->_logger->closeLog();
            $this->setResultsTable("<h4 style='color:red'>ContactServices Client Error: " . $excp->getMessage() . "</h4>");
          }
        );

        $loop->run();
      }else{
        $this->setResultsTable("<h4 style='color:red'>CSV Reader Failure: unable to read file " . $file . "</h4>");
      }
    }
  }

  ?>
