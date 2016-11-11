<?php namespace wgm\vin65\models;


  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/models/date_converter.php';
  require_once $_ENV['APP_ROOT'] . "/models/service_input_form.php";

  use wgm\models\ServiceInputForm as ServiceInputForm;

  class UpdateGiftCard extends AbstractSoapModel{

    const SERVICE_WSDL = "https://webservices.vin65.com/V300/GiftCardService.cfc?wsdl";
    const SERVICE_NAME = "GiftCardService";
    const METHOD_NAME = "UpdateGiftCard";

    function __construct($session, $version=3){

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'cardnumber';
      $vf['name'] = "Gift Card Number";
      $vf['type'] = "integer";
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'title';
      $vf['name'] = "Title";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'notes';
      $vf['name'] = "Notes";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'expirydate';
      $vf['name'] = "Expiration Date <small>(leave blank if unchanged)</small>";
      $vf['type'] = "date";
      $vf['required'] = FALSE;
      array_push($this->_value_fields, $vf);

      $vf = ServiceInputForm::FieldValues();
      $vf['id'] = 'isactive';
      $vf['name'] = "Is Active?";
      $vf['type'] = 'radio';
      $vf['choices'] = [
        0 => ['id'=>'yes', 'value'=>1, 'name'=>'Yes'],
        1 => ['id'=>'no', 'value'=>0, 'name'=>'No']
      ];
      array_push($this->_value_fields, $vf);

      $this->_value_map = [
        "giftcardid" => 'GiftCardID',
        "cardnumber" => 'CardNumber',
        "code" => 'Code',
        "title" => 'Title',
        "expirydate" => 'ExpiryDate',
        "notes" => 'Notes',
        "isactive" => 'IsActive'
      ];

      parent::__construct($session, $version);
      $this->_values["GiftCard"] = [];
      $this->_values["GiftCard"]["IsActive"] = 1; // required field change or no
    }

    public function setValues($values){
      foreach ($values as $key => $value) {
        $lkey = strtolower($key);

        if( $this->_isRealValue($value) ){
          if( array_key_exists($lkey, $this->_value_map) ){

            if( $lkey=="expirydate" ){
              $value = DateConverter::toYMD($value);
            }
            $this->_values["GiftCard"][$this->_value_map[$lkey]] = $value;
          }
        }
      }
    }

    public function getValuesID(){
      if( isset($this->_values["GiftCard"]["CardNumber"]) ){
        return $this->_values["GiftCard"]["CardNumber"];
      }elseif( isset($this->_values["GiftCard"]["Title"]) ){
        return $this->_values["GiftCard"]["Title"];
      }elseif( isset($this->_values["GiftCard"]["Code"]) ){
        return $this->_values["GiftCard"]["Code"];
      }

      return parent::getValuesID();
    }

    public function getResultID(){
      // there is no return code for this service
      return $this->_values["GiftCard"]["GiftCardID"];

      // return parent::getResultID();
    }

  }

  ?>
