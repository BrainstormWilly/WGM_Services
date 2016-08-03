<?php namespace wgm\vin65\models;

  require_once $_ENV['APP_ROOT'] . '/vin65/models/abstract_soap_model.php';
  use wgm\vin65\models\AbstractSoapModel as AbstractSoapModel;

  class UpsertOrder extends AbstractSoapModel{

    private $_rms_map = [
      "BatchID" => '',
      "CashierID" => '',
      "RegisterNumber" => '',
      "StoreID" => ''
    ];

    private $_tender_map = [
      "AmountTendered" => '',
      "CreditCardExpirationMonth" => '',
      "CreditCardExpirationYear" => '',
      "CreditCardName" => '',
      "CreditCardNumber" => '',
      "CreditCardType" => '',
      "GiftCardCode" => '',
      "GiftCardID" => '',
      "GiftCardNumber" => '',
      "GiftCardVendor" => '',
      "PaymentDate" => '',
      "PaymentType" => '',
      "PointsRedeemed" => 0
    ];

    private $_item_map = [
      "CostOfGood" => 0,
      "DepartmentCode" => '',
      "Price" => 0,
      "ProductName" => '',
      "ProductSKU" => '',
      "Quantity" => 0,
      "SalesTax" => 0,
      "ShippingPartner" => '',
      "ShippingService" => '',
      "isNonTaxable" => false
    ];

    function __construct($session){
      $this->_value_map = [
        "AltContactID" => '',
        "AltShippingAddressID" => '',
        "BillingAddress" => '',
        "BillingAddress2" => '',
        "BillingBirthdate" => '',
        "BillingCity" => '',
        "BillingCompany" => '',
        "BillingEmail" => '',
        "BillingFirstName" => '',
        "BillingLastName" => '',
        "BillingPhone" => '',
        "BillingStateCode" => '',
        "BillingZipCode" => '',
        "ContactID" => '',
        "CreditCardExpirationMonth" => '',
        "CreditCardExpirationYear" => '',
        "CreditCardName" => '',
        "CreditCardNumber" => '',
        "CreditCardType" => '', // Visa, MasterCard, AmericanExpress, Discover
        "CustomerNumber" => 0, // not included in spec, but used to get ContactID. Do not include if syncing by BillingEmail
        "GiftMessage" => '',
        "Handling" => 0,
        "OrderDate" => '',
        "OrderItems" => [],
        "OrderNotes" => '',
        "OrderNumber" => 0,
        "OrderType" => '', // AdminPanel, ClubOrder, Facebook, iPad, Mobile, POS, Telemarketing or Website
        "PaymentType" => 'Cash', // Cash, Check, CreditCard
        "PreviousOrderID" => '',
        "PreviousOrderNumber" => 0,
        "RMS" => [],
        "SalesAssociate" => '',
        "SendToFulfillment" => false,
        "ShipDate" => '',
        "Shipping" => 0,
        "ShippingAddress" => '',
        "ShippingAddress2" => '',
        "ShippingAddressID" => '',
        "ShippingBirthdate" => '',
        "ShippingCity" => '',
        "ShippingCompany" => '',
        "ShippingEmail" => '',
        "ShippingFirstName" => '',
        "ShippingLastName" => '',
        "ShippingPhone" => '',
        "ShippingStateCode" => '',
        "ShippingStatus" => '',
        "ShippingZipCode" => '',
        "SubTotal" => 0,
        "Tax" => 0,
        "Tenders" => [],
        "Total" => 0,
        "TransactionType" => '', // Order, Refund
        "WebsiteCode" => '',
        "isPickup" => false
      ];

      parent::__construct($session, 2);
      $this->_values['orders'] = [];

    }

    public function hasCustomerNumber(){
      return isset($this->_values["CustomerNumber"]) && !empty($this->_values["CustomerNumber"]);
    }

    public function getCustomerID(){
      if( $this->hasCustomerNumber() ){
        return $this->_values["CustomerNumber"];
      }
      return $this->_values["BillingEmail"];
    }

    public function addOrderValues($props, $order=NULL){
      if( $order===NULL ){
        $order = [];
      }
      foreach($props as $key => $value){
        if( array_key_exists($key, $this->_value_map) ){
          $order[$key] = $value;
        }
      }
      return $order;
    }

    public function addOrder($order){
      array_push($this->_values['orders'], $order);
    }

    public function addOrderItemValues($props, $order_item=NULL){
      if( $order_item===NULL ){
        $order_item = [];
      }
      foreach($props as $key => $value){
        if( array_key_exists($key, $this->_item_map) ){
          $order_item[$key] = $value;
        }
      }
      return $order_item;
    }

    public function addOrderItem($order, $order_item){
      if( !isset($order['OrderItems']) ){
        $order['OrderItems'] = [];
      }
      array_push($order['OrderItems'], $order_item);
    }

    // public function callService($values=NULL){
    //   parent::callService();
    //   try{
    //     $client = new \SoapClient($_ENV['V65_V2_CONTACT_SERVICE']);
    //     $result = $client->upsertShippingAddress($this->_values);
    //     // print_r($this->_values);
    //     if( is_soap_fault($result) ){
    //       $this->_error = "SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})";
    //     }elseif(empty($result->results[0]->isSuccessful)){
    //       $this->_error = $result->results[0]->message;
    //     }else{
    //       $this->_result = $result->results[0]->internalKeyCode ;
    //     }
    //   }catch(Exception $e){
    //     $this->_error = $e->message;
    //   }
    // }

  }

?>
