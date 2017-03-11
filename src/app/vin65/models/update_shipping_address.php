<?php namespace wgm\vin65\models;

require_once $_ENV['APP_ROOT'] . '/vin65/models/add_shipping_address.php';
// require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
// require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

use wgm\models\ServiceInputForm as ServiceInputForm;


  class UpdateShippingAddress extends AddShippingAddress{

    function __construct($session){
      parent::__construct($session);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'changekey';
      $vf['name'] = "Change Key";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'changevalue';
      $vf['name'] = "Change Value";
      array_push($this->_value_fields, $vf);

      array_push( $this->_value_map, ['changekey' => 'changekey'] );
      array_push( $this->_value_map, ['changevalue' => 'changevalue'] );

    }

  }
