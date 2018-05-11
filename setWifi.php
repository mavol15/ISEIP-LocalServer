<?php
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

echo '<response>';

$wifi_str = $_GET['wifi_str'];
$protocol = $_GET['protocol'];
$password = $_GET['password'];



$str = "connmanctl connect {$wifi_str}";

if($protocol = 'psk') $str .= " {$password}";

$status = shell_exec($str);

echo $status;

echo '</responce>';

?>
