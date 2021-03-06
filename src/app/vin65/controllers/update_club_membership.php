<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/upsert_club_membership.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_contact.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_club_membership.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";


  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\GetContact as GetContactModel;
  use wgm\vin65\models\GetClubMembership as GetClubMembershipModel;
  use wgm\vin65\models\UpsertClubMembership as UpsertClubMembershipModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class UpdateClubMembership extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
      $this->_queue->appendService( "wgm\\vin65\\models\\GetContact" );
      $this->_queue->appendService( "wgm\\vin65\\models\\GetClubMembership" );
      $this->_queue->appendService( "wgm\\vin65\\models\\UpsertClubMembership" );
      $this->_input_form = new ServiceInputForm( new UpsertClubMembershipModel($session) );
    }


    // CALLBACKS

    public function onSoapServiceQueueStatus($status){
      if( $status==SoapServiceQueue::PROCESS_COMPLETE ){
        $model = $this->_queue->getCurrentServiceModel();
        if( $model->getClassName()==GetContactModel::METHOD_NAME ){

          if( $model->success() ){
            $rec = $this->_queue->getCurrentCsvRecord();
            $rec["contactid"] = $model->getResultID();
            $this->_queue->processNextService($rec);
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif ($model->getClassName()==GetClubMembershipModel::METHOD_NAME) {
          if( $model->success() ){
            $res = $model->getResult();
            $rec = $this->_queue->getCurrentCsvRecord();

            foreach ($res->clubMemberships as $cm) {
              if( $cm->ClubName==$rec["clubname"] ){
                $rec["clubMembershipid"] = $cm->ClubMembershipID;
                break;
              }
            }
            // print_r($rec["clubname"]);
            // print_r("</br>");
            print_r($rec["clubMembershipid"]);
            exit;
            $this->_queue->processNextService($rec);
          }else{
            $this->_queue->processNextRecord();
          }
        }elseif( $model->getClassName()==UpsertClubMembershipModel::METHOD_NAME ){
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
