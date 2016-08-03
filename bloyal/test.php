<?php

require_once __DIR__ . './../vendor/autoload.php';
require $_ENV['APP_ROOT'] . '/bloyal/models/product_sales.php';

use wgm\bloyal\models\ProductSales as ProductSalesModel;

$model = new ProductSalesModel();

$pkg1 = [
  'TransNumber' => 1000,
  'BatchNumber' => '',
  'AccountNumber' => '2000',
  'OrderChannel' => 'POS', // WebStore | Kiosk | Club | Phone | POS | Event | Other | OutsideSales | Mobile | SocialNetwork | Wholesale
  'Comment' => '',
  'Created' => '12/1/2015', // mm/dd/yyyy
  'Company' => 'Acme, Inc.',
  'FirstName' => 'John',
  'LastName' => 'Doe',
  'Address1' => '123 Main St.',
  'Address2' => 'Suite A',
  'City' => 'AnyTown',
  'Zip' => '90000',
  'State' => 'CA',
  'Country' => 'USA',
  'PackageCompany' => 'Acme, Inc.',
  'PackageFirstName' => 'John',
  'PackageLastName' => 'Doe',
  'PackageAddress1' => '123 Main St.',
  'PackageAddress2' => 'Suite A',
  'PackageCity' => 'AnyTown',
  'PackageZip' => '90000',
  'PackageState' => 'CA',
  'PackageCountry' => 'US',
  'PackagePickupLocationCode' => '',
  'PackageShippingCarrierName' => 'UPS',
  'PackageShippingServiceCode' => '',
  'PackageShippingCharge' => 10,
  'PackageShippingDiscount' => 0,
  'PackageShippingDate' => '12/4/2015', // mm/dd/yyyy
  'LookupCode' => '3000',
  'ProductPrice' => 40,
  'ProductWeight' => .3,
  'ProductDiscount' => 8,
  'ProductDiscountReason' => '',
  'ProductQuantity' => 2,
  'ProductTaxCode' => 'CA',
  'ProductTaxRate' => 8.75,
  'ProductTaxAmount' => 2.8,
  'TenderCode' => 'MC',
  'TenderAmount' => '34.8',
];

$trans = $model->addTransactionValues($pkg1);
$prod = $model->addProductValues($pkg1, $trans);
$pkg = $model->addPackageValues($pkg1, $trans);
$pay = $model->addPaymentValues($pkg1, $trans);
$tax = $model->addProductTaxValues($pkg1, $prod);

print_r( $model->getValuesToXml() );


?>
