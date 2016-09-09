<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_order_detail.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/post_order_shipping_status.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/post_order_tracking.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/post_pickup.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/upsert_order.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/upsert_order_csv.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use wgm\vin65\controllers\AbstractSoapController as AbstractSoapController;
  use wgm\vin65\models\PostOrderShippingStatus as PostOrderShippingStatusModel;
  use wgm\vin65\models\GetOrderDetail as GetOrderDetailModel;
  use wgm\vin65\models\PostOrderTracking as PostOrderTrackingModel;
  use wgm\vin65\models\PostPickup as PostPickupModel;
  use wgm\vin65\models\UpsertOrder as UpsertOrderModel;
  use wgm\vin65\models\UpsertOrderCSV as UpsertOrderCSV;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;

  class UpsertOrder extends AbstractSoapController{

    private $_tracking_index = 0;

    function __construct($session){
      parent::__construct($session);

      $this->_queue->appendService( "wgm\\vin65\\models\\UpsertOrder" );
      $this->_queue->appendService( "wgm\\vin65\\models\\GetOrderDetail" );
      $this->_queue->appendService( "wgm\\vin65\\models\\PostOrderShippingStatus" );
      $this->_queue->appendService( "wgm\\vin65\\models\\PostOrderTracking" );
      $this->_queue->appendService( "wgm\\vin65\\models\\PostPickup" );
    }

    public function getCsvForm($has_sets=false){
      return parent::getCsvForm(FALSE);
    }

    public function setData($page_limit=25, $display_limit=50, $set_limit=1){
      $this->_queue->setData( new UpsertOrderCSV($page_limit, $display_limit, $set_limit) );
    }

    // CALLBACKS

    public function onSoapServiceQueueStatus($status){
      if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
        $model = $this->_queue->getCurrentServiceModel();
        $rec = $this->_queue->getCurrentCsvRecord();
        if( $model->success() ){
          if( $model->getClassName()==UpsertOrderModel::METHOD_NAME ){
            $rec["OrderID"] = $model->getResultID();
            unset($rec['OrderNumber']);
            $this->_queue->processWithService(GetOrderDetailModel::METHOD_NAME, $rec);
          }elseif ($model->getClassName()==GetOrderDetailModel::METHOD_NAME) {
            $res = $model->getResult();
            $rec['OrderNumber'] = $res->Order->OrderNumber;
            if ( $rec['isPickup']==1 ) {
              $this->_queue->processWithService(PostPickupModel::METHOD_NAME, $rec);
            }else{
              $this->_queue->processWithService(PostOrderShippingStatusModel::METHOD_NAME, $rec);
            }
          }elseif( $rec['isPickup']==0 ){
            // next up: tracking
            $this->_queue->processWithService(UpsertOrderModel::METHOD_NAME);
          }
        }else{
          $this->_tracking_index = 0;
          $this->_queue->processWithService(UpsertOrderModel::METHOD_NAME);
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
