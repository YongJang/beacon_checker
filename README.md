# beacon_checker
[![PHP](https://img.shields.io/badge/PHP-5.6-blue.svg)](http://windows.php.net/download#php-5.6)
[![MariaDB](https://img.shields.io/badge/MariaDB-10.2-green.svg)](https://mariadb.org/download/)
[![jQuery-ui](https://img.shields.io/badge/jQuery_ui-1.12.1-red.svg)](https://jqueryui.com/)

A beacon checker in Hyundai Elevator's office building.
The program has been tested under the environment above.


## Initial Setting
When you first clone this repository, please update the file 'beacon_checker/config/DBInitial.php'.

```
<?php
$DB_DNS_NAME = "YOUR_DNS";
$DB_PORT_NUM = "DB_PORT_NUMBER";
$DB_ROOT_ID = "DB_ID";
$DB_ROOT_PWD = "DB_PASSWORD";
$DB_NAME = "DB_NAME";
?>
```
