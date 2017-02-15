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

  class UploadClubMember extends AbstractValidator{

    function __construct($db){
      parent::__construct($db);

      $this->_sql = "
        CREATE TABLE Customers(
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
          ShipMainPhone VARCHAR(50),
          ShipEmail VARCHAR(50),
          IsPickupAtWinery BOOLEAN,
          PickupLocationCode VARCHAR(20),
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
