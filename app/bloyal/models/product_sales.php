<?php namespace wgm\bloyal\models;

  use \SimpleXMLElement as SimpleXMLElement;
  use \DOMDocument as DOMDocument;

  class ProductSales{

    private $_values;

    private $_transaction_map = [
      'DeviceKey' => '',
      'TransNumber' => 0,
      'BatchNumber' => '',
      'AccountNumber' => '',
      'OrderChannel' => '', // WebStore | Kiosk | Club | Phone | POS | Event | Other | OutsideSales | Mobile | SocialNetwork | Wholesale
      'Comment' => '',
      'Created' => '', // mm/dd/yyyy
      'Company' => '',
      'FirstName' => '',
      'LastName' => '',
      'Address1' => '',
      'Address2' => '',
      'City' => '',
      'Zip' => '',
      'State' => '',
      'Country' => '',
      'Products' => [],
      'Packages' => [],
      'Payments' => []
    ];

    private $_product_map = [
      'LookupCode' => '',
      'ProductPrice' => 0,
      'ProductWeight' => 0,
      'ProductDiscount' => 0,
      'ProductDiscountReason' => '',
      'ProductQuantity' => 0,
      'ProductPackageNum' => 0,
      'ProductTaxes' => []
    ];

    private $_product_tax_map = [
      'ProductTaxCode' => '',
      'ProductTaxRate' => 0,
      'ProductTaxAmount' => 0
    ];

    private $_package_map = [
      'PackageNum' => 0,
      'PackageCompany' => '',
      'PackageFirstName' => '',
      'PackageLastName' => '',
      'PackageAddress1' => '',
      'PackageAddress2' => '',
      'PackageCity' => '',
      'PackageZip' => '',
      'PackageState' => '',
      'PackageCountry' => '',
      'PackagePickupLocationCode' => '',
      'PackageShippingCarrierName' => '',
      'PackageShippingServiceCode' => '',
      'PackageShippingCharge' => 0,
      'PackageShippingDiscount' => 0,
      'PackageShippingDiscountReason' => '',
      'PackageShippingDate' => '', // mm/dd/yyyy
      'PackageTaxes' => []
    ];

    private $_package_tax_map = [
      'PackageShippingTaxCode' => '',
      'PackageShippingTaxRate' => 0,
      'PackageShippingTaxAmount' => 0
    ];

    private $_payment_map = [
      'TenderCode' => '',
      'TenderAmount' => '',
    ];

    function __construct(){
      $this->_values = new SimpleXMLElement("<Transactions/>");
    }


    // public function addTransaction($transaction){
    //   $t = [];
    //   $t['Transaction'] = $transaction;
    //   array_push( $this->_values, $t );
    //   $this->_values->addChild("Transaction", $transaction);
    // }

    public function addTransactionValues($values, $transaction=NULL){
      if( $transaction===NULL ){
        $transaction = $this->_values->addChild("Transaction", '');
        $transaction->addChild("Packages", '');
        $transaction->addChild("Payments", '');
        $transaction->addChild("Products", '');
      }
      foreach($values as $key => $value){
        if( array_key_exists($key, $this->_transaction_map) ){
          $transaction->addChild($key, $value);
        }
      }
      return $transaction;
    }

    public function addPackageValues($values, $transaction, $pkg=NULL){

      if( $pkg===NULL ){
        $pkg = $transaction->Packages->addChild("Package", '');
        $pkg->addChild("PackageTaxes", '');
      }
      foreach($values as $key => $value){
        if( array_key_exists($key, $this->_package_map) ){
          $pkg->addChild($key, $value);
        }
      }
      return $pkg;
    }

    public function addPackageTaxValues($values, $pkg, $tax){
      if( $tax===NULL ){
        $tax = $pkg->PackageTaxes->addChild("PackageTax", '');
      }
      foreach($values as $key => $value){
        if( array_key_exists($key, $this->_package_tax_map) ){
          $tax->addChild($key, $value);
        }
      }
      return $tax;
    }

    public function addPaymentValues($values, $transaction, $payment=NULL){
      if( $payment===NULL ){
        $payment = $transaction->Payments->addChild("Payment", '');
      }
      foreach($values as $key => $value){
        if( array_key_exists($key, $this->_payment_map) ){
          $payment->addChild($key, $value);
        }
      }
      return $payment;
    }

    public function addProductValues($values, $transaction, $product=NULL){
      if( $product===NULL ){
        $product = $transaction->Products->addChild("Product", '');
        $product->addChild("ProductTaxes", '');
      }
      foreach($values as $key => $value){
        if( array_key_exists($key, $this->_product_map) ){
          $product->addChild($key, $value);
        }
      }
      return $product;
    }

    public function addProductTaxValues($values, $product, $tax=NULL){
      if( $tax===NULL ){
        $tax = $product->ProductTaxes->addChild("ProductTax", '');
      }
      foreach($values as $key => $value){
        if( array_key_exists($key, $this->_product_tax_map) ){
          $tax->addChild($key, $value);
        }
      }
      return $tax;
    }



    // public function addPackage($value, $transaction){
    //   if( !isset($transaction->Packages) ){
    //     $transaction['Packages'] = [];
    //   }
    //   $p = [];
    //   $p['Package'] = $value;
    //   array_push( $transaction['Packages'], $p );
    //   return $transaction;
    // }

    // public function addPackageTax($value, $pkg){
    //   if( !isset($pkg['PackageTaxes']) ){
    //     $pkg['PackageTaxes'] = [];
    //   }
    //   $p = [];
    //   $p['PackageTax'] = $value;
    //   array_push( $pkg['PackageTaxes'], $p );
    //   return $pkg;
    // }

    // public function addPayment($value, $transaction){
    //   if( !isset($transaction['Payments']) ){
    //     $transaction['Payments'] = [];
    //   }
    //   $p = [];
    //   $p['Payment'] = $value;
    //   array_push( $transaction['Payments'], $p );
    //   return $transaction;
    // }

    // public function addProduct($value, $transaction){
    //   if( !isset($transaction['Products']) ){
    //     $transaction['Products'] = [];
    //   }
    //   $p = [];
    //   $p['Product'] = $value;
    //   array_push( $transaction['Products'], $p );
    //   return $transaction;
    // }

    // public function addProductTax($value, $prd){
    //   if( !isset($prd['ProductTaxes']) ){
    //     $prd['ProductTaxes'] = [];
    //   }
    //   $p = [];
    //   $p['ProductTax'] = $value;
    //   array_push( $prd['ProductTaxes'], $p );
    //   return $prd;
    // }



    public function getValues(){
      $v = $this->_values;
      return $v;
    }

    public function getValuesToXml(){
      $doc = new DOMDocument();
      $doc->formatOutput = TRUE;
      $doc->loadXML($this->_values->asXML());

      return $doc->saveXML();
    }

  }

?>
