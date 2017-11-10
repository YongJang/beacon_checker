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
}
?>
