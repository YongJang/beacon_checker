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

    } else {
      error_log("ERROR : _connect() can not connect DBMS");
      die("Connection error : ".mysqli_connect_errno());
    }

    $result = mysqli_query($this->db, 'SELECT VERSION() as VERSION');
    $data = mysqli_fetch_assoc($result);
    error_log($data['VERSION']);

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
    mysqli_select_db($this->db, 'BEACONCHECKER');

    /**
    *  Create beacondetect table if it is not exist
    */
    $result_exist = mysqli_query("SHOW TABLES LIKE 'beacondetect'");
    $data = mysqli_fetch_array($result_exist);
    if($data) { // if table is exist

    } else {  // if table is not exist
      $result_createTable = mysqli_query($this->db, 'CREATE TABLE beacondetect (
        beacon_no INT not NULL PRIMARY KEY,
        detect_cnt BIGINT unsigned,
        lastdetect DATETIME
      )');
      $data = mysqli_fetch_array($result_createTable);
    }

    /**
    *  Create beacon table if it is not exist
    */
    $result_exist = mysqli_query("SHOW TABLES LIKE 'beacon'");
    $data = mysqli_fetch_array($result_exist);
    if($data) { // if table is exist

    } else {  // if table is not exist
      $result_createTable = mysqli_query($this->db, 'CREATE TABLE beacon (
        beacon_no INT not NULL PRIMARY KEY,
        building_name varchar(200) not NULL,
        floor_num varchar(20) not NULL,
        file_name VARCHAR(200) not NULL,
        pos_x INT not NULL,
        pos_y INT not NULL
      )');
      $data = mysqli_fetch_array($result_createTable);
    }
  }

  function detectBeacon($data) {
    $query = "SELECT * FROM beacondetect WHERE beacon_no = ".$data->beacon_no;
    $result = mysqli_query($this->db, $query);
    $row_cnt = mysqli_num_rows($result);
    if(!$row_cnt) {
      $this->_insertBeaconDetect($data);
    } else {
      $query = "UPDATE beacondetect SET detect_cnt = detect_cnt + 1, lastdetect = NOW() WHERE beacon_no = ".$data->beacon_no;
      $result = mysqli_query($this->db, $query);
      $row = mysqli_num_rows($result);
      if($row) {
        error_log("ERROR : detectBeacon() can not update query");
      }
    }

  }

  function _insertBeaconDetect($data) {
    $query = sprintf(
      "INSERT INTO %s VALUES ('%s', 1, NOW())",
      $data->table,
      $data->beacon_no
    );
    $result = mysqli_query($this->db, $query);

    if(!$result) {
      error_log("ERROR : _insertBeaconDetect()");
    }
  }

  function getBeaconDetect($pivot="beacon_no", $order="ASC", $beacon_no=NULL) {
    if(!$beacon_no) {
      $query = "SELECT * FROM beacondetect ORDER BY ".$pivot." ".$order;
    } else {
      $query = "SELECT * FROM beacondetect WHERE beacon_no = ".$beacon_no." ORDER BY ".$pivot." ".$order;
    }
    $result = mysqli_query($this->db, $query);
    $out = array();

    while($row = mysqli_fetch_array($result)) {
      array_push($out, $row);
    }
    if(count($out) == 0) {
      error_log("ERROR : getBeaconDetect() No element in the beacondetect table.");
    } else {
      echo $out['beacon_no'];
      return $out;
    }
  }

  function getBeaconInfo($beacon_no=NULL, $output=NULL) {
    if(!$beacon_no && !$output) {
      $query = "SELECT * FROM beacon";
    } else {
      $query = "SELECT ";
      if($output) {
        $query = $query.$output." ";
      } else {
        $query = $query." * ";
      }
      $query = $query." FROM beacon";
      if($beacon_no) {
        $query = $query." WHERE beacon_no = ".$beacon_no;
      }
    }

    $result = mysqli_query($this->db, $query);
    $out = array();

    while($row = mysqli_fetch_array($result)) {
      array_push($out, $row);
    }
    return $out;
  }
}
?>
