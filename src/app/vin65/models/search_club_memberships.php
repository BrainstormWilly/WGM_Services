<?php namespace wgm\vin65\models;


  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

  use wgm\models\ServiceInputForm as ServiceInputForm;

  class SearchClubMemberships extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/ClubMembershipService.cfc?wsdl";
    const SERVICE_NAME = "ClubMembershipService";
    const METHOD_NAME = "SearchClubMemberships";

    function __construct($session, $version=3){

      // $vf = ServiceInputForm::FieldValues();
      // $vf['id'] = 'clubmembershipid';
      // $vf['name'] = "Club Membership ID";
      // $vf['required'] = FALSE;
      // array_push($this->_value_fields, $vf);
      //
      // $vf = ServiceInputForm::FieldValues();
      // $vf['id'] = 'clubid';
      // $vf['name'] = "Club ID";
      // $vf['required'] = FALSE;
      // array_push($this->_value_fields, $vf);
      //
      // $vf = ServiceInputForm::FieldValues();
      // $vf['id'] = 'clubname';
      // $vf['name'] = "Club Name";
      // $vf['required'] = FALSE;
      // array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'customernumber';
      $vf['name'] = "Customer Number";
      $vf['type'] = "integer";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'email';
      $vf['name'] = "Email";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'clubname';
      $vf['name'] = "Club Name";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'currentmembershipstatus';
      $vf['name'] = "Current Membership Status";
      $vf['required'] = FALSE;
      $vf['type'] = 'checkbox';
      $vf['choices'] = [
        0 => ['id'=>'active', 'value'=>'Active', 'name'=>'Active'],
        1 => ['id'=>'cancelled', 'value'=>'Cancelled', 'name'=>'Cancelled'],
        2 => ['id'=>'onhold', 'value'=>'OnHold', 'name'=>'OnHold']
      ];
      array_push($this->_value_fields, $vf);

      $this->_value_map = [
        "websiteids" => 'WebsiteIDs',
        "clubmembershipid" => 'ClubMembershipID',
        "altclubmembershipid" => 'AltClubMembershipID',
        "clubid" => 'ClubID',
        "altclubid" => 'AltClubID',
        "clubname" => "ClubName",
        "contactid" => 'ContactID',
        "currentmembershipstatus" => "CurrentMembershipStatus",
        "datemodifiedfrom" => 'DateModifiedFrom',
        "datemodifiedto" => 'DateModifiedTo',
        "customernumber" => "CustomerNumber",
        "email" => "Email",
        "maxrows" => 'MaxRows',
        "page" => 'Page'
      ];

      parent::__construct($session, 3);
    }

    public function setValues($values){
      foreach ($values as $key => $value) {
        $lkey = strtolower($key);

        if( $value!=='' ){
          if( array_key_exists($lkey, $this->_value_map) ){

            if( $lkey=="datemodifiedfrom" || $lkey=="datemodifiedto" ){
              $value = DateConverter::toYMD($value);
            }
            $this->_values[$this->_value_map[$lkey]] = $value;
          }
        }
      }
    }

    public function getValuesID(){
      if( isset($this->_values["ClubMembershipID"]) ){
        return $this->_values["ClubMembershipID"];
      }elseif( isset($this->_values["ClubID"]) ){
        return $this->_values["ClubID"];
      }elseif( isset($this->_values["ClubName"]) ){
        return $this->_values["ClubName"];
      }elseif( isset($this->_values["ContactID"]) ){
        return $this->_values["ContactID"];
      }elseif( isset($this->_values["CustomerNumber"]) ){
        return $this->_values["CustomerNumber"];
      }elseif( isset($this->_values["Email"]) ){
        return $this->_values["Email"];
      }

      return parent::getValuesID();
    }

    public function getResultID(){
      $s = "";
      // print_r($this->_result["ContactTypes"]);
      // exit;
      foreach ($this->_result->ClubMemberships as $value) {
        $s .= $value->ClubID . ", ";
      }
      return $s;
    }

  }

  ?>
