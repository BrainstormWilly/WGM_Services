<?php namespace wgm\nexternal\models;

  require_once $_ENV['APP_ROOT'] . "/nexternal/models/abstract_xml_model.php";

  class AdditionalAddressesQueryRequest extends AbstractXmlModel{


    function __construct($session, $page=1){
      parent::__construct($session, $page);
      $this->_url = "https://www.nexternal.com/shared/xml/customerquery.rest";
      $this->_input = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>" .
                        "<CustomerQueryRequest>" .
                           "<Credentials>" .
                            "<AccountName>{$session['account']}</AccountName>" .
                            "<Key>{$session['key']}</Key>" .
                           "</Credentials>" .
                           "<Page>{$page}</Page>" .
                        "</CustomerQueryRequest>";
      $this->_keys = [
        'customernumber',
        'contactid',
        'altcontactid',
        'shippingaddressid',
        'altshippingaddressid',
        'nickname',
        'birthdate',
        'firstname',
        'lastname',
        'company',
        'address',
        'address2',
        'city',
        'statecode',
        'countrycode',
        'zipcode',
        'mainphone',
        'email',
        'isprimary',
        'lookupemail'
      ];
      $this->_v65_map = [
        'customernumber' => '',
        'contactid' => '',
        'altcontactid' => '',
        'shippingaddressid' => '',
        'altshippingaddressid' => '',
        'nickname' => '',
        'birthdate' => '',
        'firstname' => '',
        'lastname' => '',
        'company' => '',
        'address' => '',
        'address2' => '',
        'city' => '',
        'statecode' => '',
        'countrycode' => '',
        'zipcode' => '',
        'mainphone' => '',
        'email' => '',
        'isprimary' => 0,
        'lookupemail' => ''
      ];
    }

    private function _parseV65Address($v, $n){
      if( isset($n[0]) ){
        $n = $n[0];
      }
      if( array_key_exists('Company', $n) ){
        $v['company'] = $n['Company'];
      }
      if( array_key_exists('Name', $n) ){
        $v['firstname'] = $n['Name']['FirstName'];
        $v['lastname'] = $n['Name']['LastName'];
      }
      if( array_key_exists('StreetAddress1', $n) ){
        $v['address'] = $n['StreetAddress1'];
      }
      if( array_key_exists('StreetAddress2', $n) ){
        $v['address2'] = $n['StreetAddress2'];
      }
      if( array_key_exists('City', $n) ){
        $v['city'] = $n['City'];
      }
      if( array_key_exists('StateProvCode', $n) ){
        $v['statecode'] = $n['StateProvCode'];
      }
      if( array_key_exists('ZipPostalCode', $n) ){
        $v['zipcode'] = $n['ZipPostalCode'];
      }
      if( array_key_exists('PhoneNumber', $n) ){
        $v['mainphone'] = $n['PhoneNumber'];
      }
      if( array_key_exists('PrimaryShip',$n) ){
        $v['isprimary'] = 1;
      }
      return $v;
    }

    // public function convertOutputToCsv($data){
    //   $keys = $this->_keys;
    //   $csv;
    //   $csvs = [$keys];
    //
    //   foreach($data as $rec){
    //     $csv = [];
    //     foreach($keys as $key){
    //       $csv[$key] = $rec[$key];
    //     }
    //     array_push($csvs, $csv);
    //
    //   }
    //   return $csvs;
    // }

    public function getOutputToV65Array(){
      $o = $this->getOutputToArray();
      $vs = [];
      $cust = $o['Customer'];
      // if( isset($o['Customer'][0]) ){
      //   $cust = $o['Customer'][0];
      // }else{
      //   $cust = $o['Customer'];
      // }

      try{
        foreach ($cust as $c) {
          if( array_key_exists('AdditionalAddresses', $c) ){
              $addr = $c['AdditionalAddresses']['Address'];
              if( isset($addr[0]) ){
                foreach($addr as $a){
                  $v = $this->_v65_map;
                  $v['customernumber'] = $c['CustomerNo'];
                  $v['lookupemail'] = $c['Email'];
                  $v = $this->_parseV65Address($v, $a);
                  array_push($vs, $v);
                }

              }else{
                $v = $this->_v65_map;
                $v['customernumber'] = $c['CustomerNo'];
                $v['lookupemail'] = $c['Email'];
                $v = $this->_parseV65Address($v, $addr);
                array_push($vs, $v);

              }
          }
        }
      }catch(Exception $e){
        echo("CUSTOMER RECORD ERROR: <br/>");
        print_r($cust);
      }

      return $vs;
    }

  }

?>
