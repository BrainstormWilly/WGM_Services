<?php namespace wgm\vin65\validators;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/abstract_validator.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/result_model.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/column_count.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/column_case.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/no_nulls.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/is_integer.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/db/valid_zip.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/db/has_some_customer_id.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/db/valid_birthdate.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/db/valid_email.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/db/yes_nos.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/db/pickup_dates_and_locations.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/db/ship_pickup_status.php';

  use \wgm\vin65\validators\tests\ColumnCount as ColumnCount;
  use \wgm\vin65\validators\tests\ColumnCase as ColumnCase;
  use \wgm\vin65\validators\tests\NoNulls as NoNulls;
  use \wgm\vin65\validators\tests\IsInteger as IsInteger;
  use \wgm\vin65\validators\tests\db\ValidZip as ValidZip;
  use \wgm\vin65\validators\tests\db\HasSomeCustomerID as HasSomeCustomerID;
  use \wgm\vin65\validators\tests\db\ValidBirthdate as ValidBirthdate;
  use \wgm\vin65\validators\tests\db\ValidEmail as ValidEmail;
  use \wgm\vin65\validators\tests\db\YesNos as YesNos;
  use \wgm\vin65\validators\tests\db\PickupDatesAndLocations as PickupDatesAndLocations;
  use \wgm\vin65\validators\tests\db\ShipPickupStatus as ShipPickupStatus;

  class OrderHistoryImporter extends AbstractValidator{

    private $_insert_order_sql = "";
    private $_insert_item_sql = "";
    private $_fields = 51;

    function __construct($db){
      parent::__construct($db);

      $this->_sql["orders"] = "CREATE TABLE orders(
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          OrderNumber INT(6) UNSIGNED,
          OrderDate DATE,
          OrderType VARCHAR(50),
          BillCustomerNumber INT(6) UNSIGNED,
          BillBirthDate DATE,
          BillFirstName VARCHAR(50),
          BillLastName VARCHAR(50),
          BillCompany VARCHAR(50),
          BillAddress VARCHAR(120),
          BillAddress2 VARCHAR(120),
          BillCity VARCHAR(50),
          BillStateCode CHAR(2),
          BillZipCode VARCHAR(20),
          BillPhone VARCHAR(20),
          BillEmail VARCHAR(120),
          isPickup VARCHAR(4),
          PickupDate DATE,
          PickupLocationCode VARCHAR(20),
          ShipBirthDate DATE,
          ShipFirstName VARCHAR(50),
          ShipLastName VARCHAR(50),
          ShipCompany VARCHAR(50),
          ShipAddress VARCHAR(50),
          ShipAddress2 VARCHAR(50),
          ShipCity VARCHAR(50),
          ShipStateCode VARCHAR(2),
          ShipZipCode VARCHAR(50),
          ShipPhone VARCHAR(50),
          ShipEmail VARCHAR(50),
          ShippingStatus VARCHAR(50),
          ShipDate DATE,
          Carrier VARCHAR(50),
          TrackingNumber VARCHAR(120),
          SourceCode VARCHAR(50),
          PaymentType VARCHAR(50),
          CreditCardType VARCHAR(20),
          CreditCardNumber VARCHAR(20),
          NameOnCard VARCHAR(50),
          ExpiryMonth TINYINT(2),
          ExpiryYear SMALLINT(4),
          OrderNotes TEXT,
          GiftMessage TEXT,
          OrderSubtotal DECIMAL(10,2),
          OrderShipping DECIMAL(10,2),
          OrderHandling DECIMAL(10,2),
          OrderTax DECIMAL(10,2),
          OrderTotal DECIMAL(10,2)
        )";
      $this->_sql["order_items"] = "CREATE TABLE order_items(
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          OrderNumber INT(6) UNSIGNED,
          ItemSKU VARCHAR(50),
          ItemName VARCHAR(120),
          ItemQuantity INT(6),
          ItemUnitPrice DECIMAL(10,2)
        )";

      $this->_tables["orders"] = [
          "OrderNumber",
          "OrderDate",
          "OrderType",
          "BillCustomerNumber",
          "BillBirthDate",
          "BillFirstName",
          "BillLastName",
          "BillCompany",
          "BillAddress",
          "BillAddress2",
          "BillCity",
          "BillStateCode",
          "BillZipCode",
          "BillPhone",
          "BillEmail",
          "isPickup",
          "PickupDate",
          "PickupLocationCode",
          "ShipBirthDate",
          "ShipFirstName",
          "ShipLastName",
          "ShipCompany",
          "ShipAddress",
          "ShipAddress2",
          "ShipCity",
          "ShipStateCode",
          "ShipZipCode",
          "ShipPhone",
          "ShipEmail",
          "ShippingStatus",
          "ShipDate",
          "Carrier",
          "TrackingNumber",
          "SourceCode",
          "PaymentType",
          "CreditCardType",
          "CreditCardNumber",
          "NameOnCard",
          "ExpiryMonth",
          "ExpiryYear",
          "OrderNotes",
          "GiftMessage",
          "OrderSubtotal",
          "OrderShipping",
          "OrderHandling",
          "OrderTax",
          "OrderTotal"
      ];
      $this->_tables['order_items'] = [
        "OrderNumber",
        "ItemSKU",
        "ItemName",
        "ItemQuantity",
        "ItemUnitPrice"
      ];

      $order_values = [];
      foreach ($this->_tables["orders"] as $col) {
        array_push($order_values, "?");
      }

      $this->_insert_order_sql = "insert into orders (" . implode(",", $this->_tables['orders']) . ") VALUES (" . implode(",", $order_values) . ")";
      $this->_insert_item_sql = "insert into order_items (" . implode(",", $this->_tables['order_items']) . ") VALUES (?,?,?,?,?)";
    }

    public function runTests(){
      $this->_tests["columncount"] =  new ColumnCount();
      $this->_tests["columncase"] =  new ColumnCase();
      $this->_tests["nonulls"] =  new NoNulls();
      $this->_tests["validemail"] =  new ValidEmail($this->_db);
      $this->_tests["isinteger"] =  new IsInteger();
      $this->_tests["validzip"] =  new ValidZip($this->_db);
      $this->_tests["hassomecustomerid"] =  new HasSomeCustomerID($this->_db);
      $this->_tests["validbirthdate"] =  new ValidBirthdate($this->_db);
      $this->_tests["yesnos"] =  new YesNos($this->_db);
      $this->_tests["pickupdatesandlocations"] =  new PickupDatesAndLocations($this->_db);
      $this->_tests["shippickupstatus"] =  new ShipPickupStatus($this->_db);

      while( $this->_reader->hasNextRecord() ){
        $rec = $this->_reader->getNextRecord();
        $stm_order_params = [];
        $stm_item_params = [];
        $order_typs = "";
        $item_typs = "";
        $order_vars = [];
        $recs = count($rec);
        for($i=0; $i<$recs; $i++){
          // guard against blank columns
          if( $i<$this->_fields ){
            switch ($i) {
              case 0:
                $item_typs .= "i";
              case 3:
              case 38:
              case 39:
                $order_typs .= "i";
                break;
              case 42:
              case 43:
              case 44:
              case 45:
              case 46:
                $order_typs .= "d";
                break;
              case 47:
              case 48:
                $item_typs .= "s";
                break;
              case 49:
                $item_typs .= "i";
                break;
              case 50:
                $item_typs .= "d";
                break;
              default:
                $order_typs .= "s";
                break;
            }
          }else{
            break;
          }
        }

        $stm_order_params[] = & $order_typs;
        $stm_item_params[] = & $item_typs;

        for($i=0; $i<$recs; $i++){
          // guard against blank columns
          if( $i<$this->_fields ){
            if( $rec[$i] !== NULL ){
              switch ($i) {
                case 4:
                case 18:
                  $rec[$i] = $this->dateFormatter($rec[$i], true);
                  break;
                case 1:
                case 16:
                case 18:
                case 30:
                  $rec[$i] = $this->dateFormatter($rec[$i]);
              }
            }
            if( $i==0 ){
              $stm_order_params[] = & $rec[$i];
              $stm_item_params[] = & $rec[$i];
            }elseif( $i>46) {
              $stm_item_params[] = & $rec[$i];
            }else{
              $stm_order_params[] = & $rec[$i];
            }
          }else{
            break;
          }
        }

        $order_number_matches = $this->_db->query("SELECT id FROM orders WHERE OrderNumber = " . $rec[0]);
        if( $order_number_matches->num_rows==0 ){
          $stm = $this->_db->prepare($this->_insert_order_sql);
          call_user_func_array(array($stm, 'bind_param'), $stm_order_params);

          if( !$stm->execute() ){
            array_push( $this->_results, ResultModel::CreateError("DB Insert Orders" . $stm->error, $rec) );
            break;
          }
        }

        $stm = $this->_db->prepare($this->_insert_item_sql);
        call_user_func_array(array($stm, 'bind_param'), $stm_item_params);

        if( !$stm->execute() ){
          array_push( $this->_results, ResultModel::CreateError("DB Insert Order Items" . $stm->error, $rec) );
          break;
        }
      }

      if( count($this->_results) == 0 ){
        $params = [
          "table" => $this->_fields,
          "file" => count($this->_reader->getHeaders())
        ];
        $test = $this->_tests["columncount"];
        $test->runTest($params);
        array_push( $this->_results, $test->getResult() );

        foreach($this->_tables as $key => $val){
          $params = [
            "table_name" => $key,
            "table" => $val,
            "file" => $this->_reader->getHeaders()
          ];
          $test = $this->_tests["columncase"];
          $test->runTest($params);
          array_push( $this->_results, $test->getResult() );
        }

        $params = [
          "customer_number" => 'BillCustomerNumber',
          "customer_email" => "BillEmail",
          "table_name" => "orders"
        ];
        $test = $this->_tests["hassomecustomerid"];
        $test->runTest($params);
        array_push( $this->_results, $test->getResult() );

        $params = [
          "column" => 'BillBirthDate',
          "table_name" => "orders"
        ];
        $test = $this->_tests["validbirthdate"];
        $test->runTest($params);
        array_push( $this->_results, $test->getResult() );

        $params = [
          "column" => 'BillZipCode',
          "table_name" => "orders"
        ];
        $test = $this->_tests["validzip"];
        $test->runTest($params);
        array_push( $this->_results, $test->getResult() );

        $params = [
          "column" => 'BillEmail',
          "table_name" => "orders"
        ];
        $test = $this->_tests["validemail"];
        $test->runTest($params);
        array_push( $this->_results, $test->getResult() );

        $params = [
          "column" => 'isPickup',
          "table_name" => "orders"
        ];
        $test = $this->_tests["yesnos"];
        $test->runTest($params);
        array_push( $this->_results, $test->getResult() );

        $params = [
          "ispickup" => 'isPickup',
          "pickuplocation" => 'PickupLocationCode',
          "pickupdate" => 'PickupDate',
          "table_name" => "orders"
        ];
        $test = $this->_tests["pickupdatesandlocations"];
        $test->runTest($params);
        array_push( $this->_results, $test->getResult() );

        $params = [
          "column" => 'ShipBirthDate',
          "table_name" => "orders"
        ];
        $test = $this->_tests["validbirthdate"];
        $test->runTest($params);
        array_push( $this->_results, $test->getResult() );

        $params = [
          "column" => 'ShipZipCode',
          "table_name" => "orders"
        ];
        $test = $this->_tests["validzip"];
        $test->runTest($params);
        array_push( $this->_results, $test->getResult() );

        $params = [
          "column" => 'ShippingStatus',
          "table_name" => "orders"
        ];
        $test = $this->_tests["shippickupstatus"];
        $test->runTest($params);
        array_push( $this->_results, $test->getResult() );

        $params = [
          "subtotal_column" => 'OrderSubtotal',
          "shipping_column" => 'OrderShipping',
          "handling_column" => 'OrderHandling',
          "tax_column" => 'OrderTax',
          "item_quantity_column" => 'ItemQuantity',
          "item_price_column" => 'ItemUnitPrice',
          "table_name" => "orders"
        ];
        $test = $this->_tests["shippickupstatus"];
        $test->runTest($params);
        array_push( $this->_results, $test->getResult() );

      }

      $this->setState( self::STATE_TESTS_COMPLETE );
    }

    // $stm->close();
  }

?>
