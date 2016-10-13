<?php namespace wgm\vin65\validators;

  require_once $_ENV['APP_ROOT'] . '/vin65/validators/abstract_validator.php';

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

      array_push( $this->_tables, "Customers" );

    }



  }

?>
