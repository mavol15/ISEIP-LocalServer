<?php
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

$host = "localhost";
$dbname = "sensorDB";
$username = "AutoUser";
$password = "2imprezsAutoPWD";

//Create connection
$conn = new mysqli($host, $username, $password, $dbmane);
//Check connection
if ($conn->connect_error){
        die("Connection to DB failed; " . $conn->connect_error);
}


$sql = "SELECT sensorTB.co2, ROUND(sensorTB.temp, 1) AS temp, ROUND(sensorTB.hum, 1) AS hum FROM sensorDB.sensorTB WHERE sensorTB.temp AND sensorTB.co2 IS NOT NULL ORDER BY time DESC LIMIT 1;";


if($q = $conn->query($sql)){

	if($r = $q->fetch_assoc()){

		$co2 = $r['co2'];
		$temp = $r['temp'];
		$hum = $r['hum'];
		$q->free();
	}

}

$conn->close();





echo '<response>';
$CO2_getReq = $_GET['CO2_getReq'];
$ReqArray = array('1','2','3','4');

if(in_array($CO2_getReq,$ReqArray))
    echo "{$co2},{$temp},{$hum}";

elseif ($CO2_getReq=='')
    echo 'CO2_getReq is Empty';

else
    echo 'CO2_getReq is unexpected';

echo '</response>';

?>
