<?php
class DBMaria extends DB {
  private $db = NULL;

  /**
	 * Constructor
	 * @return void
	 */
  function DBMaria($auto_connect = TRUE) {
    if($auto_connect) $this->_connect();
  }

  /**
	 * Create an instance of this class
	 * @return DBMaria return DBMysql object instance
	 */
  function create() {
    return new DBMaria;
  }

  function _connect($connection) {
    require 'DBInitial_test.php';   // MariaDB Initial Path.
    $this->db = mysqli_connect($DB_DNS_NAME . ":" . $DB_PORT_NUM, $DB_ROOT_ID, $DB_ROOT_PWD, $DB_NAME);

    if($this->db) {
      echo "connect : 성공<br>";
    } else {
      echo "disconnect : 실패<br>";
    }

    $result = mysqli_query($this->db, 'SELECT VERSION() as VERSION');
    $data = mysqli_fetch_assoc($result);
    echo $data['VERSION'];

  }

  function checkDBTableSet() {
    /**
    *  Create Beaconchecker database if it is not exist
    */
    $result_createDB = mysqli_query($this->db, 'CREATE DATABASE IF NOT EXISTS BEACONCHECKER');
    $data = mysqli_fetch_assoc($result_createDB);

    /**
    *  Use Beaconchecker database
    */
    $result_useBC = mysqli_query($this->db, 'USE BEACONCHECKER');
    $data = mysqli_fetch_assoc($result_useBC);

    /**
    *  create beacondetect table if it is not exist
    */
    $result_exist = mysqli_query("SHOW TABLES LIKE 'beacondetect'");
    $data = NULL;
    $data = mysqli_fetch_array($result_exist);
    if($data) { // if table is exist
      echo "<br>table is exist<br>";
    } else {  // if table is not exist
      echo "<br>table is not exist<br>";
      $result_createTable = mysqli_query($this->db, 'CREATE TABLE BEACONDETECT (
        beacon_no INT not NULL PRIMARY KEY,
        detect_cnt BIGINT unsigned,
        lastdetect DATETIME
      )');
      $data = mysqli_fetch_array($result_createTable);
    }
  }

  function detectBeacon($data) {
    $query = "SELECT * FROM beacondetect WHERE beacon_no = ".$data->beacon_no.";";
    $result = mysqli_query($this->db, $query);
    $row_cnt = mysqli_num_rows($result);
    if (!$row_cnt) {
      error_log("TEST : inner if statement".$row_cnt);
      $this->_insertBeaconDetect($data);
    } else {
      error_log("TEST : inner else statement".$row_cnt);
    }

  }

  function _insertBeaconDetect($data) {
    $query = "INSERT INTO ".$data->table." VALUES ('".$data->beacon_no."', 1, NOW());";
    $result = mysqli_query($this->db, $query);

    if(!$result) {
      error_log("ERROR : _insertBeaconDetect()");
    }
  }

  function getBeaconDetect() {
    $query = "SELECT * from beacondetect;";
    $result = mysqli_query($this->db, $query);
    $row = mysqli_fetch_assoc($result);
    return $row;
  }


}
?>
