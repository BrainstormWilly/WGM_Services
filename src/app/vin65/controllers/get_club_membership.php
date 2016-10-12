<?php namespace wgm\vin65\controllers;

  require_once $_ENV['APP_ROOT'] . "/vin65/controllers/abstract_soap_controller.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/date_converter.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/get_club_membership.php";
  require_once $_ENV['APP_ROOT'] . "/vin65/models/soap_service_queue.php";

  use wgm\models\ServiceInput as ServiceInputModel;
  use wgm\models\ServiceInputForm as ServiceInputForm;
  use wgm\vin65\models\DateConverter as DateConverter;
  use wgm\vin65\models\GetClubMembership as GetClubMembershipModel;
  use wgm\vin65\models\SoapServiceQueue as SoapServiceQueue;


  class GetClubMembership extends AbstractSoapController{

    function __construct($session){
      parent::__construct($session);
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
          $res = $model->getResult()->contacts[0];
          if( !empty($res) ){
            $r = "<div class='panel panel-default'>" .
                    "<div class='panel-body'>" .
                      "<row>" .
                        "<div class='col-sm-2'>" .
                          "<strong>ID: </strong></br>" .
                          "<strong>Customer Number: </strong></br>" .
                          "<strong>Added: </strong></br>" .
                          "<strong>Name: </strong></br>" .
                          "<strong>Birthdate: </strong></br>" .
                          "<strong>Email: </strong></br>" .
                          "<strong>Phone: </strong></br>" .
                          "<strong>Address: </strong>" .
                        "</div>" .
                        "<div class='col-sm-4'>" .
                          $res->ContactID . "</br>" .
                          $res->AccountNumber . "</br>" .
                          DateConverter::toMDY($res->DateAdded) . "</br>" .
                          $res->Firstname . " " . $res->Lastname . "</br>" .
                          DateConverter::toMDY($res->Birthdate) . "</br>" .
                          $res->Email . "</br>" .
                          $res->MainPhone . "</br>" .
                          $res->Address . "</br>";
            if( $res->Company !== '' ){
                          $r .= $res->Company . "</br>";
            }
            if( $res->Address2 !== '' ){
                          $r .= $res->Address2 . "</br>";
            }
                          $r .= $res->City . ", " . $res->StateCode . " " . $res->ZipCode .
                        "</div>" .
                      "</row" .
                    "</div>" .
                  "</div>";

            return $r;
          }
        }
      }

      return parent::getFullResultsTable();

    }


  }


?>
