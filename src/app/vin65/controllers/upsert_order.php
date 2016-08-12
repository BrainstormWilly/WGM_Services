<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/upsert_order.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/upsert_order_csv.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use wgm\vin65\controllers\AbstractSoapController as AbstractSoapController;
  // use wgm\vin65\models\UpsertOrder as UpsertOrderModel;
  use wgm\vin65\models\UpsertOrderCSV as UpsertOrderCSV;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;

  class UpsertOrder extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->setData( new UpsertOrderCSV() );
      $this->_queue->appendService( "wgm\\vin65\\models\\UpsertOrder" );
    }


    // private function _processRecord(){
    //   $record = $this->_orders[$this->_order_index];
    //   if( isset($record["CustomerNumber"]) ){
    //     $contact = new GetContactModel($this->_session);
    //     $contact->setValues($record);
    //     $this->_contact_proxy->GetContact($contact->getValues())->then(
    //       function($gc_result) use ($contact, $record){
    //         $contact->setResult($gc_result);
    //         $record['ContactID'] = $contact->GetContact()->ContactID;
    //         $this->_processOrder($record);
    //       },
    //       function($excp) use ($contact){
    //         $this->_logger->writeToLog( ServiceLogger::createFailItem($record["OrderNumber"], $contact->getCustomerNumber() , 'ContactServices->GetContact', $excp->getMessage()));
    //       }
    //     );
    //   }else{
    //     print_r($record);
    //     $this->_processOrder($record);
    //   }
    // }

    // private function _processOrder($record){
    //   $model = new UpsertOrderModel($this->_session);
    //   $order = $model->addOrderValues($record);
    //   foreach ($record["OrderItems"] as $value) {
    //     $order_item = $model->addOrderItemValues($value);
    //     $model->addOrderItem($order, $order_item);
    //   }
    //   $model->addOrder($order);
    //   // print_r($model->getValues());
    //   // exit;
    //   $this->_order_proxy->UpsertOrder($model->getValues())->then(
    //     function($model_result) use ($model){
    //       if(empty($model_result->results[0]->isSuccessful)){
    //         $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex(), $model->getValuesID() , 'OrderServices->UpsertOrder', $model_result->results[0]->message));
    //       }else{
    //         $this->_logger->writeToLog( ServiceLogger::createSuccessItem($this->_csv_model->getRecordIndex(), $model->getValuesID()  , 'OrderServices->UpsertOrder', $model_result->results[0]->internalKeyCode));
    //       }
    //       if( ++$this->_order_index < count($this->_orders) ){
    //         $this->_processRecord();
    //       }else{
    //         $this->_logger->closelog();
    //       }
    //     },
    //     function($excp) use ($model){
    //       $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex(), $model->getValuesID()  , 'NotesServices->UpsertShippingAddress', $excp->getMessage()));
    //     }
    //   );
    // }

    // public function queueRecords($file, $index=0){
    //   parent::queueRecords($file, $index);
    //
    //   if( $this->_csv_model->readFile($file) ){
    //     $this->_logger->openLog($this->_csv_model->getFile(), $index);
    //     // reorganize records into service consumable
    //     while( $this->_csv_model->hasNextRecord() ){
    //       $rec = $this->_csv_model->getNextRecord();
    //
    //       $index = -1;
    //       $cnt = count($this->_orders);
    //       for($i=0; $i<$cnt; $i++){
    //         if( $this->_orders[$i]["OrderNumber"] == $rec["OrderNumber"] ){
    //           array_push($this->_orders[$i]['OrderItems'], $rec);
    //           $index = $i;
    //           break;
    //         }
    //       }
    //
    //       if( $index == -1 ){
    //         $order = $rec;
    //         $order["OrderItems"] = [$rec];
    //         array_push($this->_orders, $order);
    //       }
    //     }
    //
    //     $loop = EventLoopFactory::create();
    //     $soap = new SoapFactory($loop);
    //
    //     $soap->createClient($_ENV['V65_CONTACT_SERVICE'])->then(
    //       function($contact_client) use ($soap){
    //         $this->_contact_proxy = new Proxy($contact_client);
    //         $soap->createClient($_ENV['V65_V2_ORDER_SERVICES'])->then(
    //           function($model_client) use ($soap){
    //             $this->_order_proxy = new Proxy($model_client);
    //             $this->_processRecord();
    //           },
    //           function($excp){
    //             $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerNumber() , 'OrderServices', $excp->getMessage()));
    //           }
    //
    //         );
    //       },
    //       function($excp){
    //         $this->_logger->writeToLog( ServiceLogger::createFailItem($this->_csv_model->getRecordIndex()+1, $contact->getCustomerNumber() , 'ContactServices', $excp->getMessage()));
    //       }
    //     );
    //
    //     $loop->run();
    //   }else{
    //     $this->_logger->writeToLog( ServiceLogger::createFailItem(1, '0000' , 'CSV Reader', 'Unable to read file.'));
    //   }
    // }

  }


?>
