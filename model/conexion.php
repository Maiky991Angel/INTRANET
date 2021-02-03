<?php
$serverName = 'SRVMED02\SRVMED02';
$connectionInfo = array( "Database"=>"FactuXX", "UID"=>"sa", "PWD"=>"abcd.1234");

$conn = sqlsrv_connect($serverName, $connectionInfo);

if(!$conn ) {
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>