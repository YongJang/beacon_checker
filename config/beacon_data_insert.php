<?php

require './config.php';
$db_manager = new DBMaria();
/*
if($handle = fopen("../res/beacon_query.csv", "r") !== FALSE) {
  $out = array();
  while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    for($i=0; $i < $num; $i++) {
        array_push($out, $data[$i]);
    }
  }
  error_log("LOG : ".$out[0][0]);
}
*/

?>
