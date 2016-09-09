<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_gift_card.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/update_gift_card.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\GetGiftCard as GetGiftCardModel;
  use wgm\vin65\models\UpdateGiftCard as UpdateGiftCardModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;

  class UpdateGiftCard extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetGiftCard" );
      $this->_queue->appendService( "wgm\\vin65\\models\\UpdateGiftCard" );
      $this->_input_form = new ServiceInputForm( new UpdateGiftCardModel($session) );
    }

    // CALLBACKS

    public function onSoapServiceQueueStatus($status){
      if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
        $model = $this->_queue->getCurrentServiceModel();
        if( $model->getClassName()==GetGiftCardModel::METHOD_NAME ){
          if( $model->success() ){
            $result = $model->getResult();
            $rec = $this->_queue->getCurrentCsvRecord();
            $rec["giftcardid"] = $result->GiftCard->GiftCardID;
            $rec["code"] = $result->GiftCard->Code;
            if( empty($rec["title"]) ){
              $rec["title"] = $result->GiftCard->Title;
            }
            if( empty($rec["notes"]) ){
              $rec["notes"] = $result->GiftCard->Notes;
            }
            if( empty($rec["expirydate"]) ){
              $rec["expirydate"] = $result->GiftCard->ExpiryDate;
            }

            $this->_queue->processNextService($rec);
          }else{

            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==UpdateGiftCardModel::METHOD_NAME ){
          // print_r($model->getValues());
          // exit;
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
