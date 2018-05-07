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

$TYPE = $_GET['TYPE'];
$TSTART = $_GET['TSTART'];
$TSTOP = $_GET['TSTOP'];
$RANGE = $_GET['RANGE'];
$STEPS = $_GET['STEPS'];


if($TYPE=='LIVE')
    echo "{$co2},{$temp},{$hum}";

elseif($TYPE=='RANGE'){
    unlink('data/data.csv');

    //MYSQL

    echo "Ready";

}


elseif($TYPE=='INTERVAL')
    echo "Interval mode";



else
    echo 'CO2_getReq is unexpected';
    unlink('data/data.csv');

echo '</response>';

?>
