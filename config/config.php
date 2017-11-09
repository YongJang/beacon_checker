<?php

if(version_compare(PHP_VERSION, '5.4.0', '<')) {
	@error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);
} else {
	@error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING ^ E_STRICT);
}

if(file_exists('./DBInitial.php')) {
  require './DBInitial.php';
}


?>
