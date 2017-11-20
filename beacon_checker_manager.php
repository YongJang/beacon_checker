<?php
header("Content-Type: application/json; charset=UTF-8");
require './config/config.php';

$db_manager = new DBMaria();
$db_manager->checkDBTableSet();

$detectBeaconJSON = json_decode($_POST["beaconData"]);
$db_manager->detectBeacon($detectBeaconJSON);

?>

<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
  <title>Beacon Checker Manager</title>
</head>

</html>
