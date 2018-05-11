<?php
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

echo '<response>';

$res = shell_exec('connmanctl scan wifi');

$services = shell_exec('connmanctl services');

$data = explode("\n", $services);
foreach($data as &$row){
    $row = preg_split('/ +/', $row);
    if(count($row) == 2){ //Hidden networks (no SSID) will merge the "Status" and "SSID" fields becuse both are empty and next to each other
        array_unshift($row, ""); //Adding an empty field will restore balance in the array ("Status", "SSID", "WiFi String")
    }
    $row[] = end(explode('_', $row[2])); //Adding a column with the securety attribute for easy read in JS side
}
array_pop($data);
echo json_encode($data);

echo '</response>';

?>
