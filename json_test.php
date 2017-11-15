<?php
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_POST["x"]);

require './config/DBInitial_test.php';   // MariaDB Initial Path.
$db = mysqli_connect($DB_DNS_NAME . ":" . $DB_PORT_NUM, $DB_ROOT_ID, $DB_ROOT_PWD, $DB_NAME);

if($db) {
  echo "connect : 성공<br>";
} else {
  echo "disconnect : 실패<br>";
}

$result = mysqli_query($db, 'SELECT VERSION() as VERSION');
$data = mysqli_fetch_assoc($result);
echo $data['VERSION'];

$result_useBC = mysqli_query($db, 'USE BEACONCHECKER');
$data = mysqli_fetch_assoc($result_useBC);


$string = "INSERT INTO ".(string)$obj->table." VALUES ('".$obj->name."',".$obj->number.");";
$result = mysqli_query($db, $string);
$outp = array();
$outp = mysqli_fetch_row($result);
echo json_encode($outp);
?>
