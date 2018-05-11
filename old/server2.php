<?php
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';


//####Connect to MySQL/MariaDB ##############################################

$host = "localhost";
$dbname = "sensorDB";
$username = "AutoUser";
$password = "2imprezsAutoPWD";

$conn = new mysqli($host, $username, $password, $dbmane);
//Check for connection errors
if ($conn->connect_error){
        die("Connection to DB failed; " . $conn->connect_error);
}

//###########################################################################


echo "<response>\n";


//$ALLOWED_TYPE = array('RANGE','INTERVAL','CLEAR');
//$ALLOWED_DATA = array('ALL','CO2','TEMP','HUM','PRES','CTH'); // CTH = CO2, TEMP, HUM


$TYPE = $_GET['TYPE'];
$DATA = $_GET['DATA'];
//$TSTART = $_GET['TSTART'];
//$TSTOP = $_GET['TSTOP'];
//$RANGE = $_GET['RANGE'];
//$STEPS = $_GET['STEPS'];



if($TYPE=='RANGE'){

    unlink('/tmp/data.csv'); // delete old data.csv file if there is one - MySQL/MariaDB can't override an existing file for securety resons

    //Create a MySQL/MariaDB SELECT string create CSV file from the requested data

    $sql = "SELECT sensorTB.time, "; // Time should always be in the first column.

    //Select what data to include based on the $DATA value
    if($DATA=='CTH')
        $sql .= "sensorTB.co2, sensorTB.temp, sensorTB.hum ";
    elseif($DATA=='ALL')
        $sql .= "sensorTB.co2, sensorTB.temp, sensorTB.hum, sensorTB.pres ";
    elseif($DATA=='CO2')
        $sql .= "sensorTB.co2 ";
    elseif($DATA=='TEMP')
        $sql .= "sensorTB.temp ";
    elseif($DATA=='HUM')
        $sql .= "sensorTB.hum ";
    else
        echo "Unexpected DATA value";


    $sql .= "FROM sensorDB.sensorTB ORDER BY time DESC LIMIT 360;"; // Add "WHERE XXXX IS NOT NULL" statement just before "ORDER BY"

}


elseif($TYPE=='INTERVAL'){

    echo "Interval mode - NOT IMPLAMENTED";


}



else
    echo "Unexpected query";



if($q = $conn->query($sql)){

    while($r = $q->fetch_assoc()){

            echo "{$r['time']},{$r['co2']},{$r['temp']},{$r['hum']}\n";

    }

$q->free();

}


$conn->close();

//echo $sql;

echo '</response>';

?>
