<?php namespace wgm\vin65\validators;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/abstract_validator.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/column_count.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/column_case.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/no_nulls.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/valid_email.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/is_unique.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/is_integer.php';
  require_once $_ENV['APP_ROOT'] . '/vin65/validators/tests/valid_zip.php';

  use \wgm\vin65\validators\tests\ColumnCount as ColumnCount;
  use \wgm\vin65\validators\tests\ColumnCase as ColumnCase;
  use \wgm\vin65\validators\tests\NoNulls as NoNulls;
  use \wgm\vin65\validators\tests\ValidEmail as ValidEmail;
  use \wgm\vin65\validators\tests\IsUnique as IsUnique;
  use \wgm\vin65\validators\tests\IsInteger as IsInteger;
  use \wgm\vin65\validators\tests\ValidZip as ValidZip;

  // foreach (scandir(dirname(__FILE__)) as $filename) {
  //   $path = dirname(__FILE__) . '/' . $filename;
  //   if (is_file($path)) {
  //       require $path;
  //   }
  // }

  class UploadContact extends AbstractValidator{

    function __construct($db){
      parent::__construct($db);

      $this->_sql = "
        CREATE TABLE Customers(
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          CustomerNumber INT(6) UNSIGNED,
          BirthDate DATETIME,
          FirstName VARCHAR(50),
          LastName VARCHAR(50),
          Company VARCHAR(50),
          Address VARCHAR(50),
          Address2 VARCHAR(50),
          City VARCHAR(50),
          StateCode CHAR(2),
          ZipCode VARCHAR(20),
          CountryCode CHAR(2),
          MainPhone VARCHAR(20),
          Email VARCHAR(50) NOT NULL,
          Username VARCHAR(50),
          Password VARCHAR(20),
          ContactType VARCHAR(50),
          PriceLevel VARCHAR(50),
          SourceCode VARCHAR(50),
          UNIQUE (Email)
        )";

      $this->_tables["Customers"] = [
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
        "CountryCode",
        "MainPhone",
        "Email",
        "Username",
        "Password",
        "ContactType",
        "PriceLevel",
        "SourceCode"
      ];

    }

    public function runTests(){
      $this->_tests["columncount"] =  new ColumnCount();
      $this->_tests["columncase"] =  new ColumnCase();
      $this->_tests["nonulls"] =  new NoNulls();
      $this->_tests["validemail"] =  new ValidEmail();
      $this->_tests["isunique"] =  new IsUnique();
      $this->_tests["isinteger"] =  new IsInteger();
      $this->_tests["validzip"] =  new ValidZip();

      $params = [
        "table" => count($this->_tables["Customers"]),
        "file" => count($this->_reader->getHeaders())
      ];
      $test = $this->_tests["columncount"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        "table" => $this->_tables["Customers"],
        "file" => $this->_reader->getHeaders()
      ];
      $test = $this->_tests["columncase"];
      $test->runTest($params);
      array_push( $this->_results, $test->getResult() );

      $params = [
        'column' => "Email",
        'index' => array_search("Email", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["nonulls"];
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
        'column' => "Email",
        'index' => array_search("Email", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["isunique"];
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
        'column' => "CustomerNumber",
        'index' => array_search("CustomerNumber", $this->_reader->getHeaders()),
        "file" => $this->_reader->getRecords()
      ];
      $test = $this->_tests["isunique"];
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

      $this->setState( self::STATE_TESTS_COMPLETE );
    }



  }

?>
