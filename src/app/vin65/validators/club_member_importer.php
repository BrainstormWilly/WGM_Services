<?php namespace wgm\vin65\validators;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/abstract_validator.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/column_count.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/column_case.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/no_nulls.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/valid_email.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/is_integer.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/valid_zip.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/valid_cc_type.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/valid_cc_number.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/has_some_customer_id.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/valid_cc_expiry_month.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/valid_cc_expiry_year.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/yes_nos.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/club_member_pu_location.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/valid_birthdate.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/valid_club_name.php';

  use \wgm\vin65\validators\tests\ColumnCount as ColumnCount;
  use \wgm\vin65\validators\tests\ColumnCase as ColumnCase;
  use \wgm\vin65\validators\tests\NoNulls as NoNulls;
  use \wgm\vin65\validators\tests\ValidEmail as ValidEmail;
  use \wgm\vin65\validators\tests\IsInteger as IsInteger;
  use \wgm\vin65\validators\tests\ValidZip as ValidZip;
  use \wgm\vin65\validators\tests\ValidCCType as ValidCCType;
  use \wgm\vin65\validators\tests\ValidCCNumber as ValidCCNumber;
  use \wgm\vin65\validators\tests\HasSomeCustomerID as HasSomeCustomerID;
  use \wgm\vin65\validators\tests\ValidCCExpiryMonth as ValidCCExpiryMonth;
  use \wgm\vin65\validators\tests\ValidCCExpiryYear as ValidCCExpiryYear;
  use \wgm\vin65\validators\tests\YesNos as YesNos;
  use \wgm\vin65\validators\tests\ClubMemberPULocation as ClubMemberPULocation;
  use \wgm\vin65\validators\tests\ValidBirthdate as ValidBirthdate;
  use \wgm\vin65\validators\tests\ValidClubName as ValidClubName;

  // foreach (scandir(dirname(__FILE__)) as $filename) {
  //   $path = dirname(__FILE__) . '/' . $filename;
  //   if (is_file($path)) {
  //       require $path;
  //   }
  // }

  class ClubMemberImporter extends AbstractValidator{

    function __construct($db){
      parent::__construct($db);

      $this->_sql = "
        CREATE TABLE club_members(
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          CustomerNumber INT(6) UNSIGNED,
          BirthDate DATE,
          FirstName VARCHAR(50),
          LastName VARCHAR(50),
          Company VARCHAR(50),
          Address VARCHAR(50),
          Address2 VARCHAR(50),
          City VARCHAR(50),
          StateCode CHAR(2),
          ZipCode VARCHAR(20),
          MainPhone VARCHAR(20),
          Cell VARCHAR(20),
          Email VARCHAR(50) NOT NULL,
          Username VARCHAR(50),
          Password VARCHAR(20),
          CreditCardType VARCHAR(20),
          CreditCardNumber VARCHAR(20),
          CreditCardExpiryMo TINYINT(2),
          CreditCardExpiryYr SMALLINT(4),
          NameOnCard VARCHAR(20),
          ClubName VARCHAR(50),
          SignupDate DATE,
          OnHoldStartDate DATE,
          OnHoldUntilDate DATE,
          CancelDate DATE,
          IsGift BOOLEAN,
          GiftMessage TEXT,
          ClubNotes TEXT,
          ShipNickName VARCHAR(50),
          ShipBirthDate DATE,
          ShipFirstName VARCHAR(50),
          ShipLastName VARCHAR(50),
          ShipCompany VARCHAR(50),
          ShipAddress VARCHAR(50),
          ShipAddress2 VARCHAR(50),
          ShipCity VARCHAR(50),
          ShipZipCode VARCHAR(50),
          ShipStateCode VARCHAR(2),
          ShipMainPhone VARCHAR(50),
          ShipEmail VARCHAR(50),
          IsPickupAtWinery BOOLEAN,
          PickupLocationCode VARCHAR(20)
        )";

      $this->_tables["club_members"] = [
        "CustomerNumber",
        "BirthDate",
        "FirstName",
        "LastName",
        "Company",
        "Address",
        "Address2",
        "City",
        "StateCode",
        "ZipCode",
        "MainPhone",
        "Cell",
        "Email",
        "Username",
        "Password",
        "CreditCardType",
        "CreditCardNumber",
        "CreditCardExpiryMo",
        "CreditCardExpiryYr",
        "NameOnCard",
        "ClubName",
        "SignupDate",
        "OnHoldStartDate",
        "OnHoldUntilDate",
        "CancelDate",
        "IsGift",
        "GiftMessage",
        "ClubNotes",
        "ShipNickName",
        "ShipBirthDate",
        "ShipFirstName",
        "ShipLastName",
        "ShipCompany",
        "ShipAddress",
        "ShipAddress2",
        "ShipCity",
        "ShipStateCode",
        "ShipZipCode",
        "ShipMainPhone",
        "ShipEmail",
        "IsPickupAtWinery",
        "PickupLocationCode"
      ];

    }

    public function runTests(){
      $this->_tests["columncount"] =  new ColumnCount();
      $this->_tests["columncase"] =  new ColumnCase();
      $this->_tests["nonulls"] =  new NoNulls();
      $this->_tests["validemail"] =  new ValidEmail();
      $this->_tests["isinteger"] =  new IsInteger();
      $this->_tests["validzip"] =  new ValidZip();
      $this->_tests["validcctype"] =  new ValidCCType();
      $this->_tests["validccnumber"] =  new ValidCCNumber();
      $this->_tests["hassomecustomerid"] =  new HasSomeCustomerID();
      $this->_tests["validccexpirymonth"] =  new ValidCCExpiryMonth();
      $this->_tests["validccexpiryyear"] =  new ValidCCExpiryYear();
      $this->_tests["yesnos"] =  new YesNos();
      $this->_tests["clubmemberpulocation"] =  new ClubMemberPULocation();
      $this->_tests["validbirthdate"] =  new ValidBirthdate();
      $this->_tests["validclubname"] =  new ValidClubName();

      $params = [
        "table" => count($this->_tables["club_members"]),
        "file" => count($this->_reader->getHeaders())
      ];
      $test = $this->_tests["columncount"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        "table" => $this->_tables["club_members"],
        "file" => $this->_reader->getHeaders()
      ];
      $test = $this->_tests["columncase"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'customer_number_index' => array_search("CustomerNumber", $this->_reader->getHeaders()),
        'email_index' => array_search("Email", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["hassomecustomerid"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "Email",
        'index' => array_search("Email", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["validemail"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "CustomerNumber",
        'index' => array_search("CustomerNumber", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["isinteger"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "ZipCode",
        'index' => array_search("ZipCode", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["validzip"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "CreditCardType",
        'index' => array_search("CreditCardType", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["validcctype"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "CreditCardNumber",
        'cc_number_index' => array_search("CreditCardNumber", $this->_reader->getHeaders()),
        'cc_type_index' => array_search("CreditCardType", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["validccnumber"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "CreditCardExpiryMo",
        'index' => array_search("CreditCardExpiryMo", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["nonulls"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "CreditCardExpiryMo",
        'index' => array_search("CreditCardExpiryMo", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["validccexpirymonth"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "CreditCardExpiryYr",
        'index' => array_search("CreditCardExpiryYr", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["nonulls"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "CreditCardExpiryYr",
        'index' => array_search("CreditCardExpiryYr", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["validccexpiryyear"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $this->setState( self::STATE_TESTS_COMPLETE );

      $params = [
        'column' => "IsPickupAtWinery",
        'index' => array_search("IsPickupAtWinery", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["yesnos"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "PickupLocationCode",
        'pu_location_index' => array_search("PickupLocationCode", $this->_reader->getHeaders()),
        'pu_at_winery_index' => array_search("IsPickupAtWinery", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["clubmemberpulocation"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "BirthDate",
        'index' => array_search("BirthDate", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["nonulls"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "BirthDate",
        'index' => array_search("BirthDate", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["validbirthdate"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "ClubName",
        'index' => array_search("ClubName", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["validclubname"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $this->setState( self::STATE_TESTS_COMPLETE );
    }
  }
