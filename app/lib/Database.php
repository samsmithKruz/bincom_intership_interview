<?php
/*
   * PDO Database Class
   * Connect to database
   * Create prepared statements
   * Bind values
   * Return rows and results
   */
//   require_once 'config.php';
class Database
{
  private $host = DB_HOST;
  private $user = DB_USER;
  private $pass = DB_PASS;
  private $dbname = DB_NAME;

  private $dbh;
  private $stmt;
  private $error;

  public function __construct()
  {
    // Set DSN
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
    $options = array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    // Create PDO instance
    try {
      $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
    } catch (PDOException $e) {
      $this->error = $e->getMessage();
      echo $this->error;
    }
  }

  // Start Transaction
  public function beginTransaction($isolationLevel = "SERIALIZABLE")
  {
    // set isolation level 
    // SERIALIZABLE, READ COMMITTED, READ UNCOMMITTED, REPEATABLE READ
    $this->dbh->exec("SET TRANSACTION ISOLATION LEVEL $isolationLevel");

    // Start Transaction
    $this->dbh->beginTransaction();
  }
  // Commit Transaction
  public function commitTransaction()
  {
    $this->dbh->commit();
  }
  // Start Transaction
  public function rollbackTransaction()
  {
    $this->dbh->rollback();
  }

  // Prepare statement with query
  public function query($sql)
  {
    $this->stmt = $this->dbh->prepare($sql);
    return $this;
  }

  // Bind values
  public function bind($param, $value, $type = null)
  {
    if (is_null($type)) {
      switch (true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
      }
    }

    $this->stmt->bindValue($param, $value, $type);
    return $this;
  }

  // Execute the prepared statement
  public function execute()
  {
    try {
      $this->stmt->execute();
      return $this;
    } catch (PDOException $e) {
      throw new Exception('Query execution error: ' . $e->getMessage());
    }
  }

  // Get result set as array of objects
  public function resultSet()
  {
    $this->execute();
    $result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
    $this->stmt->closeCursor(); // Close the cursor to allow other queries
    return $result;
  }

  // Get single record as object
  public function single()
  {
    $this->execute();
    $result = $this->stmt->fetch(PDO::FETCH_OBJ);
    $this->stmt->closeCursor(); // Close the cursor to allow other queries
    return $result;
  }

  // Get row count
  public function rowCount()
  {
    return $this->stmt->rowCount();
  }

  // Close Connection
  public function __destruct()
  {
    $this->dbh = null;
  }
}
