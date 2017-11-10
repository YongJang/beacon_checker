<?php
class DB {
  var $db_dns = NULL;
  var $db_port_num = NULL;
  var $db_root_id = NULL;
  var $db_root_pwd = NULL;
  var $db_name = NULL;

  var $cond_operation = array(
		'equal' => '=',
		'more' => '>=',
		'excess' => '>',
		'less' => '<=',
		'below' => '<',
		'notequal' => '<>',
		'notnull' => 'is not null',
		'null' => 'is null',
	);

  function _setDBInfo() {
    print('hello' . '<br>');
    if(file_exists('./DBInitial.php')) {
      print('hi'. '<br>');
      require 'DBInitial.php';
      print($DB_DNS_NAME . '<br>');
      $db_dns = $DB_DNS_NAME;
      $db_port_num = $DB_PORT_NUM;
      $db_root_id = $DB_ROOT_ID;
      $db_root_pwd = $DB_ROOT_PWD;
      $db_name = $DB_NAME;
    }
  }

  function printDBInfo() {
    print('db_dns = ' . $dbdns . '<br>');
  }
}
?>
