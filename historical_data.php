<?php
header('Access-Control-Allow-Origin: *');
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
//$ALLOWED_RANGE = array('1H','3H','6H','12H','24H','7D','28D','365D');

$DIVIDERS = array('1H' => 1,'3H' => 3,'6H' => 6,'12H' => 12,'24H' => 30);
$ROWS_TO_READ = array('1H' => 360,'3H' => 1080,'6H' => 2160,'12H' => 4320,'24H' => 8640);


$TYPE = $_GET['TYPE'];
$DATA = $_GET['DATA'];
$RANGE = $_GET['RANGE'];


//$TSTART = $_GET['TSTART'];
//$TSTOP = $_GET['TSTOP'];
//$STEPS = $_GET['STEPS'];



if($TYPE=='RANGE'){

    //unlink('/tmp/data.csv'); // delete old data.csv file if there is one - MySQL/MariaDB can't override an existing file for securety resons

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


    $sql .= "FROM sensorDB.sensorTB ORDER BY time DESC LIMIT {$ROWS_TO_READ[$RANGE]};"; // Add "WHERE XXXX IS NOT NULL" statement just before "ORDER BY"

}

//######################################################################
elseif($TYPE=='INTERVAL'){

    echo "Interval mode - NOT IMPLAMENTED";


}


else
    echo "Unexpected query";
//####################################################################


if($q = $conn->query($sql)){

    $CO2 = 0;
    $TEMP = 0;
    $HUM = 0;
    $J = 0;

    while($r = $q->fetch_assoc()){

        $CO2 = $CO2 + $r['co2'];
        $TEMP = $TEMP + $r['temp'];
        $HUM = $HUM + $r['hum'];
        $J++;
        if($J == $DIVIDERS[$RANGE]){

            $CO2 = round($CO2 / $DIVIDERS[$RANGE], 0);
            $TEMP = round($TEMP / $DIVIDERS[$RANGE], 2);
            $HUM = round($HUM / $DIVIDERS[$RANGE], 2);

            echo "{$r['time']},{$CO2},{$TEMP},{$HUM}\n";

            $CO2 = 0;
            $TEMP = 0;
            $HUM = 0;
            $J = 0;
        }
    }

    $q->free();
}

$conn->close();

echo '</response>';

?>
