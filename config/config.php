<?php

ini_set("log_errors", 1);
ini_set("error_log", "./beaconchecker-error.log");
error_log("LOG : START");


if(version_compare(PHP_VERSION, '5.4.0', '<')) {
	@error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);
} else {
	@error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING ^ E_STRICT);
}


require 'DB.class.php';
require 'DBMaria.class.php';



?>
