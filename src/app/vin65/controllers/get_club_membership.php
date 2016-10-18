<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/date_converter.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_club_membership.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use wgm\models\ServiceInput as ServiceInputModel;
  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\DateConverter as DateConverter;
  use wgm\vin65\models\GetClubMembership as GetClubMembershipModel;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class GetClubMembership extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetContact" );
      $this->_queue->appendService( "wgm\\vin65\\models\\GetClubMembership" );
      $this->_input_form = new ServiceInputForm( new GetClubMembershipModel($session) );
    }

    // public function inputRecord($record){
    //   // create consumable service model for queue
    //   $input = new ServiceInputModel();
    //   $input->addRecord($record);
    //
    //   $this->_queue->setData($input);
    //   $this->_queue->init($_ENV['UPLOADS_PATH'] . '/get_order_detail.csv');
    // }

    public function getFullResultsTable(){

      $model = $this->_queue->getCurrentServiceModel();

      if( isset($model) ){
        if( $model->success() ){
          // print_r($model->getResult());
          // $res = $model->getResult()->clubMemberships;
          $r = "<h3>Results</h3>";
          if( count($model->getResult()->clubMemberships) == 0 ){
            $r .= '<h4>No Memberships found for ' . $model->getValuesID() . '</h4>';
          }else{

            foreach ($model->getResult()->clubMemberships as $res) {
              $r .= "<div class='media'><div class='media-body'>";
              $ps = (array)($res);
              foreach ($ps as $key => $value) {
                if( isset($value) && $value !== '' ){
                  $r .= "<strong>" . $key . ":</strong> " . $value . "</br>";
                }

              }

                $r .= "</div></div>";
            }
          }
          return $r;
        }
      }

      return parent::getFullResultsTable();

    }


    // CALLBACKS

    public function onSoapServiceQueueStatus($status){
      if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
        $model = $this->_queue->getCurrentServiceModel();
        if( $model->getClassName()==GetContactModel::METHOD_NAME ){
          if( $model->success() ){
            $rec = [];
            $rec["contactid"] = $model->getResultID();
            $this->_queue->processNextService($rec);

          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==GetClubMembershipModel::METHOD_NAME ){
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
