<?php
class DBMaria extends DB {

  /**
	 * Constructor
	 * @return void
	 */
  function DBMaria($auto_connect = TRUE) {
    $this->_setDBInfo();
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
    $this->printDBInfo();
    $db = mysqli_connect($DB_DNS_NAME . ":" . $DB_PORT_NUM, $DB_ROOT_ID, $DB_ROOT_PWD, $DB_NAME);

    if($db) {
      echo "connect : 성공<br>";
    } else {
      echo "disconnect : 실패<br>";
    }

    $result = mysqli_query($db, 'SELECT VERSION() as VERSION');
    $data = mysqli_fetch_assoc($result);
    echo $data['VERSION'];

  }
}
?>
